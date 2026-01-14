/**
 * Chat Page Handler
 * Xử lý giao diện chat với Socket.IO
 */

(function() {
    'use strict';
    
    // ============ LẤY CONFIG TỪ HTML ============
    const chatContainer = document.getElementById('chat-container');
    if (!chatContainer) return; // Không phải trang chat
    
    const currentUserId = parseInt(chatContainer.dataset.userId) || null;
    const activePartnerId = parseInt(chatContainer.dataset.partnerId) || null;
    const partnerName = chatContainer.dataset.partnerName || '';
    
    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.querySelector('form[action="/chat/send"]');
    const messageInput = document.querySelector('input[name="content"]');
    const imageBtn = document.querySelector('.fa-image')?.closest('button');
    const fileBtn = document.querySelector('.fa-paperclip')?.closest('button');
    // ============ ONLINE STATUS ============
    let onlineUsersList = []; // Danh sách user đang online

    // ============ KHỞI TẠO ============
    document.addEventListener("DOMContentLoaded", function() {
        // Scroll to bottom
        scrollToBottom();
        
        // Set current chat user for Socket
        if (window.chatSocket && activePartnerId) {
            window.chatSocket.setCurrentChatUser(activePartnerId);
        }
        
        // Đăng ký callback nhận tin nhắn mới
        if (window.chatSocket) {
            window.chatSocket.onNewMessage(handleNewMessage);
        }
        
        // Setup file upload buttons
        setupFileUpload();
        
        // Setup emoji picker
        setupEmojiPicker();
        
        // Setup online status listener
        setupOnlineStatus();
    });
    
    // ============ XỬ LÝ GỬI TIN NHẮN ============
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const content = messageInput.value.trim();
            if (!content) return;
            
            // Gửi qua Socket.IO
            if (window.chatSocket && window.chatSocket.isConnected) {
                window.chatSocket.sendMessage(activePartnerId, content);
                
                // Optimistic update - thêm vào UI ngay
                appendMessage({
                    content: content,
                    sender_id: currentUserId,
                    created_at: new Date().toISOString()
                }, true);
                
                // Clear input
                messageInput.value = '';
                messageInput.focus();
                
                // Báo ngừng typing
                window.chatSocket.sendTyping(activePartnerId, false);
            } else {
                // Fallback: Gửi qua HTTP
                this.submit();
            }
        });
        
        // ============ TYPING INDICATOR ============
        let typingTimer = null;
        
        messageInput.addEventListener('input', function() {
            if (!window.chatSocket || !window.chatSocket.isConnected) return;
            
            window.chatSocket.sendTyping(activePartnerId, true);
            
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                window.chatSocket.sendTyping(activePartnerId, false);
            }, 2000);
        });
        
        // Enter to send, Shift+Enter for new line
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                messageForm.dispatchEvent(new Event('submit'));
            }
        });
    }
    
    // ============ FILE UPLOAD ============
    function setupFileUpload() {
        // Tạo hidden input cho file
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.style.display = 'none';
        fileInput.accept = 'image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt';
        document.body.appendChild(fileInput);
        
        // Image button - chỉ cho phép ảnh
        if (imageBtn) {
            imageBtn.addEventListener('click', () => {
                fileInput.accept = 'image/*';
                fileInput.click();
            });
        }
        
        // File button - cho phép tất cả file
        if (fileBtn) {
            fileBtn.addEventListener('click', () => {
                fileInput.accept = 'image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt';
                fileInput.click();
            });
        }
        
        // Handle file selection
        fileInput.addEventListener('change', async function() {
            const file = this.files[0];
            if (!file) return;
            
            // Validate size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('File quá lớn! Tối đa 10MB');
                return;
            }
            
            try {
                // Show loading state
                if (messageInput) {
                    messageInput.placeholder = 'Đang tải file...';
                    messageInput.disabled = true;
                }
                
                // Upload và gửi
                await window.chatSocket.sendFile(activePartnerId, file);
                
                // Optimistic update với attachment
                const isImage = file.type.startsWith('image/');
                appendMessage({
                    content: '',
                    sender_id: currentUserId,
                    created_at: new Date().toISOString(),
                    has_attachment: true,
                    attachment: {
                        name: file.name,
                        path: URL.createObjectURL(file), // Temporary URL
                        type: file.type,
                        is_image: isImage
                    }
                }, true);
                
            } catch (error) {
                alert('Lỗi tải file: ' + error.message);
            } finally {
                // Reset
                this.value = '';
                if (messageInput) {
                    messageInput.placeholder = 'Nhập tin nhắn...';
                    messageInput.disabled = false;
                }
            }
        });
    }
    
    // ============ EMOJI PICKER ============
    function setupEmojiPicker() {
        const emojiBtn = document.getElementById('emoji-btn');
        const emojiPicker = document.getElementById('emoji-picker');
        
        if (!emojiBtn || !emojiPicker) return;
        
        // Toggle emoji picker
        emojiBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            emojiPicker.classList.toggle('hidden');
        });
        
        // Click emoji to insert
        emojiPicker.querySelectorAll('.emoji-item').forEach(btn => {
            btn.addEventListener('click', () => {
                const emoji = btn.textContent;
                if (messageInput) {
                    // Insert emoji at cursor position
                    const start = messageInput.selectionStart;
                    const end = messageInput.selectionEnd;
                    const text = messageInput.value;
                    messageInput.value = text.substring(0, start) + emoji + text.substring(end);
                    messageInput.focus();
                    messageInput.selectionStart = messageInput.selectionEnd = start + emoji.length;
                }
                emojiPicker.classList.add('hidden');
            });
        });
        // Close picker when clicking outside
        document.addEventListener('click', (e) => {
            if (!emojiPicker.contains(e.target) && e.target !== emojiBtn) {
                emojiPicker.classList.add('hidden');
            }
        });
    }
    
    // ============ NHẬN TIN NHẮN MỚI ============
    function handleNewMessage(message, type) {
        console.log('[ChatPage] New message:', message, type);
        
        if (message.sender_id == activePartnerId || message.receiver_id == activePartnerId) {
            if (type === 'sent') return; // Đã append rồi
            appendMessage(message, false);
        }
    }
    
    // ============ THÊM TIN NHẮN VÀO UI ============
    function appendMessage(message, isMe) {
        if (!messagesContainer) return;
        
        // Xóa empty state nếu có
        const emptyState = messagesContainer.querySelector('.empty-chat-state');
        if (emptyState) emptyState.remove();
        
        const time = new Date(message.created_at);
        const timeStr = time.getHours().toString().padStart(2, '0') + ':' + 
                        time.getMinutes().toString().padStart(2, '0');
        
        const partnerAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(partnerName)}&background=random&size=64`;
        
        // Build message content
        let contentHtml = '';
        
        if (message.attachment) {
            const att = message.attachment;
            if (att.is_image) {
                // Hiển thị ảnh
                contentHtml = `
                    <a href="${att.path}" target="_blank" class="block">
                        <img src="${att.path}" alt="${escapeHtml(att.name)}" 
                             class="max-w-full max-h-[200px] rounded-lg cursor-pointer hover:opacity-90">
                    </a>
                `;
            } else {
                // Hiển thị file download link
                contentHtml = `
                    <a href="${att.path}" download="${escapeHtml(att.name)}" 
                       class="flex items-center gap-2 p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        <i class="fa-solid fa-file text-blue-500"></i>
                        <span class="text-sm truncate max-w-[150px]">${escapeHtml(att.name)}</span>
                        <i class="fa-solid fa-download text-gray-400 text-xs"></i>
                    </a>
                `;
            }
            
            // Add caption if exists
            if (message.content && message.content !== '[File đính kèm]') {
                contentHtml += `<p class="mt-2">${escapeHtml(message.content)}</p>`;
            }
        } else {
            contentHtml = escapeHtml(message.content);
        }
        
        const messageEl = document.createElement('div');
        messageEl.className = `flex w-full ${isMe ? 'justify-end' : 'justify-start'} group animate-fade-in-up`;
        messageEl.innerHTML = `
            <div class="flex max-w-[70%] ${isMe ? 'flex-row-reverse' : 'flex-row'} items-end gap-2">
                ${!isMe ? `<img src="${partnerAvatar}" class="w-8 h-8 rounded-full object-cover shadow-sm mb-1 flex-shrink-0">` : ''}
                <div class="flex flex-col ${isMe ? 'items-end' : 'items-start'}">
                    <div class="relative px-4 py-2.5 shadow-sm text-[15px] leading-relaxed break-words font-normal
                        ${isMe 
                            ? 'bg-[#2C67C8] text-white rounded-2xl rounded-tr-sm' 
                            : 'bg-white border border-gray-200 text-gray-800 rounded-2xl rounded-tl-sm'
                        }">
                        ${contentHtml}
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 px-1">${timeStr}</span>
                </div>
            </div>
        `;
        
        messagesContainer.appendChild(messageEl);
        scrollToBottom();
    }
    
// ============ ONLINE STATUS ============
    // ============ ONLINE STATUS ============
    function setupOnlineStatus() {
        // Init ngay nếu socket đã có data
        if (window.chatSocket && typeof window.chatSocket.getOnlineUsers === 'function') {
            const currentOnlineParams = window.chatSocket.getOnlineUsers();
            if (currentOnlineParams && currentOnlineParams.length > 0) {
                onlineUsersList = currentOnlineParams;
                updatePartnerOnlineStatus();
            }
        }

        // Fallback: Hiển thị từ last_seen ngay lập tức (không chờ socket)
        updatePartnerOnlineStatus();
        
        // Nếu có socket, lắng nghe realtime updates
        if (window.chatSocket && window.chatSocket.socket) {
            const socket = window.chatSocket.socket;
            
            // Lắng nghe danh sách user online
            socket.on('online_users', (userIds) => {
                console.log('[OnlineStatus] Online users:', userIds);
                onlineUsersList = userIds.map(id => id.toString());
                updatePartnerOnlineStatus();
            });
            
            // Lắng nghe khi user online
            socket.on('user_online', (data) => {
                console.log('[OnlineStatus] User online:', data);
                if (data.user_id && !onlineUsersList.includes(data.user_id.toString())) {
                    onlineUsersList.push(data.user_id.toString());
                }
                if (data.user_id == activePartnerId) {
                    updatePartnerOnlineStatus();
                }
            });
            
            // Lắng nghe khi user offline
            socket.on('user_offline', (data) => {
                console.log('[OnlineStatus] User offline:', data);
                onlineUsersList = onlineUsersList.filter(id => id != data.user_id?.toString());
                if (data.user_id == activePartnerId) {
                    updatePartnerOnlineStatus(data.last_seen);
                }
            });
        }
    }

/**
 * Cập nhật UI hiển thị trạng thái online của partner
 */
function updatePartnerOnlineStatus(lastSeen = null) {
    if (!activePartnerId) return;
    
    const isOnline = onlineUsersList.includes(activePartnerId.toString());
    
    // Tìm các element cần update
    const statusDot = document.querySelector('.status-dot');
    const statusText = document.querySelector('.status-text');
    
    if (!statusDot || !statusText) return;
    
    if (isOnline) {
        // Online: chấm xanh + text "Đang hoạt động"
        statusDot.className = 'status-dot absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white';
        statusText.className = 'status-text text-xs text-green-600 font-medium flex items-center gap-1';
        statusText.textContent = 'Đang hoạt động';
    } else {
        // Offline: chấm xám + text "X phút/giờ trước"
        statusDot.className = 'status-dot absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-gray-400 ring-2 ring-white';
        statusText.className = 'status-text text-xs text-gray-500 font-medium flex items-center gap-1';
        
        // Lấy last_seen từ tham số hoặc data attribute
        let lastSeenValue = lastSeen;
        if (!lastSeenValue) {
            const lastSeenData = document.querySelector('[data-partner-last-seen]')?.dataset.partnerLastSeen;
            lastSeenValue = lastSeenData;
        }
        
        if (lastSeenValue && lastSeenValue !== '') {
            statusText.textContent = formatLastSeen(lastSeenValue);
        } else {
            statusText.textContent = 'Không hoạt động';
        }
    }
}

    /**
     * Format thời gian "X phút/giờ/ngày trước"
     */
    function formatLastSeen(dateString) {
        if (!dateString) return 'Không hoạt động';
        
        const lastSeen = new Date(dateString);
        const now = new Date();
        const diffMs = now - lastSeen;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);
        
        if (diffMins < 1) {
            return 'Vừa mới truy cập';
        } else if (diffMins < 60) {
            return `Hoạt động ${diffMins} phút trước`;
        } else if (diffHours < 24) {
            return `Hoạt động ${diffHours} giờ trước`;
        } else if (diffDays < 7) {
            return `Hoạt động ${diffDays} ngày trước`;
        } else {
            return `Hoạt động ${lastSeen.toLocaleDateString('vi-VN')}`;
        }
    }

    // ============ HELPERS ============
    function scrollToBottom() {
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
})();
