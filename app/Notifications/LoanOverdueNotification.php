<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LoanOverdueNotification extends Notification
{
    use Queueable;

    public function __construct(private Loan $loan) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $beneficiary = $this->loan->student
            ? $this->loan->student->person->name
            : ($this->loan->professional ? $this->loan->professional->person->name : 'N/A');

        $itemName = $this->loan->loanable->name ?? 'Item';
        $daysOverdue = (int) ceil(now()->diffInDays($this->loan->due_date, false));
        $daysOverdue = abs($daysOverdue);

        return [
            'loan_id' => $this->loan->id,
            'title'   => 'Empréstimo Atrasado',
            'message' => "O item '{$itemName}' está com o beneficiário {$beneficiary} e encontra-se atrasado há {$daysOverdue} dia(s).",
            'url'     => route('emprestimos.visualizar', $this->loan->id),
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
