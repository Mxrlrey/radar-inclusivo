<?php

namespace App\Services;

use App\Enums\LoanStatus;
use App\Enums\ResourceStatus;
use App\Enums\WaitlistStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Loan;
use App\Models\Waitlist;
use App\Notifications\ItemAvailableNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LoanService
{
    /**
     * RF: injeta o serviço responsável por integrar o fluxo de empréstimos com lista de espera.
     * Uso: permite notificar e atualizar reservas ao criar ou finalizar empréstimos.
     */
    public function __construct(
        protected WaitlistService $waitlistService
    ) {}

    /**
     * RF: cria um empréstimo com lock de estoque, validações e baixa automática na fila.
     * Uso: registro operacional de saída de materiais e tecnologias assistivas.
     */
    public function store(array $data): Loan
    {
        return DB::transaction(function () use ($data) {
            $item = $data['loanable_type']::lockForUpdate()
                ->findOrFail($data['loanable_id']);

            $data['loanable_type'] = $item->getMorphClass();

            $this->validateNewLoan($item, $data);

            $this->handleStockDecrement($item);

            $loan = Loan::create([
                ...$data,
                'status' => LoanStatus::ACTIVE,
                'return_date' => null,
                'user_id' => $data['user_id'] ?? auth()->id(),
            ]);

            $this->fulfillWaitlistIfExists(
                $item,
                $data['student_id'] ?? null,
                $data['professional_id'] ?? null
            );

            return $loan;
        });
    }

    /**
     * RF: atualiza apenas campos seguros do empréstimo para preservar o histórico.
     * Uso: edição restrita de observações em registros já lançados.
     */
    public function update(Loan $loan, array $data): Loan
    {
        return DB::transaction(function () use ($loan, $data) {
            $safeData = array_intersect_key($data, array_flip(['observation']));

            if (array_key_exists('observation', $safeData)) {
                $loan->update([
                    'observation' => $safeData['observation']
                ]);
            }

            return $loan->fresh();
        });
    }

    /**
     * RF: exclui um empréstimo restaurando estoque e notificando a fila quando aplicável.
     * Uso: correção administrativa de registros lançados indevidamente.
     */
    public function delete(Loan $loan): void
    {
        DB::transaction(function () use ($loan) {
            if ($loan->return_date === null) {
                $item = $loan->loanable()->lockForUpdate()->first();

                $this->handleStockIncrement($item, LoanStatus::RETURNED);

                $nextWaitlist = $this->waitlistService->notifyNext($item);

                if ($nextWaitlist) {
                    auth()->user()->notify(new ItemAvailableNotification($nextWaitlist));
                }
            }

            $loan->delete();
        });
    }

    /**
     * RF: finaliza um empréstimo calculando status de devolução e reposição de estoque.
     * Uso: fluxo de devolução de itens emprestados a alunos e profissionais.
     */
    public function markAsReturned(Loan $loan, array $data = []): Loan
    {
        return DB::transaction(function () use ($loan, $data) {

            if ($loan->return_date !== null) {
                throw new BusinessRuleException('Este empréstimo já foi finalizado.');
            }

            $item = $loan->loanable()->lockForUpdate()->first();

            $returnDate = now();
            $isDamaged = !empty($data['is_damaged']);

            $statusEnum = $isDamaged
                ? LoanStatus::DAMAGED
                : ($returnDate->greaterThan($loan->due_date)
                    ? LoanStatus::LATE
                    : LoanStatus::RETURNED);

            $loan->update([
                'return_date' => $returnDate,
                'status' => $statusEnum,
                'observation' => $data['observation'] ?? $loan->observation,
            ]);

            $this->handleStockIncrement($item, $statusEnum);

            if (!$isDamaged) {
                $nextWaitlist = $this->waitlistService->notifyNext($item);

                if ($nextWaitlist) {
                    auth()->user()->notify(new ItemAvailableNotification($nextWaitlist));
                }
            }

            return $loan->fresh();
        });
    }

    /**
     * RF: reduz o estoque disponível do item emprestado quando ele não é digital.
     * Uso: mantém o inventário sincronizado no momento da retirada.
     */
    private function handleStockDecrement($item): void
    {
        if ($item->is_digital) return;

        if ($item->quantity_available <= 0) {
            throw new BusinessRuleException('Não há unidades disponíveis em estoque.');
        }

        $newAvailable = $item->quantity_available - 1;

        $item->update([
            'quantity_available' => $newAvailable,
            'status' => $newAvailable <= 0
                ? ResourceStatus::IN_USE
                : $item->status,
        ]);

        $item->refresh();
    }

    /**
     * RF: recompõe o estoque do item devolvido e ajusta seu status operacional.
     * Uso: atualização de inventário ao devolver ou cancelar empréstimos ativos.
     */
    private function handleStockIncrement($item, LoanStatus $status): void
    {
        if (!$item || $item->is_digital) return;

        $newStatus = $status === LoanStatus::DAMAGED
            ? ResourceStatus::DAMAGED
            : ResourceStatus::AVAILABLE;

        $item->update([
            'quantity_available' => $item->quantity_available + 1,
            'status'             => $newStatus,
        ]);

        $item->refresh();
    }

    /**
     * RF: valida se a nova quantidade total suporta os empréstimos em aberto.
     * Uso: protege edições de estoque em materiais e tecnologias emprestáveis.
     */
    public function validateStockAvailability($item, int $quantity): void
    {
        if ($item->is_digital) return;

        $activeLoans = $item->exists
            ? $item->loans()
                ->whereIn('status', LoanStatus::openStatuses())
                ->count()
            : 0;

        if ($quantity < $activeLoans) {
            throw new BusinessRuleException("Impossível reduzir estoque: existem {$activeLoans} unidades emprestadas.");
        }
    }

    /**
     * RF: recalcula a quantidade disponível do item com base no total e nos empréstimos ativos.
     * Uso: sustenta cadastros e edições de recursos emprestáveis no acervo.
     */
    public function calculateStockForLoan($item, array $data): array
    {
        $isDigital = $data['is_digital'] ?? $item->is_digital ?? false;

        if ($isDigital) {
            $data['quantity_available'] = null;
            return $data;
        }

        $total = (int) ($data['quantity'] ?? $item->quantity ?? 0);

        $activeLoans = $item->exists
            ? $item->loans()
                ->whereIn('status', LoanStatus::openStatuses())
                ->count()
            : 0;

        $data['quantity_available'] = $total - $activeLoans;

        return $data;
    }

    /**
     * RF: centraliza as validações necessárias antes de criar um novo empréstimo.
     * Uso: protege o fluxo de retirada contra inconsistências de beneficiário e disponibilidade.
     */
    private function validateNewLoan($item, array $data): void
    {
        $this->validateBeneficiary($data);
        $this->checkActiveLoanPendency($data);
        $this->validateResourceAvailability($item);
    }

    /**
     * RF: valida se o recurso está em condição de ser emprestado.
     * Uso: bloqueia empréstimos de itens indisponíveis por status ou conservação.
     */
    private function validateResourceAvailability($item): void
    {
        if ($item->is_digital) return;

        if ($item->status->blocksLoan()) {
            throw new BusinessRuleException("O recurso está com status '{$item->status->label()}', que bloqueia empréstimos.");
        }

        if ($item->conservation_state?->blocksLoan()) {
            throw new BusinessRuleException("O estado '{$item->conservation_state->label()}' bloqueia empréstimos.");
        }
    }

    /**
     * RF: impede empréstimos duplicados do mesmo item para o mesmo beneficiário.
     * Uso: preserva rotatividade e evita duplicidade de posse simultânea.
     */
    private function checkActiveLoanPendency(array $data): void
    {
        $exists = Loan::where('loanable_id', $data['loanable_id'])
            ->where('loanable_type', $data['loanable_type'])
            ->whereNull('return_date')
            ->where(function ($q) use ($data) {
                if (!empty($data['student_id'])) {
                    $q->where('student_id', $data['student_id']);
                } else {
                    $q->where('professional_id', $data['professional_id']);
                }
            })
            ->exists();

        if ($exists) {
            throw new BusinessRuleException('Este beneficiário já possui um empréstimo ativo deste recurso.');
        }
    }

    /**
     * RF: valida que o empréstimo tenha exatamente um beneficiário elegível.
     * Uso: impede registros sem destinatário ou com aluno e profissional ao mesmo tempo.
     */
    private function validateBeneficiary(array $data, ?Loan $loan = null): void
    {
        if ($loan && $loan->status !== LoanStatus::ACTIVE) {
            return;
        }

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
     * RF: consulta os empréstimos vencidos ainda em aberto no sistema.
     * Uso: painéis, alertas e rotinas operacionais de cobrança de devolução.
     */
    public function getOverdueLoans(): Collection
    {
        return Loan::where('status', LoanStatus::ACTIVE)
            ->where('due_date', '<', now())
            ->with(['student.person', 'loanable'])
            ->get();
    }

    /**
     * RF: marca como atendida a reserva correspondente ao empréstimo efetivado.
     * Uso: mantém a lista de espera coerente quando o item é liberado ao beneficiário.
     */
    private function fulfillWaitlistIfExists($item, ?int $studentId, ?int $professionalId): void
    {
        $query = Waitlist::where('waitlistable_id', $item->id)
            ->where('waitlistable_type', $item->getMorphClass())
            ->whereIn('status', [
                WaitlistStatus::WAITING->value,
                WaitlistStatus::NOTIFIED->value
            ]);

        if ($studentId) {
            $query->where('student_id', $studentId);
        } elseif ($professionalId) {
            $query->where('professional_id', $professionalId);
        }

        $waitlist = $query->first();

        if ($waitlist) {
            $waitlist->update([
                'status' => WaitlistStatus::FULFILLED->value
            ]);
        }
    }
}
