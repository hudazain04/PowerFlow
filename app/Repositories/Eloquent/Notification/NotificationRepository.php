<?php
namespace App\Repositories\Eloquent\Notification;

use App\Models\Notification;
use App\Models\NotificationUser;
use Illuminate\Database\Eloquent\Model;

class NotificationRepository
{
    public function createNotification(array $data, Model $notifier = null): Notification
    {
        return Notification::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'type' => $data['type'],
            'notifier_id' => $notifier?->getKey(),
            'notifier_type' => $notifier ? get_class($notifier) : null,
        ]);
    }

    public function attachUsers(Notification $notification, array $models): void
    {
        $rows = collect($models)->map(fn(Model $model) => [
            'notification_id' => $notification->id,
            'notified_id' => $model->getKey(),
            'notified_type' => get_class($model),
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        NotificationUser::insert($rows);
    }


    public function getMyNotifications($user, $paginate = 20)
    {
        $received = $user->receivedNotifications()
            ->orderByDesc('notification_user.created_at')
            ->paginate($paginate);


        return $received;
    }
    public function getUnReadNotifications($user, $paginate = 20)
    {
        $received = $user->receivedNotifications()
            ->orderByDesc('notification_user.created_at')
            ->wherePivot('is_read', false)
            ->paginate($paginate);

        return $received;
    }

    public function getNotificationsSentByMe($user, $paginate = 20)
    {
        return $user->sentNotifications()
            ->orderByDesc('created_at')
            ->paginate($paginate);
    }

    public function markNotificationAsRead($user)
    {
        return $user->receivedNotifications()
            ->wherePivot('is_read', false)
            ->update(['is_read' => true]);
    }
}
