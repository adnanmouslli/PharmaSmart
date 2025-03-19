class ChatHandler {
    constructor() {
        this.socket = null;
        this.messageQueue = [];
        this.isProcessingQueue = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectDelay = 2000;
        
        this.initialize();
    }

    initialize() {
        this.connectWebSocket();
        this.setupEventListeners();
    }

    connectWebSocket() {
        try {
            this.socket = new WebSocket('ws://localhost:8765');
            
            this.socket.onopen = () => {
                this.updateConnectionStatus('متصل', true);
                this.reconnectAttempts = 0;
            };
            
            this.socket.onclose = () => {
                this.updateConnectionStatus('غير متصل', false);
                this.handleReconnection();
            };
            
            this.socket.onerror = (error) => {
                console.error('WebSocket error:', error);
                this.updateConnectionStatus('خطأ في الاتصال', false);
            };
            
            this.socket.onmessage = (event) => {
                this.handleMessage(JSON.parse(event.data));
            };
        } catch (error) {
            console.error('Error connecting to WebSocket:', error);
            this.updateConnectionStatus('فشل الاتصال', false);
        }
    }

    setupEventListeners() {
        const form = document.getElementById('chatForm');
        const input = document.getElementById('userInput');
        
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const message = input.value.trim();
            if (message) {
                this.sendMessage(message);
                input.value = '';
                input.style.height = 'auto';
            }
        });

        // معالجة مفتاح Enter وضبط ارتفاع حقل الإدخال
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
        });

        // ضبط ارتفاع حقل الإدخال تلقائياً
        input.addEventListener('input', () => {
            input.style.height = 'auto';
            input.style.height = `${input.scrollHeight}px`;
        });
    }

    sendMessage(message) {
        if (this.socket.readyState === WebSocket.OPEN) {
            // إضافة الرسالة الظاهرة للمستخدم في المحادثة
            this.addMessageToChat('user', message);
            
            let messageToSend;
            const selectedOption = this.findSelectedOption(message);
            
            if (selectedOption) {
                // إذا كان الاختيار من القائمة، نرسل المعرف
                messageToSend = selectedOption.id;
            } else {
                // إذا كان نص عادي، نرسله كما هو
                messageToSend = message;
            }
            
            // إرسال الرسالة للسيرفر
            console.log('Sending to server:', { input: messageToSend }); // للتأكد من المعرف المرسل
            this.socket.send(JSON.stringify({ input: messageToSend }));
            
            // مسح الخيارات بعد الإرسال
            this.updateOptions([]);
        } else {
            this.showNotification('لا يمكن إرسال الرسالة. الرجاء التحقق من اتصالك.', 'error');
        }
    }

    findSelectedOption(text) {
        const optionsArea = document.getElementById('optionsArea');
        if (!optionsArea) return null;

        const buttons = optionsArea.getElementsByClassName('chat-option');
        for (const button of buttons) {
            if (button.textContent === text && button.dataset.optionId) {
                return {
                    id: button.dataset.optionId,
                    text: button.textContent
                };
            }
        }
        return null;
    }


    findOptionById(text) {
        const optionsArea = document.getElementById('optionsArea');
        const options = Array.from(optionsArea.getElementsByTagName('button'));
        return options.find(option => option.textContent === text)?.dataset?.option 
               ? JSON.parse(option.dataset.option) 
               : null;
    }
    
    async processMessageQueue() {
        if (this.messageQueue.length === 0) {
            this.isProcessingQueue = false;
            return;
        }

        this.isProcessingQueue = true;
        const data = this.messageQueue.shift();

        if (!data.message || data.message.trim() === '') {
            console.warn('Empty message received:', data);
            this.processMessageQueue();
            return;
        }

        this.showTypingIndicator();
        await new Promise(resolve => setTimeout(resolve, Math.random() * 1000 + 500));
        this.hideTypingIndicator();
        
        switch (data.type) {
            case 'node':
            case 'start':
                this.addMessageToChat('bot', data.message);
                if (data.options && Array.isArray(data.options)) {
                    this.updateOptions(data.options);
                }
                break;
            case 'error':
                this.addMessageToChat('bot', data.message, 'error');
                if (data.options && Array.isArray(data.options)) {
                    this.updateOptions(data.options);
                }
                break;
            case 'warning':
                this.addMessageToChat('bot', data.message, 'warning');
                break;
            case 'timeout':
                this.addMessageToChat('bot', data.message, 'timeout');
                break;
            default:
                console.warn('Unknown message type:', data.type);
        }

        setTimeout(() => this.processMessageQueue(), 300);
    }

    addMessageToChat(sender, content, type = 'normal') {
        if (!content || content.trim() === '') {
            console.warn('Attempted to add empty message to chat');
            return;
        }

        const messagesContainer = document.getElementById('chatMessages');
        const template = document.getElementById('messageTemplate');
        const messageWrapper = template.content.cloneNode(true);
        
        const messageDiv = messageWrapper.querySelector('.message');
        const relevantMessageDiv = messageWrapper.querySelector(sender === 'user' ? '.user-message' : '.bot-message');
        const otherMessageDiv = messageWrapper.querySelector(sender === 'user' ? '.bot-message' : '.user-message');
        
        otherMessageDiv.remove();
        
        const messageContent = relevantMessageDiv.querySelector('p');
        messageContent.textContent = content;
        messageContent.dir = 'rtl';
        messageContent.lang = 'ar';
        
        const messageBubble = relevantMessageDiv.querySelector('.message-bubble');
        messageBubble.dir = 'rtl';

        // تطبيق الأنماط حسب نوع الرسالة
        switch (type) {
            case 'error':
                messageContent.classList.add('text-red-500');
                messageBubble.classList.add('border-red-200', 'dark:border-red-800', 'bg-red-50', 'dark:bg-red-900/10');
                break;
            case 'warning':
                messageContent.classList.add('text-yellow-500');
                messageBubble.classList.add('border-yellow-200', 'dark:border-yellow-800', 'bg-yellow-50', 'dark:bg-yellow-900/10');
                break;
            default:
                if (sender === 'user') {
                    messageContent.classList.add('text-white');
                    messageBubble.classList.add('bg-gradient-to-l', 'from-teal-500', 'to-teal-600');
                } else {
                    messageContent.classList.add('text-gray-800', 'dark:text-gray-200');
                }
        }

        if (sender === 'user') {
            relevantMessageDiv.classList.add('flex-row-reverse');
            messageBubble.classList.add('ml-auto');
        } else {
            messageBubble.classList.add('mr-auto');
        }

        messagesContainer.appendChild(messageWrapper);
        
        requestAnimationFrame(() => {
            messageDiv.classList.add('opacity-100');
            this.scrollToBottom();
        });
    }

    handleMessage(data) {
        console.log('Received from server:', data); // للتأكد من الرد
        if (!data || typeof data !== 'object') {
            console.error('Invalid message data received:', data);
            return;
        }

        this.messageQueue.push(data);
        if (!this.isProcessingQueue) {
            this.processMessageQueue();
        }
    }

    updateOptions(options) {
        const optionsArea = document.getElementById('optionsArea');
        optionsArea.innerHTML = '';
        
        if (!options || !Array.isArray(options) || options.length === 0) return;
        
        const optionsGrid = document.createElement('div');
        optionsGrid.className = 'grid grid-cols-1 md:grid-cols-2 gap-3';
        optionsGrid.dir = 'rtl';
        
        options.forEach((option, index) => {
            if (!option || !option.id || !option.text) return;

            const button = document.createElement('button');
            button.className = 'chat-option';
            button.textContent = option.text;
            
            // تخزين معرف الخيار في data attribute
            button.dataset.optionId = option.id;
            
            button.dir = 'rtl';
            button.lang = 'ar';
            
            // تأثيرات الظهور
            button.style.opacity = '0';
            button.style.transform = 'translateY(10px)';
            
            button.onclick = () => {
                this.sendMessage(option.text);
            };
            
            optionsGrid.appendChild(button);
            
            setTimeout(() => {
                button.style.transition = 'all 0.3s ease-out';
                button.style.opacity = '1';
                button.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        optionsArea.appendChild(optionsGrid);
    }

    showTypingIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'typingIndicator';
        indicator.className = 'typing-indicator mb-4 text-gray-500 dark:text-gray-400';
        
        for (let i = 0; i < 3; i++) {
            const dot = document.createElement('span');
            dot.style.animationDelay = `${i * 0.2}s`;
            indicator.appendChild(dot);
        }
        
        const messagesContainer = document.getElementById('chatMessages');
        messagesContainer.appendChild(indicator);
        this.scrollToBottom();
    }

    hideTypingIndicator() {
        const indicator = document.getElementById('typingIndicator');
        if (indicator) {
            indicator.remove();
        }
    }

    scrollToBottom() {
        const messagesContainer = document.getElementById('chatMessages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    updateConnectionStatus(status, isConnected) {
        const statusElement = document.getElementById('connectionStatus');
        const statusDot = statusElement.previousElementSibling;
        
        statusElement.textContent = status;
        statusDot.classList.remove('bg-green-400', 'bg-red-400', 'bg-yellow-400');
        statusDot.classList.add(isConnected ? 'bg-green-400' : 'bg-red-400');
        statusDot.classList.toggle('animate-pulse', isConnected);
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-y-[-100%] opacity-0`;
        
        switch (type) {
            case 'error':
                notification.classList.add('bg-red-500', 'text-white');
                break;
            case 'warning':
                notification.classList.add('bg-yellow-500', 'text-white');
                break;
            default:
                notification.classList.add('bg-teal-500', 'text-white');
        }
        
        notification.textContent = message;
        notification.dir = 'rtl';
        notification.lang = 'ar';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-y-[-100%]', 'opacity-0');
        }, 100);
        
        setTimeout(() => {
            notification.classList.add('translate-y-[-100%]', 'opacity-0');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    async handleReconnection() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            this.updateConnectionStatus(`محاولة إعادة الاتصال... (${this.reconnectAttempts}/${this.maxReconnectAttempts})`, false);
            
            await new Promise(resolve => setTimeout(resolve, this.reconnectDelay));
            this.connectWebSocket();
        } else {
            this.updateConnectionStatus('فشل الاتصال. يرجى تحديث الصفحة.', false);
            this.showNotification('فشل الاتصال بالخادم. يرجى تحديث الصفحة وإعادة المحاولة.', 'error');
        }
    }
}

// تهيئة معالج الدردشة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    window.chatHandler = new ChatHandler();
});