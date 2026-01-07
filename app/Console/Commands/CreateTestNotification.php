<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class CreateTestNotification extends Command
{
    protected $signature = 'notification:test {user_id=1}';

    protected $description = 'Create a test notification for a user';

    public function handle(): int
    {
        $userId = $this->argument('user_id');
        $user = User::findOrFail($userId);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'test',
            'title' => 'Test Notification',
            'message' => 'This is a test notification from the command',
            'icon' => '🧪',
            'data' => ['test' => true],
        ]);

        $this->info("Test notification created for user {$user->name}");

        return 0;
    }
}
