<?php

namespace App\Services;

use App\Enums\WaitlistStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Loan;
use App\Models\Waitlist;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;

class WaitlistService
{
    /**
     * RF: cria uma solicitação de fila resolvendo o morph type e validando indisponibilidade.
     * Uso: cadastro de reservas para itens sem estoque disponível no momento.
     */
    public function store(array $data): Waitlist
    {
        return DB::transaction(function () use ($data) {
            $modelClass = Relation::getMorphedModel($data['waitlistable_type'])
                ?? $data['waitlistable_type'];

            $item = $modelClass::lockForUpdate()->findOrFail($data['waitlistable_id']);

            $data['waitlistable_type'] = $item->getMorphClass();

            $this->validateNewWaitlist($item, $data);

            return Waitlist::create([
                'waitlistable_id' => $item->id,
                'waitlistable_type' => $data['waitlistable_type'],
                'student_id' => $data['student_id'] ?? null,
                'professional_id' => $data['professional_id'] ?? null,
                'user_id' => $data['user_id'],
                'requested_at' => now(),
                'status' => WaitlistStatus::WAITING->value,
                'observation' => $data['observation'] ?? null,
            ]);
        });
    }

    /**
     * RF: atualiza uma solicitação de fila somente com campos permitidos e estado válido.
     * Uso: manutenção operacional de observações e status do atendimento.
     */
    public function update(Waitlist $waitlist, array $data): Waitlist
    {
        $this->validateStatusModification($waitlist, $data);

        $waitlist->update($this->filterUpdatableFields($data));

        return $waitlist->fresh();
    }

    /**
     * RF: remove uma solicitação de fila quando o estado permitir exclusão.
     * Uso: correção administrativa de reservas registradas indevidamente.
     */
    public function delete(Waitlist $waitlist): void
    {
        $this->validateDeletion($waitlist);
        $waitlist->delete();
    }

    /**
     * RF: cancela uma solicitação ainda em espera.
     * Uso: fluxo manual de desistência do beneficiário na lista de espera.
     */
    public function cancel(Waitlist $waitlist): Waitlist
    {
        $currentStatus = WaitlistStatus::tryFrom($waitlist->status);

        if ($currentStatus !== WaitlistStatus::WAITING) {
            throw new BusinessRuleException('Apenas solicitações em espera podem ser canceladas.');
        }

        $waitlist->update(['status' => WaitlistStatus::CANCELLED->value]);

        return $waitlist->fresh();
    }

    /**
     * RF: notifica a próxima solicitação elegível de um item e altera seu status.
     * Uso: integração com devoluções e liberações de estoque no acervo.
     */
    public function notifyNext($item): ?Waitlist
    {
        $next = Waitlist::where('waitlistable_id', $item->id)
            ->where('waitlistable_type', $item->getMorphClass())
            ->where('status', WaitlistStatus::WAITING->value)
            ->oldest('requested_at')
            ->first();

        if (!$next) return null;

        $next->update(['status' => WaitlistStatus::NOTIFIED->value]);

        return $next->fresh();
    }

    /**
     * RF: centraliza as validações de criação de uma nova solicitação de fila.
     * Uso: protege o cadastro contra inconsistências de beneficiário, estoque e duplicidade.
     */
    private function validateNewWaitlist($item, array $data): void
    {
        $this->validateBeneficiary($data);
        $this->ensureNoStockAvailable($item);
        $this->ensureNoDuplicateEntry($item, $data);
    }

    /**
     * RF: valida que a solicitação tenha exatamente um beneficiário elegível.
     * Uso: impede reservas sem destinatário ou com aluno e profissional simultaneamente.
     */
    private function validateBeneficiary(array $data): void
    {
        $student = $data['student_id'] ?? null;
        $professional = $data['professional_id'] ?? null;

        if (empty($student) && empty($professional)) {
            throw new BusinessRuleException('É necessário informar um aluno ou um profissional.');
        }

        if (!empty($student) && !empty($professional)) {
            throw new BusinessRuleException('Não é permitido informar aluno e profissional ao mesmo tempo.');
        }
    }

    /**
     * RF: impede entrada na fila quando o item ainda pode ser emprestado diretamente.
     * Uso: força o uso do fluxo de empréstimo enquanto houver disponibilidade real.
     */
    private function ensureNoStockAvailable($item): void
    {
        $status = $item->status;

        if (!$status->blocksLoan() && $item->quantity_available > 0) {
            throw new BusinessRuleException('Este recurso ainda possui unidades disponíveis e pode ser emprestado, portanto não é possível criar uma fila de espera.');
        }
    }

    /**
     * RF: impede duplicidade de fila ou empréstimo ativo para o mesmo beneficiário e item.
     * Uso: mantém a lista de espera coerente com o histórico de atendimento.
     */
    private function ensureNoDuplicateEntry($item, array $data): void
    {
        $student = $data['student_id'] ?? null;
        $professional = $data['professional_id'] ?? null;

        $existsQuery = Waitlist::where('waitlistable_id', $item->id)
            ->where('waitlistable_type', $item->getMorphClass())
            ->whereIn('status', [
                WaitlistStatus::WAITING->value,
                WaitlistStatus::NOTIFIED->value
            ]);

        if ($student) $existsQuery->where('student_id', $student);
        else $existsQuery->where('professional_id', $professional);

        if ($existsQuery->exists()) {
            throw new BusinessRuleException('Este beneficiário já possui uma solicitação ativa para este recurso.');
        }

        $loanQuery = Loan::where('loanable_id', $item->id)
            ->where('loanable_type', $item->getMorphClass())
            ->whereNull('return_date');

        if ($student) $loanQuery->where('student_id', $student);
        else $loanQuery->where('professional_id', $professional);

        if ($loanQuery->exists()) {
            throw new BusinessRuleException('Este beneficiário já possui um empréstimo ativo deste recurso.');
        }
    }

    /**
     * RF: valida alterações de status respeitando a imutabilidade de registros finalizados.
     * Uso: protege o histórico da fila, permitindo apenas ajustes compatíveis com o estado atual.
     */
    private function validateStatusModification(Waitlist $waitlist, array $data): void
    {
        if (!isset($data['status'])) return;

        $currentStatus = WaitlistStatus::tryFrom($waitlist->status);

        $updatableKeys = array_keys($data);
        $onlyObservation = count($updatableKeys) === 1 && in_array('observation', $updatableKeys);

        if (!$onlyObservation && in_array($currentStatus, [WaitlistStatus::FULFILLED, WaitlistStatus::CANCELLED], true)) {
            throw new BusinessRuleException('Solicitação já finalizada não pode ser alterada, exceto observações.');
        }
    }

    /**
     * RF: bloqueia exclusão de solicitações já atendidas.
     * Uso: preserva o histórico operacional de reservas concluídas.
     */
    private function validateDeletion(Waitlist $waitlist): void
    {
        $currentStatus = WaitlistStatus::tryFrom($waitlist->status);

        if ($currentStatus === WaitlistStatus::FULFILLED) {
            throw new BusinessRuleException('Solicitações já atendidas não podem ser removidas.');
        }
    }

    /**
     * RF: filtra os campos que podem ser alterados após a criação da solicitação.
     * Uso: restringe updates a status e observação no fluxo de manutenção da fila.
     */
    private function filterUpdatableFields(array $data): array
    {
        return collect($data)
            ->only(['status', 'observation'])
            ->toArray();
    }
}
