<?php

namespace App\Controllers;

use App\Models\UserAddress;
use App\Middleware\VerificationMiddleware;

/**
 * Controller quản lý địa chỉ giao hàng
 * 
 * CRUD địa chỉ với tích hợp HERE Maps Autocomplete.
 * 
 * @author UniMarket Team
 * @version 1.0.0
 */
class AddressController extends BaseController
{
    private UserAddress $addressModel;

    public function __construct()
    {
        $this->addressModel = new UserAddress();
    }

    /**
     * Hiển thị danh sách địa chỉ
     * 
     * GET /addresses
     */
    public function index(): void
    {
        VerificationMiddleware::requireVerified();

        $userId = $this->getUserId();
        if (!$userId) {
            $this->redirectToLogin();
            return;
        }

        $addresses = $this->addressModel->getByUserId($userId);

        $this->view('addresses/index', [
            'addresses' => $addresses,
            'pageTitle' => 'Địa chỉ giao hàng'
        ]);
    }

    /**
     * Form thêm địa chỉ mới
     * 
     * GET /addresses/create
     */
    public function create(): void
    {
        VerificationMiddleware::requireVerified();

        $userId = $this->getUserId();
        if (!$userId) {
            $this->redirectToLogin();
            return;
        }

        $this->view('addresses/create', [
            'pageTitle' => 'Thêm địa chỉ mới'
        ]);
    }

    /**
     * Lưu địa chỉ mới
     * 
     * POST /addresses/store
     */
    public function store(): void
    {
        VerificationMiddleware::requireVerified();

        $userId = $this->getUserId();
        if (!$userId) {
            $this->jsonError('Vui lòng đăng nhập', 401);
            return;
        }

        // Validate input
        $errors = $this->validateAddressInput($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: /addresses/create');
            exit;
        }

        // Chuẩn bị dữ liệu
        $data = [
            'user_id' => $userId,
            'label' => trim($_POST['label'] ?? 'Địa chỉ mới'),
            'recipient_name' => trim($_POST['recipient_name']),
            'phone_number' => trim($_POST['phone_number']),
            'province' => trim($_POST['province']),
            'district' => trim($_POST['district']),
            'ward' => trim($_POST['ward'] ?? ''),
            'street_address' => trim($_POST['street_address']),
            'full_address' => trim($_POST['full_address'] ?? ''),
            'latitude' => !empty($_POST['latitude']) ? (float) $_POST['latitude'] : null,
            'longitude' => !empty($_POST['longitude']) ? (float) $_POST['longitude'] : null,
            'here_place_id' => $_POST['here_place_id'] ?? null,
            'is_default' => !empty($_POST['is_default']) ? 1 : 0
        ];

        try {
            $addressId = $this->addressModel->create($data);

            $_SESSION['success'] = 'Đã thêm địa chỉ mới thành công!';

            // Nếu từ checkout, redirect về checkout
            if (!empty($_POST['redirect_to'])) {
                header('Location: ' . $_POST['redirect_to']);
            } else {
                header('Location: /addresses');
            }
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
            $_SESSION['old'] = $_POST;
            header('Location: /addresses/create');
            exit;
        }
    }

    /**
     * Form sửa địa chỉ
     * 
     * GET /addresses/edit?id=X
     */
    public function edit(): void
    {
        VerificationMiddleware::requireVerified();

        $userId = $this->getUserId();
        if (!$userId) {
            $this->redirectToLogin();
            return;
        }

        $addressId = (int) ($_GET['id'] ?? 0);
        $address = $this->addressModel->findById($addressId, $userId);

        if (!$address) {
            $_SESSION['error'] = 'Không tìm thấy địa chỉ';
            header('Location: /addresses');
            exit;
        }

        $this->view('addresses/edit', [
            'address' => $address,
            'pageTitle' => 'Sửa địa chỉ'
        ]);
    }

