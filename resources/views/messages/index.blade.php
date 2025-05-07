<x-app-layout>
    <!-- Move this to the top, before any content -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functions
            window.showUserSelectionModal = async function() {
                try {
                    const response = await fetch('{{ route("users.list") }}');
                    if (!response.ok) throw new Error('Failed to fetch users');
                    
                    const users = await response.json();
                    const usersList = document.getElementById('available-users-list');
                    
                    if (users.length === 0) {
                        usersList.innerHTML = '<div class="text-center p-4 text-gray-500">No users available</div>';
                    } else {
                        usersList.innerHTML = users.map(user => `
                            <div class="flex items-center p-3 hover:bg-gray-50 cursor-pointer" 
                                 onclick="selectUser('${user.id}', '${user.name}')">
                                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                                    ${user.name.charAt(0).toUpperCase()}
                                </div>
                                <div class="ml-3">
                                    <div class="font-medium">${user.name}</div>
                                    <div class="text-sm text-gray-500">${user.email}</div>
                                </div>
                            </div>
                        `).join('');
                    }
                    
                    document.getElementById('user-selection-modal').classList.remove('hidden');
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to load users');
                }
            };

            window.hideUserSelectionModal = function() {
                const modal = document.getElementById('user-selection-modal');
                modal.classList.add('hidden');
            };

            window.selectUser = function(userId, userName) {
                // Existing selectUser code...
                hideUserSelectionModal();
            };
        });
    </script>

    <div class="fixed inset-0 flex bg-gray-100">
        <!-- Back Button -->
        <a href="{{ route('dashboard') }}" class="absolute top-4 right-4 p-2 text-gray-600 hover:text-gray-900 z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>

        <!-- Sidebar -->
        <div class="w-72 bg-white border-r border-gray-200 flex flex-col pt-14">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800">Chat Users</h2>
                    <button onclick="showUserSelectionModal()" class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-1 text-sm font-medium shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                        </svg>
                        Add User
                    </button>
                </div>
            </div>
            <div class="user-list flex-1 overflow-y-auto">
                <!-- Users will be added here dynamically -->
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="flex-1 flex flex-col">
            <div id="message-list" class="flex-1 overflow-y-auto p-4">
                <div class="text-center text-gray-500 italic py-8" id="no-messages">
                    No messages yet. Start a conversation!
                </div>
            </div>
            <div class="border-t border-gray-200 bg-white p-6">
                <form id="message-form">
                    <div class="flex gap-3">
                        <input type="text" 
                               id="message-input"
                               class="flex-1 rounded-lg border border-gray-300 px-4 py-4 focus:outline-none focus:border-indigo-500 text-lg"
                               placeholder="Type a message...">
                        <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-sm">
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- User Selection Modal -->
    <div id="user-selection-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 w-[32rem] max-h-[80vh] flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Select Users</h3>
                <button onclick="hideUserSelectionModal()" class="text-gray-500 hover:text-gray-700 p-2">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto" id="available-users-list">
                <!-- Users will be loaded here -->
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // UI Elements
        const messageList = document.getElementById('message-list');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const userSelectionModal = document.getElementById('user-selection-modal');
        const availableUsersList = document.getElementById('available-users-list');
        const userList = document.querySelector('.user-list');

        // Websocket connection
        let socket = new WebSocket('ws://localhost:3001');
        
        // Message handling
        messageForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            const messageData = {
                id: Date.now(),
                text: message,
                sender: '{{ Auth::id() }}',
                timestamp: new Date()
            };

            socket.send(JSON.stringify(messageData));
            addMessage(messageData, true);
            messageInput.value = '';
        });

        // Websocket event handlers
        socket.onmessage = (event) => {
            const message = JSON.parse(event.data);
            addMessage(message, false);
        };

        function addMessage(message, isOwn) {
            const noMessages = document.getElementById('no-messages');
            if (noMessages) noMessages.remove();

            const messageElement = document.createElement('div');
            messageElement.className = `flex ${isOwn ? 'justify-end' : 'justify-start'} mb-4`;
            messageElement.innerHTML = `
                <div class="max-w-[70%] ${isOwn ? 'bg-blue-500 text-white' : 'bg-gray-200'} rounded-lg px-4 py-2">
                    <p>${message.text}</p>
                    <small class="text-xs opacity-75">
                        ${new Date(message.timestamp).toLocaleTimeString()}
                    </small>
                </div>
            `;
            messageList.appendChild(messageElement);
            messageList.scrollTop = messageList.scrollHeight;
        }
    </script>
    @endpush
</x-app-layout>
