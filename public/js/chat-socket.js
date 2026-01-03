/**
 * Zoldify Chat Socket Client
 * Kết nối Socket.IO để nhắn tin real-time
 */

class ChatSocket {
    constructor() {
        this.socket = null;
        this.currentUserId = null;
        this.currentChatUserId = null; // User đang chat cùng
        this.isConnected = false;
        this.messageCallbacks = [];
        this.typingTimeout = null;
    }

    /**
     * Khởi tạo kết nối Socket.IO
     * @param {number} userId - ID của user hiện tại
     */
    connect(userId) {
        if (!userId) {
            console.error('[ChatSocket] userId is required');
            return;
        }

        this.currentUserId = userId;

        // URL của Socket.IO server
        // Development: localhost:3001
        // Production: https://zoldify.com:3001 hoặc subdomain
        const socketUrl = window.SOCKET_URL || 'http://localhost:3001';

        // Khởi tạo kết nối
        this.socket = io(socketUrl, {
            transports: ['websocket', 'polling'],
            reconnection: true,
            reconnectionAttempts: 5,
            reconnectionDelay: 1000
        });

        // Đăng ký các event listeners
        this._setupListeners();
    }

    /**
     * Setup các event listeners
     */
    _setupListeners() {
        // Kết nối thành công
        this.socket.on('connect', () => {
            console.log('[ChatSocket] Connected to server');
            this.isConnected = true;

            // Báo cho server biết user đang online
            this.socket.emit('user_online', this.currentUserId);
        });

        // Mất kết nối
        this.socket.on('disconnect', (reason) => {
            console.log('[ChatSocket] Disconnected:', reason);
            this.isConnected = false;
        });

        // Lỗi kết nối
        this.socket.on('connect_error', (error) => {
            console.error('[ChatSocket] Connection error:', error);
        });

        // Nhận tin nhắn mới
        this.socket.on('new_message', (message) => {
            console.log('[ChatSocket] New message received:', message);
            this._handleNewMessage(message);
        });

        // Xác nhận tin nhắn đã gửi
        this.socket.on('message_sent', (message) => {
            console.log('[ChatSocket] Message sent successfully:', message);
            this._handleMessageSent(message);
        });

        // Danh sách user online
        this.socket.on('online_users', (userIds) => {
            console.log('[ChatSocket] Online users:', userIds);
            this._updateOnlineStatus(userIds);
        });

        // User đang nhập
        this.socket.on('user_typing', (data) => {
            this._handleTyping(data);
        });

        // Lỗi từ server
        this.socket.on('error', (error) => {
            console.error('[ChatSocket] Server error:', error);
            alert('Lỗi: ' + error.message);
        });
    }

    /**
     * Gửi tin nhắn (có thể kèm attachment)
     * @param {number} receiverId - ID người nhận
     * @param {string} content - Nội dung tin nhắn
     * @param {Object} attachment - File đính kèm (optional)
     */
    sendMessage(receiverId, content, attachment = null) {
        if (!this.isConnected) {
            console.error('[ChatSocket] Not connected');
            return false;
        }

        if (!content && !attachment) {
            return false;
        }

        const data = {
            sender_id: this.currentUserId,
            receiver_id: receiverId,
            content: content ? content.trim() : null
        };

        if (attachment) {
            data.attachment = attachment;
        }

        this.socket.emit('send_message', data);
        return true;
    }

