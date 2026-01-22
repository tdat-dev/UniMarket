<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Notification;
use App\Models\User;

/**
 * NotificationController - Gửi thông báo hàng loạt cho Admin
 */
class NotificationController extends AdminBaseController
{
    private Notification $notifModel;
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->notifModel = new Notification();
        $this->userModel = new User();
    }

    /**
     * Form gửi thông báo
     */
    public function broadcast()
    {
        // Lấy thống kê users
        $stats = [
            'total' => $this->userModel->count(),
            'buyers' => $this->userModel->countByRole('buyer'),
            'sellers' => $this->userModel->countByRole('seller'),
        ];

        $this->view('notifications/broadcast', [
            'title' => 'Gửi Thông báo',
            'stats' => $stats
        ]);
    }

    /**
     * Xử lý gửi thông báo
     */
    public function sendBroadcast()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/notifications/broadcast');
            exit;
        }

        $content = trim($_POST['content'] ?? '');
        $target = $_POST['target'] ?? 'all'; // all, buyers, sellers
        $type = $_POST['type'] ?? 'system';

        if (empty($content)) {
            $_SESSION['error'] = 'Nội dung không được để trống';
            header('Location: /admin/notifications/broadcast');
            exit;
        }

        // Lấy danh sách users theo target
        switch ($target) {
            case 'buyers':
                $users = $this->userModel->getByRole('buyer');
                break;
            case 'sellers':
                $users = $this->userModel->getByRole('seller');
                break;
            default:
                $users = $this->userModel->getAll();
        }

        $sent = 0;
        foreach ($users as $user) {
            try {
                $this->notifModel->createNotification(
                    (int) $user['id'],
                    $content,
                    $type
                );
                $sent++;
            } catch (\Exception $e) {
                // Log error but continue
                error_log("Failed to send notification to user #{$user['id']}: " . $e->getMessage());
            }
        }

        $_SESSION['success'] = "Đã gửi thông báo đến {$sent} người dùng!";
        header('Location: /admin/notifications/broadcast');
        exit;
    }
}
