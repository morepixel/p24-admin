<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Report $report,
        protected string $oldStatus,
        protected string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Status des Vorgangs wurde geändert')
            ->greeting('Hallo ' . $notifiable->name)
            ->line("Der Status des Vorgangs #{$this->report->id} wurde geändert.")
            ->line("Alter Status: {$this->oldStatus}")
            ->line("Neuer Status: {$this->newStatus}")
            ->action('Vorgang anzeigen', url("/admin/reports/{$this->report->id}"))
            ->line('Vielen Dank für Ihre Aufmerksamkeit!');
    }
}
