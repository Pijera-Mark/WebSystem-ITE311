<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    public function getNotifications()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }
        
        $userId = session()->get('userID');
        $notificationModel = new NotificationModel();
        
        // Get notifications and unread count
        $notifications = $notificationModel->getUserNotifications($userId, 5);
        $unreadCount = $notificationModel->getUnreadCount($userId);
        
        // Format notifications for display
        $formattedNotifications = [];
        foreach ($notifications as $notification) {
            $formattedNotifications[] = [
                'id' => $notification['id'],
                'title' => $notification['title'],
                'message' => $notification['message'],
                'type' => $notification['type'],
                'is_read' => $notification['is_read'],
                'created_at' => date('M d, Y h:i A', strtotime($notification['created_at'])),
                'time_ago' => $this->timeAgo($notification['created_at'])
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'notifications' => $formattedNotifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    public function markAsRead()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }
        
        $notificationId = $this->request->getPost('notification_id');
        $userId = session()->get('userID');
        $notificationModel = new NotificationModel();
        
        if ($notificationModel->markAsRead($notificationId, $userId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to mark notification as read'
            ]);
        }
    }
    
    public function markAllAsRead()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }
        
        $userId = session()->get('userID');
        $notificationModel = new NotificationModel();
        
        if ($notificationModel->markAllAsRead($userId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to mark all notifications as read'
            ]);
        }
    }
    
    public function getUnreadCount()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }
        
        $userId = session()->get('userID');
        $notificationModel = new NotificationModel();
        $unreadCount = $notificationModel->getUnreadCount($userId);
        
        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }
    
    private function timeAgo($datetime)
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;
        
        if ($diff < 60) {
            return 'Just now';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return date('M d, Y', $time);
        }
    }
}
