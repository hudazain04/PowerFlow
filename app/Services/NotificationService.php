<?php
namespace App\Services;

use App\ApiHelper\ApiCode;
use App\Exceptions\ErrorException;
use App\Models\User;
use App\Models\Employee;
use App\Notifications\SystemNotification;
use App\Types\UserTypes;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationService
{

    public function baseSendNotification($title, $body, array $fcmTokens)
    {
        $firebase = (new Factory())
            ->withServiceAccount(storage_path('app/firebase/firebase_config.json'));

        $messaging = $firebase->createMessaging();

        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body,
        ]);

        $message = CloudMessage::new();

        $message = $message->withNotification($notification);

        $messaging->sendMulticast($message, $fcmTokens);
    }
    public function notifyAdmins(array $data)
    {
        $admins = User::role(UserTypes::ADMIN)->whereNotNull("fcm_token")->pluck('fcm_token');
        $this->baseSendNotification($data->title, $data->body, $admins);

    }

    public function notifyUsers(array $data)
    {
        $users = User::role(UserTypes::USER)->whereNotNull("fcm_token")->pluck('fcm_token');
        // Notification::send($users, new SystemNotification($data));
        $this->baseSendNotification($data->title, $data->body, $users);
    }

    public function notifyEmployees(array $data)
    {
        $employees = Employee::role(UserTypes::EMPLOYEE)->whereNotNull("fcm_token")->pluck('fcm_token');
        Notification::send($employees, new SystemNotification($data));
        $this->baseSendNotification($data->title, $data->body, $employees);
    }

    public function notifyAll(array $data)
    {
        $this->notifyAdmins($data);
        $this->notifyUsers($data);
        $this->notifyEmployees($data);
    }

    public function notifyCustomUser(array $ids, array $data = [])
    {
        $users = User::whereIn('id', $ids)->whereNotNull("fcm_token")->pluck('fcm_token');

        $this->baseSendNotification($data->title, $data->body, $users);
    }


    public function notifyCustomAdmin(array $ids, array $data = [])
    {
        $users = User::role(UserTypes::ADMIN)->whereIn('id', $ids)->whereNotNull("fcm_token")->pluck('fcm_token');

        $this->baseSendNotification($data->title, $data->body, $users);
    }

    public function notifyCustomEmployee(array $ids, array $data = [])
    {
        $employees = Employee::whereIn('id', $ids)->whereNotNull("fcm_token")->pluck('fcm_token');

        $this->baseSendNotification($data->title, $data->body, $employees);

    }

    public function getAll($user)
    {
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $user->unreadNotifications->markAsRead();
        return $notifications;
    }

    public function show($user, $id)
    {
        $notification = $user->notifications()
            ->where('id', $id)
            ->first();
        if (!$notification) {
            throw new ErrorException(__('notification.notFound'), ApiCode::NOT_FOUND);
        }
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return $notification;
    }
}
