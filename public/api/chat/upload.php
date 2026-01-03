<?php
/**
 * API Upload Chat Attachment
 * POST /api/chat/upload
 */

header('Content-Type: application/json');

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check authentication
if (!isset($_SESSION['user']['id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Check file upload
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded or upload error']);
    exit;
}

$file = $_FILES['file'];
$userId = $_SESSION['user']['id'];

// ============ VALIDATION ============

// Allowed file types
$allowedTypes = [
    // Images
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp',
    // Documents
    'application/pdf' => 'pdf',
    'application/msword' => 'doc',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
    'application/vnd.ms-excel' => 'xls',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
    'text/plain' => 'txt',
];

// Max file size: 10MB
$maxSize = 10 * 1024 * 1024;

// Check file type
$fileType = mime_content_type($file['tmp_name']);
if (!isset($allowedTypes[$fileType])) {
    http_response_code(400);
    echo json_encode(['error' => 'File type not allowed']);
    exit;
}

// Check file size
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['error' => 'File too large. Max 10MB']);
    exit;
}

// ============ UPLOAD ============

// Generate unique filename
$extension = $allowedTypes[$fileType];
$newFileName = 'chat_' . $userId . '_' . time() . '_' . uniqid() . '.' . $extension;

// Upload directory
$uploadDir = __DIR__ . '/../../uploads/chat/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$uploadPath = $uploadDir . $newFileName;

// Move file
if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save file']);
    exit;
}

// ============ RESPONSE ============

$isImage = strpos($fileType, 'image/') === 0;

echo json_encode([
    'success' => true,
    'file' => [
        'name' => $file['name'],
        'path' => '/uploads/chat/' . $newFileName,
        'type' => $fileType,
        'size' => $file['size'],
        'is_image' => $isImage,
        'extension' => $extension
    ]
]);
