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
    )
    {
    }

    public function notify(SendNotificationRequest $request)
    {
        $methodName = "notify" . ucfirst($request->type);

        if (!method_exists($this->notificationService, $methodName)) {
            return $this->success(null,__('notification.invalidType'),ApiCode::BAD_REQUEST);
        }

        $result = $this->notificationService->{$methodName}(
            $request->title,
            $request->body,
            $request->ids?? null
        );

        return $this->success($result,__('notification.sent'));
    }

    public function getAll(Request $request)
    {
        $user=$request->user();
        $notifications=$this->notificationService->getAll($user);
        return $this->successWithPagination(NotificationResource::collection($notifications),__('messafes.success'));
    }

    public function show(Request $request ,$id)
    {
        $user=$request->user();
        $notification=$this->notificationService->show($user,$id);
        return $this->success(NotificationResource::make($notification),__('messages.success'));

    }
}
