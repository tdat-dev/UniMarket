<?php

namespace App\Controllers;

use App\Middleware\VerificationMiddleware;

class ChatController extends BaseController
{
    public function index()
    {
        VerificationMiddleware::requireVerified();
        // Require login for chat
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            // Redirect to login handled by middleware ideally, but basic check here
            header('Location: /login');
            exit;
             header('Location: /login');
             exit;
        }
        
        $currentUserId = $_SESSION['user']['id'];
        $messageModel = new \App\Models\Message();
        $userModel = new \App\Models\User();

        // 1. Get List of Conversations
        $conversations = $messageModel->getRecentConversations($currentUserId);
        
        // 2. Identify Active Conversation
        $activePartnerId = $_GET['user_id'] ?? null;
        $activePartner = null;
        $messages = [];

        if ($activePartnerId) {
             // Validate partner
             $activePartner = $userModel->find($activePartnerId);
             
             // Prevent chatting with self
             if ($activePartnerId == $currentUserId) {
                 header('Location: /chat');
                 exit;
             }

             if ($activePartner) {
                 // Fetch messages
                 $messages = $messageModel->getConversation($currentUserId, $activePartnerId);
             }
        } elseif (!empty($conversations)) {
            // Default to first conversation
            $first = $conversations[0];
            $activePartnerId = $first['partner']['id'];
            $activePartner = $first['partner'];
            $messages = $messageModel->getConversation($currentUserId, $activePartnerId);
        }

        $this->view('chat/index', [
            'conversations' => $conversations,
            'activePartner' => $activePartner,
            'messages' => $messages,
            'currentUserId' => $currentUserId
        ]);
    }

    public function send()
    {
        if (session_status() == PHP_SESSION_NONE) {
             session_start();
        }

        if (!isset($_SESSION['user'])) {
             echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
             exit;
        }

        $receiverId = $_POST['receiver_id'] ?? null;
        $content = trim($_POST['content'] ?? '');

        if (!$receiverId || empty($content)) {
             echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
             exit;
        }

        // Prevent sending message to self
        if ($receiverId == $_SESSION['user']['id']) {
             echo json_encode(['status' => 'error', 'message' => 'Cannot send message to yourself']);
             exit;
        }

        $messageModel = new \App\Models\Message();
        $id = $messageModel->create([
            'sender_id' => $_SESSION['user']['id'],
            'receiver_id' => $receiverId,
            'content' => $content
        ]);

        if ($id) {
             // If ajax request, return json. If form submit, redirect.
             // For now assume standard form post or simple ajax handled by view
             // Let's just redirect back to chat
             header('Location: /chat?user_id=' . $receiverId);
             exit;
        } else {
             // Handle error
             header('Location: /chat?user_id=' . $receiverId . '&error=1');
             exit;
        }
    }
}
