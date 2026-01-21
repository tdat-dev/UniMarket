<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Firebase Auth Service
 * 
 * Service xử lý xác thực Firebase ID Token từ frontend.
 * 
 * Flow:
 * 1. Frontend gọi Firebase signInWithPopup() → nhận ID token
 * 2. Frontend gửi token về backend qua POST request
 * 3. Backend verify token với Google's tokeninfo endpoint
 * 4. Nếu valid → trả về user info
 * 
 * @package App\Services
 */
class FirebaseAuthService
{
    private Client $httpClient;
    private array $config;

    /**
     * Google's token verification endpoint
     * Dùng để verify Firebase ID token
     */
    private const TOKEN_INFO_URL = 'https://oauth2.googleapis.com/tokeninfo';

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/firebase.php';

        // Disable SSL verification for localhost (Windows/Laragon)
        $this->httpClient = new Client([
            'verify' => false,
            'timeout' => 10
        ]);
    }

    /**
     * Verify Firebase ID Token và lấy thông tin user
     * 
     * @param string $idToken ID Token từ Firebase frontend
     * @return array|null User info hoặc null nếu token không hợp lệ
     * 
     * Trả về array với các field:
     * - email: Email của user
     * - full_name: Tên đầy đủ
     * - avatar: URL ảnh đại diện
     * - email_verified: true/false
     */
    public function verifyIdToken(string $idToken): ?array
    {
        try {
            // Gọi Google tokeninfo endpoint để verify token
            $response = $this->httpClient->get(self::TOKEN_INFO_URL, [
                'query' => ['id_token' => $idToken]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            // Kiểm tra token có thuộc về project của mình không
            // aud (audience) phải match với Firebase project ID
            if (!$this->isValidAudience($data['aud'] ?? '')) {
                error_log("Firebase Auth: Invalid audience - " . ($data['aud'] ?? 'null'));
                return null;
            }

            // Token hợp lệ, trả về user info
            return [
                'email' => $data['email'] ?? null,
                'full_name' => $data['name'] ?? $data['email'] ?? 'User',
                'avatar' => $data['picture'] ?? null,
                'email_verified' => ($data['email_verified'] ?? 'false') === 'true'
            ];

        } catch (GuzzleException $e) {
            error_log("Firebase Auth Error: " . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            error_log("Firebase Auth Exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Kiểm tra audience có hợp lệ không
     * 
     * Audience phải là Firebase App ID của project
     * Điều này đảm bảo token được tạo từ app của mình
     */
    private function isValidAudience(string $audience): bool
    {
        $validAudiences = [
            $this->config['app_id'] ?? '',
            $this->config['project_id'] ?? '',
        ];

        return in_array($audience, $validAudiences, true);
    }

    /**
     * Kiểm tra Firebase có được cấu hình chưa
     */
    public function isConfigured(): bool
    {
        return !empty($this->config['api_key'])
            && !empty($this->config['project_id']);
    }

    /**
     * Lấy Firebase config để truyền cho frontend
     * Chỉ trả về những field cần thiết cho JS SDK
     */
    public function getClientConfig(): array
    {
        return [
            'apiKey' => $this->config['api_key'],
            'authDomain' => $this->config['auth_domain'],
            'projectId' => $this->config['project_id'],
            'storageBucket' => $this->config['storage_bucket'],
            'messagingSenderId' => $this->config['messaging_sender_id'],
            'appId' => $this->config['app_id'],
            'measurementId' => $this->config['measurement_id'],
        ];
    }
}
