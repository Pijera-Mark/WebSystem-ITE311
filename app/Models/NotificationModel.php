<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'title', 'message', 'type', 'is_read'];
    protected $useTimestamps = false;
    
    public function createNotification($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }
    
    public function getUserNotifications($user_id, $limit = 10)
    {
        return $this->where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
    
    public function getUnreadCount($user_id)
    {
        return $this->where('user_id', $user_id)
            ->where('is_read', false)
            ->countAllResults();
    }
    
    public function markAsRead($notification_id, $user_id)
    {
        return $this->where('id', $notification_id)
            ->where('user_id', $user_id)
            ->set(['is_read' => true, 'updated_at' => date('Y-m-d H:i:s')])
            ->update();
    }
    
    public function markAllAsRead($user_id)
    {
        return $this->where('user_id', $user_id)
            ->where('is_read', false)
            ->set(['is_read' => true, 'updated_at' => date('Y-m-d H:i:s')])
            ->update();
    }
    
    public function deleteNotification($notification_id, $user_id)
    {
        return $this->where('id', $notification_id)
            ->where('user_id', $user_id)
            ->delete();
    }
}
