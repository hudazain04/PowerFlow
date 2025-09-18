<?php
namespace App\Services;

use App\Models\User;
use App\Models\Employee;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
    {
        public function notifyAdmins(array $data)
        {
            $admins = User::role('admin')->get();
            Notification::send($admins, new SystemNotification($data));

        }

        public function notifyUsers(array $data)
        {
            $users = User::role('user')->get();
            Notification::send($users, new SystemNotification($data));
        }

        public function notifyEmployees(array $data)
        {
            $employees = Employee::role('employee')->get();
            Notification::send($employees, new SystemNotification($data));
        }

        public function notifyAll(array $data)
        {
            $this->notifyAdmins($data);
            $this->notifyUsers($data);
            $this->notifyEmployees($data);
        }

        public function notifyCustomUser(array $ids ,  array $data = [])
        {
            $users = User::whereIn('id', $ids)->get();
            Notification::send($users, new SystemNotification($data));
        }
        public function notifyCustomEmployee(array $ids , array $data = [])
        {
            $employees = Employee::whereIn('id', $ids)->get();
            Notification::send($employees, new SystemNotification($data));

        }
    }