    /**
     * Upload file và gửi kèm tin nhắn
     * @param {number} receiverId - ID người nhận
     * @param {File} file - File object từ input
     * @param {string} caption - Caption cho file (optional)
     * @returns {Promise}
     */
    async sendFile(receiverId, file, caption = '') {
        if (!this.isConnected) {
            throw new Error('Not connected');
        }

        // 1. Upload file lên server
        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch('/api/chat/upload.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error || 'Upload failed');
            }

            const result = await response.json();
            
            // 2. Gửi tin nhắn kèm attachment qua Socket
            return this.sendMessage(receiverId, caption, result.file);

        } catch (error) {
            console.error('[ChatSocket] File upload failed:', error);
            throw error;
        }
    }

    /**
     * Đánh dấu tin nhắn đã đọc
     * @param {Array} messageIds - Mảng ID tin nhắn
     */
    markAsRead(messageIds) {
        if (!this.isConnected || !messageIds.length) return;

        this.socket.emit('mark_read', {
            message_ids: messageIds,
            reader_id: this.currentUserId
        });
    }

    /**
     * Báo đang nhập
     * @param {number} receiverId - ID người nhận
     * @param {boolean} isTyping - Đang nhập hay không
     */
    sendTyping(receiverId, isTyping) {
        if (!this.isConnected) return;

        this.socket.emit('typing', {
            sender_id: this.currentUserId,
            receiver_id: receiverId,
            is_typing: isTyping
        });
    }

    /**
     * Đăng ký callback khi có tin nhắn mới
     * @param {Function} callback 
     */
    onNewMessage(callback) {
        this.messageCallbacks.push(callback);
    }

    /**
     * Xử lý tin nhắn mới nhận được
     */
    _handleNewMessage(message) {
        // Gọi tất cả callbacks đã đăng ký
        this.messageCallbacks.forEach(cb => cb(message, 'received'));

        // Nếu đang ở trang chat với người gửi -> đánh dấu đã đọc
        if (this.currentChatUserId == message.sender_id) {
            this.markAsRead([message.id]);
        }

        // Phát âm thanh thông báo
        this._playNotificationSound();
    }

    /**
     * Xử lý tin nhắn đã gửi thành công
     */
    _handleMessageSent(message) {
        this.messageCallbacks.forEach(cb => cb(message, 'sent'));
    }

    /**
     * Cập nhật trạng thái online
     */
    _updateOnlineStatus(onlineUserIds) {
        // Cập nhật UI hiển thị trạng thái online
        document.querySelectorAll('[data-user-id]').forEach(el => {
            const userId = el.getAttribute('data-user-id');
            const statusDot = el.querySelector('.online-status');
            
            if (statusDot) {
                if (onlineUserIds.includes(userId)) {
                    statusDot.classList.add('online');
                    statusDot.classList.remove('offline');
                } else {
                    statusDot.classList.add('offline');
                    statusDot.classList.remove('online');
                }
            }
        });
    }

    /**
     * Xử lý sự kiện đang nhập
     */
    _handleTyping(data) {
        const typingIndicator = document.getElementById('typing-indicator');
        if (!typingIndicator) return;

        if (data.is_typing && data.sender_id == this.currentChatUserId) {
            typingIndicator.style.display = 'block';
            typingIndicator.textContent = 'Đang nhập...';
        } else {
            typingIndicator.style.display = 'none';
        }
    }

    /**
     * Phát âm thanh thông báo
     * Chỉ phát khi:
     * - Tab không active, HOẶC
     * - Không đang chat với người gửi
     */
    _playNotificationSound() {
        // Nếu đang ở tab chat với người gửi -> không phát
        if (document.hasFocus() && document.querySelector('#chat-container')) {
            return;
        }
        
        try {
            // Preload audio để phát nhanh hơn
            if (!this._notificationAudio) {
                this._notificationAudio = new Audio('/sounds/notification.mp3');
                this._notificationAudio.volume = 0.5;
            }
            
            // Reset và phát
            this._notificationAudio.currentTime = 0;
            this._notificationAudio.play().catch(() => {
                // Browser chặn autoplay - bỏ qua
            });
        } catch (e) {
            console.warn('[ChatSocket] Cannot play notification sound');
        }
    }

    /**
     * Set user đang chat cùng
     */
    setCurrentChatUser(userId) {
        this.currentChatUserId = userId;
    }

    /**
     * Ngắt kết nối
     */
    disconnect() {
        if (this.socket) {
            this.socket.disconnect();
            this.isConnected = false;
        }
    }
}

// Tạo instance global
window.chatSocket = new ChatSocket();