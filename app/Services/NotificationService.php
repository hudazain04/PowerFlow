<?php

namespace App\Services;

use App\ApiHelper\ApiCode;
use App\Exceptions\ErrorException;
use App\Models\User;
use App\Models\Employee;
use App\Notifications\SystemNotification;
use App\Repositories\Eloquent\Notification\NotificationRepository;
use App\Types\UserTypes;
use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationService
{


    public function __construct(
        protected NotificationRepository $notificationRepository
    ) {
    }

    public function storeNotification(array $data, array $userIds, ?Model $notifier = null)
    {
        $notification = $this->notificationRepository->createNotification($data, $notifier);

        $this->notificationRepository->attachUsers($notification, $userIds);

        return $notification;
    }


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
        $admins = User::role(UserTypes::ADMIN)
            ->whereNotNull("fcmToken")
            ->get(['id', 'fcmToken']);

        if ($admins->isEmpty()) {
            return;
        }
        $tokens = $admins->pluck('fcmToken')->toArray();
        if (count($admins) === 0) {
            return;
        }
        $this->baseSendNotification($data["title"], $data["body"], $tokens);
        $this->storeNotification($data, $admins->all(), auth()->user());
    }

    public function notifyUsers(array $data)
    {
        $users = User::role(UserTypes::USER)
            ->whereNotNull("fcmToken")
            ->get(['id', 'fcmToken']);

        if ($users->isEmpty()) {
            return;
        }
        $tokens = $users->pluck('fcmToken')->toArray();
        if (count($users) === 0) {
            return;
        }
        $this->baseSendNotification($data["title"], $data["body"], $tokens);
        $this->storeNotification($data, $users->all(), auth()->user());
    }

    public function notifyEmployees(array $data)
    {
        $employees = Employee::role(UserTypes::EMPLOYEE)
            ->whereNotNull("fcmToken")
            ->get(['id', 'fcmToken']);

        if ($employees->isEmpty()) {
            return;
        }
        $tokens = $employees->pluck('fcmToken')->toArray();
        if (count($employees) === 0) {
            return;
        }
        $this->baseSendNotification($data["title"], $data["body"], $tokens);
        $this->storeNotification($data, $employees->all(), auth()->user());
    }

    public function notifyAll(array $data)
    {
        $this->notifyAdmins($data);
        $this->notifyUsers($data);
        $this->notifyEmployees($data);
    }

    public function notifyCustomUser(array $data = [])
    {
        $users = User::whereIn('id', $data["ids"])->whereNotNull("fcmToken")->get(['id', 'fcmToken']);
        if (count($users) === 0) {
            return;
        }
        $tokens = $users->pluck('fcmToken')->toArray();
        $this->baseSendNotification($data["title"], $data["body"], $tokens);
        $this->storeNotification($data, $users->all(), auth()->user());
    }


    public function notifyCustomAdmin(array $data = [])
    {
        $users = User::role(UserTypes::ADMIN)->whereIn('id', $data["ids"])->whereNotNull("fcmToken")->get(['id', 'fcmToken']);
        if (count($users) === 0) {
            return;
        }

        $tokens = $users->pluck('fcmToken')->toArray();
        $this->baseSendNotification($data["title"], $data["body"], $tokens);
        $this->storeNotification($data, $users->all(), auth()->user());
    }

    public function notifyCustomEmployee(array $data = [])
    {
        $employees = Employee::whereIn('id', $data["ids"])->whereNotNull("fcmToken")->get(['id', 'fcmToken']);
        if (count($employees) === 0) {
            return;
        }
        $tokens = $employees->pluck('fcmToken')->toArray();
        $this->baseSendNotification($data["title"], $data["body"], $tokens);
        $this->storeNotification($data, $employees->all(), auth()->user());

    }

    public function getAll($user)
    {
        $notifications = $this->notificationRepository->getMyNotifications($user);
        return $notifications;
    }
    public function getSentNotifications($user)
    {
        $notifications = $this->notificationRepository->getNotificationsSentByMe($user);
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

    public function getUnRead($user)
    {
        $notifications = $this->notificationRepository->getUnReadNotifications($user);
        return $notifications;
    }
    public function markNotificationAsRead($user)
    {
        return $this->notificationRepository->markNotificationAsRead($user);
    }
}
