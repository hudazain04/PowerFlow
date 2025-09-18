<?php

namespace App\Http\Controllers;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\Http\Requests\Notification\SendNotificationRequest;
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
}
