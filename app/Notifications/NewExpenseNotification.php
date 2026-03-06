<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewExpenseNotification extends Notification
{
    use Queueable;

    protected $expense;
    protected $userName;

    /**
     * Create a new notification instance.
     */
    public function __construct($expense, $userName)
    {
        $this->expense = $expense;
        $this->userName = $userName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Hanya kirim via Database
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'expense_id' => $this->expense->id,
            'amount' => $this->expense->amount,
            'description' => $this->expense->description,
            'user_name' => $this->userName,
            'message' => "{$this->userName} telah menambahkan realisasi belanja baru sebesar Rp " . number_format($this->expense->amount, 0, ',', '.'),
            'type' => 'expense_created'
        ];
    }
}
