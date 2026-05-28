<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class TaskUnassignedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $user;

    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mise à jour tâche : Assignation retirée',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.task_unassigned',
        );
    }
}
