<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User; // Import the User model

class NewUserRegistered extends Notification implements ShouldQueue // Add ShouldQueue for better performance
{
    use Queueable;

    // Property to hold the newly registered user
    public User $newUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // We want to send an email and also store it in the database
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // $notifiable here is the admin receiving the email
        // $this->newUser is the user who just registered
        $profileCompletionUrl = route('member-profile.create');

        return (new MailMessage)
                    ->subject('New Member Registration: ' . $this->newUser->name)
                    ->greeting('Hello, Administrator!')
                    ->line('A new member has registered and needs to complete their profile.')
                    ->line('Name: ' . $this->newUser->name)
                    ->line('Email: ' . $this->newUser->email)
                    ->line('Registered At: ' . $this->newUser->created_at->format('M d, Y H:i A'))
                    ->line('Once they complete their profile, it will appear in the "Manage Pending Memberships" section for your review.')
                    ->action('Go to Dashboard', url('/dashboard'))
                    ->salutation('Regards, ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification. (For Database)
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // This is the data that will be stored in the `notifications` table
        return [
            'user_id' => $this->newUser->id,
            'user_name' => $this->newUser->name,
            'message' => "New user '{$this->newUser->name}' has registered.",
            'action_url' => route('admin.users.show', $this->newUser->id), // Link to view the new user's system profile
        ];
    }
}