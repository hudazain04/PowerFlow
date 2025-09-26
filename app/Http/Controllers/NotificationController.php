<?php

namespace App\Http\Controllers;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\Http\Requests\Notification\SendNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected NotificationService $notificationService,
    ) {
    }

    public function notify(SendNotificationRequest $request)
    {
        $methodName = "notify" . ucfirst($request->type);

        if (!method_exists($this->notificationService, $methodName)) {
            return $this->success(null, __('notification.invalidType'), ApiCode::BAD_REQUEST);
        }

        $result = $this->notificationService->{$methodName}([
            'title' => $request->title,
            'body' => $request->body,
            'type' => $request->type,
            'ids' => $request->ids ?? null
        ]);


        return $this->success($result, __('notification.sent'));
    }

    public function getAll(Request $request)
    {
        $user = $request->user();
        $notifications = $this->notificationService->getAll($user);
        return $this->successWithPagination(NotificationResource::collection($notifications), __('messages.success'));
    }
    public function getSentNotifications(Request $request)
    {
        $user = $request->user();
        $notifications = $this->notificationService->getSentNotifications($user);
        return $this->successWithPagination(NotificationResource::collection($notifications), __('messages.success'));
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $notification = $this->notificationService->show($user, $id);
        return $this->success(NotificationResource::make($notification), __('messages.success'));

    }

    public function getUnRead(Request $request)
    {
        $user = $request->user();
        $notifications = $this->notificationService->getUnRead($user);
        return $this->successWithPagination(NotificationResource::collection($notifications), __('messages.success'));
    }

    public function markNotificationAsRead(Request  $request)
    {
        $user = $request->user();
        $this->notificationService->markNotificationAsRead($user);
        return $this->success(null,__('notification.read'));
    }
}
