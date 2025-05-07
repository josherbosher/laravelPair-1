<x-messages.layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $user->name }}
                    </h2>
                    <a href="{{ route('messages.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                        Back to messages
                    </a>
                </div>

                <div class="space-y-4 mb-4 h-[32rem] overflow-y-auto p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    @foreach($messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="{{ $message->sender_id === auth()->id() 
                                ? 'bg-indigo-600 text-white' 
                                : 'bg-gray-500 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }} rounded-lg px-4 py-2 max-w-sm">
                                <p class="text-sm">{{ $message->content }}</p>
                                <span class="text-xs {{ $message->sender_id === auth()->id() 
                                    ? 'opacity-75' 
                                    : 'text-gray-600 dark:text-gray-400' }}">{{ $message->created_at->format('g:i A') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form action="{{ route('messages.store', $user) }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" 
                           name="content" 
                           placeholder="Type your message..." 
                           class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-black-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                           required>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-messages.layout>
