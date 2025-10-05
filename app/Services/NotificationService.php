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


    public function baseSendNotification($title, $body, array $fcmTokens, ?array $data = [])
    {
        $firebase = (new Factory())
            ->withServiceAccount(storage_path('app/firebase/firebase_config.json'));

        $messaging = $firebase->createMessaging();

        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);

        $message = CloudMessage::new();

        $message = $message->withNotification($notification);

        $messaging->sendMulticast($message, $fcmTokens);
    }




    public function notifyAdmins(array $data)
    {
        $authUser = auth()->user();
        $admins = User::role(UserTypes::ADMIN)
            ->whereNotNull("fcmToken")
            ->where('id', '!=', $authUser->id)
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

        $authUser = auth()->user();
        $generator = $authUser->powerGenerator;
        $query = User::role(UserTypes::USER)
            ->whereNotNull('fcmToken')
            ->where('id', '!=', $authUser->id);
        if ($authUser->hasRole(UserTypes::ADMIN) && $generator) {
            $query->whereHas('counters', function ($q) use ($generator) {
                $q->where('generator_id', $generator->id);
            });
        }

        $users = $query->get(['id', 'fcmToken']);
        //        $users = User::role(UserTypes::USER)
//            ->whereNotNull("fcmToken")
//            ->get(['id', 'fcmToken']);

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
        $authUser = auth()->user();
        $generator = $authUser->powerGenerator;
        $query = Employee::role(UserTypes::EMPLOYEE)
            ->whereNotNull("fcmToken")
            ->where('id', '!=', $authUser->id);
        if ($authUser->hasRole(UserTypes::ADMIN) && $generator) {
            $query->where('generator_id', $generator->id);
        }

        $employees = $query->get(['id', 'fcmToken']);
        //        $employees = Employee::role(UserTypes::EMPLOYEE)
//            ->whereNotNull("fcmToken")
//            ->get(['id', 'fcmToken']);

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
        $users = User::role(UserTypes::ADMIN)->whereIdIn($data["ids"])->whereNotNull("fcmToken")->get(['id', 'fcmToken']);
        if (count($users) === 0) {
            throw new ErrorException("jojo love hudhudte", 500, [
                $users,
                $data["ids"]
            ]);
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