    /**
     * Cập nhật địa chỉ
     * 
     * POST /addresses/update
     */
    public function update(): void
    {
        VerificationMiddleware::requireVerified();

        $userId = $this->getUserId();
        if (!$userId) {
            $this->jsonError('Vui lòng đăng nhập', 401);
            return;
        }

        $addressId = (int) ($_POST['id'] ?? 0);

        // Validate input
        $errors = $this->validateAddressInput($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: /addresses/edit?id=$addressId");
            exit;
        }

        // Chuẩn bị dữ liệu
        $data = [
            'label' => trim($_POST['label'] ?? 'Địa chỉ'),
            'recipient_name' => trim($_POST['recipient_name']),
            'phone_number' => trim($_POST['phone_number']),
            'province' => trim($_POST['province']),
            'district' => trim($_POST['district']),
            'ward' => trim($_POST['ward'] ?? ''),
            'street_address' => trim($_POST['street_address']),
            'full_address' => trim($_POST['full_address'] ?? ''),
            'latitude' => !empty($_POST['latitude']) ? (float) $_POST['latitude'] : null,
            'longitude' => !empty($_POST['longitude']) ? (float) $_POST['longitude'] : null,
            'here_place_id' => $_POST['here_place_id'] ?? null,
            'is_default' => !empty($_POST['is_default']) ? 1 : 0
        ];

        try {
            $result = $this->addressModel->update($addressId, $data, $userId);

            if ($result) {
                $_SESSION['success'] = 'Đã cập nhật địa chỉ thành công!';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật địa chỉ';
            }

            header('Location: /addresses');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
            header("Location: /addresses/edit?id=$addressId");
            exit;
        }
    }

    /**
     * Xóa địa chỉ
     * 
     * POST /addresses/delete
     */
    public function delete(): void
    {
        VerificationMiddleware::requireVerified();

        $userId = $this->getUserId();
        if (!$userId) {
            $this->jsonError('Vui lòng đăng nhập', 401);
            return;
        }

        $addressId = (int) ($_POST['id'] ?? 0);

        try {
            $result = $this->addressModel->delete($addressId, $userId);

            if ($result) {
                $_SESSION['success'] = 'Đã xóa địa chỉ thành công!';
            } else {
                $_SESSION['error'] = 'Không thể xóa địa chỉ';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }

        header('Location: /addresses');
        exit;
    }

    /**
     * Đặt địa chỉ làm mặc định
     * 
     * POST /addresses/set-default
     */
    public function setDefault(): void
    {
        VerificationMiddleware::requireVerified();

        $userId = $this->getUserId();
        if (!$userId) {
            $this->jsonError('Vui lòng đăng nhập', 401);
            return;
        }

        $addressId = (int) ($_POST['id'] ?? 0);

        try {
            $result = $this->addressModel->setAsDefault($addressId, $userId);

            if ($result) {
                $_SESSION['success'] = 'Đã đặt địa chỉ mặc định!';
            } else {
                $_SESSION['error'] = 'Không thể đặt mặc định';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }

        header('Location: /addresses');
        exit;
    }

    /**
     * Validate input địa chỉ
     */
    private function validateAddressInput(array $data): array
    {
        $errors = [];

        if (empty($data['recipient_name'])) {
            $errors['recipient_name'] = 'Vui lòng nhập tên người nhận';
        }

        if (empty($data['phone_number'])) {
            $errors['phone_number'] = 'Vui lòng nhập số điện thoại';
        } elseif (!preg_match('/^(0|\+84)[0-9]{9,10}$/', preg_replace('/\s+/', '', $data['phone_number']))) {
            $errors['phone_number'] = 'Số điện thoại không hợp lệ';
        }

        if (empty($data['province'])) {
            $errors['province'] = 'Vui lòng chọn Tỉnh/Thành phố';
        }

        if (empty($data['district'])) {
            $errors['district'] = 'Vui lòng chọn Quận/Huyện';
        }

        if (empty($data['street_address'])) {
            $errors['street_address'] = 'Vui lòng nhập địa chỉ chi tiết';
        }

        return $errors;
    }

    /**
     * Helper: Lấy user ID từ session
     */
    private function getUserId(): ?int
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user']['id'] ?? null;
    }

    /**
     * Helper: Redirect to login
     */
    private function redirectToLogin(): void
    {
        $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục';
        header('Location: /login');
        exit;
    }

    /**
     * Helper: JSON error response
     */
    private function jsonError(string $message, int $code = 400): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $message]);
        exit;
    }
}
