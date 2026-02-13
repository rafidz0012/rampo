<x-layouts.dashboard>
    <x-slot name="header">Chat Room</x-slot>
    <x-slot name="title">Chat</x-slot>

    <div class="flex h-[calc(100vh-12rem)] bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden" 
         x-data="chatApp({{ auth()->id() }})">
        
        <!-- Sidebar -->
        <div class="w-1/3 border-r border-gray-200 dark:border-gray-700 flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <input type="text" placeholder="Search users..." class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-gray-900 dark:text-white" x-model="searchUser">
            </div>
            <div class="flex-1 overflow-y-auto">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <template x-for="user in filteredUsers" :key="user.id">
                        <li @click="selectUser(user)" 
                            :class="{'bg-blue-50 dark:bg-gray-700': activeUser && activeUser.id === user.id}"
                            class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold" x-text="user.name.substring(0,2).toUpperCase()"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="user.name"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate w-32" x-text="user.email"></p>
                                </div>
                            </div>
                            <!-- Unread Badge -->
                            <span x-show="unreadCounts[user.id] > 0" 
                                  class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full" 
                                  x-text="unreadCounts[user.id]"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col bg-gray-50 dark:bg-gray-900">
            <template x-if="activeUser">
                <div class="flex-1 flex flex-col h-full">
                    <!-- Header -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs font-bold" x-text="activeUser.name.substring(0,2).toUpperCase()"></div>
                            <h3 class="font-medium text-gray-900 dark:text-white" x-text="activeUser.name"></h3>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
                        <template x-for="msg in messages" :key="msg.id">
                            <div class="flex" :class="msg.sender_id === authId ? 'justify-end' : 'justify-start'">
                                <div class="max-w-[70%] rounded-lg px-4 py-2 shadow-sm text-sm"
                                     :class="msg.sender_id === authId ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-bl-none'">
                                    
                                    <!-- File Display -->
                                    <template x-if="msg.file_path">
                                        <div class="mb-2">
                                            <!-- Image -->
                                            <template x-if="msg.file_type.startsWith('image/')">
                                                <img :src="msg.file_url" class="rounded-lg max-h-60 w-auto cursor-pointer" @click="window.open(msg.file_url, '_blank')">
                                            </template>
                                            
                                            <!-- Video -->
                                            <template x-if="msg.file_type.startsWith('video/')">
                                                <video :src="msg.file_url" controls class="rounded-lg max-h-60 w-full"></video>
                                            </template>
                                            
                                            <!-- Other Files -->
                                            <template x-if="!msg.file_type.startsWith('image/') && !msg.file_type.startsWith('video/')">
                                                <a :href="msg.file_url" target="_blank" class="flex items-center gap-2 p-2 rounded bg-black/10 dark:bg-white/10 hover:bg-black/20 dark:hover:bg-white/20 transition">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    <span class="truncate" x-text="msg.file_name"></span>
                                                </a>
                                            </template>
                                        </div>
                                    </template>

                                    <p x-show="msg.message" x-text="msg.message"></p>
                                    <span class="text-[10px] opacity-70 mt-1 block text-right" 
                                          x-text="formatTime(msg.created_at)"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Input Area -->
                    <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                        <!-- File Preview -->
                        <template x-if="selectedFile">
                            <div class="mb-4 flex items-center justify-between p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center gap-2 min-w-0">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.415a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span class="text-xs truncate text-blue-700 dark:text-blue-300" x-text="selectedFile.name"></span>
                                </div>
                                <button @click="clearFile" class="text-blue-500 hover:text-blue-700 p-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                </button>
                            </div>
                        </template>

                        <form @submit.prevent="sendMessage" class="flex gap-2">
                            <!-- Hidden File Input -->
                            <input type="file" x-ref="fileInput" @change="onFileChange" class="hidden">
                            
                            <!-- Attachment Button -->
                            <button type="button" @click="$refs.fileInput.click()" class="p-2 text-gray-500 hover:text-blue-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.415a6 6 0 108.486 8.486L20.5 13"/></svg>
                            </button>

                            <input type="text" x-model="newMessage" 
                                   class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="Type a message...">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50"
                                    :disabled="!newMessage.trim() && !selectedFile">
                                Send
                            </button>
                        </form>
                    </div>
                </div>
            </template>
            
            <template x-if="!activeUser">
                <div class="flex-1 flex items-center justify-center text-gray-400 flex-col gap-4">
                    <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p>pilih user unutk ngobrol</p>
                </div>
            </template>
        </div>
    </div>

    <script>
        function chatApp(authId) {
            return {
                authId: authId,
                users: {{ \Illuminate\Support\Js::from($users) }},
                activeUser: null,
                messages: [],
                newMessage: '',
                selectedFile: null,
                searchUser: '',
                unreadCounts: {},
                pollInterval: null,

                get filteredUsers() {
                    return this.users.filter(u => u.name.toLowerCase().includes(this.searchUser.toLowerCase()));
                },

                init() {
                    this.pollUnread();
                    setInterval(() => this.pollUnread(), 5000); // Check unread counts globally
                },

                selectUser(user) {
                    this.activeUser = user;
                    this.fetchMessages();
                    this.scrollToBottom();
                    
                    // Start polling active chat
                    if (this.pollInterval) clearInterval(this.pollInterval);
                    this.pollInterval = setInterval(() => this.fetchMessages(false), 3000); // Poll active chat every 3s
                },

                onFileChange(e) {
                    this.selectedFile = e.target.files[0];
                },

                clearFile() {
                    this.selectedFile = null;
                    if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                },

                async fetchMessages(scroll = true) {
                    if (!this.activeUser) return;
                    
                    try {
                        const response = await fetch(`/chat/${this.activeUser.id}`);
                        const data = await response.json();
                        this.messages = data.messages;
                        if (scroll) this.scrollToBottom();
                        
                        // Reset unread count for this user
                        this.unreadCounts[this.activeUser.id] = 0;
                    } catch (error) {
                        console.error('Error fetching messages:', error);
                    }
                },

                async sendMessage() {
                    if ((!this.newMessage.trim() && !this.selectedFile) || !this.activeUser) return;
                    
                    const messageText = this.newMessage;
                    const fileToSend = this.selectedFile;
                    
                    this.newMessage = ''; 
                    this.clearFile();

                    // Create FormData
                    const formData = new FormData();
                    formData.append('receiver_id', this.activeUser.id);
                    if (messageText) formData.append('message', messageText);
                    if (fileToSend) formData.append('file', fileToSend);

                    // Optimistic update (Simple)
                    this.messages.push({
                        id: 'temp-' + Date.now(),
                        sender_id: this.authId,
                        message: messageText,
                        file_path: fileToSend ? URL.createObjectURL(fileToSend) : null,
                        file_name: fileToSend ? fileToSend.name : null,
                        file_type: fileToSend ? fileToSend.type : null,
                        created_at: new Date().toISOString(),
                    });
                    this.scrollToBottom();

                    try {
                        const response = await fetch('/chat', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: formData
                        });
                        
                        this.fetchMessages(false);
                    } catch (error) {
                        console.error('Error sending message:', error);
                    }
                },

                async pollUnread() {
                    try {
                        const response = await fetch('/chat-unread');
                        this.unreadCounts = await response.json();
                    } catch (error) {
                        console.error('Error polling unread:', error);
                    }
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = document.getElementById('chat-messages');
                        if (container) container.scrollTop = container.scrollHeight;
                    });
                },

                formatTime(isoString) {
                    const date = new Date(isoString);
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                }
            }
        }
    </script>
</x-layouts.dashboard>

