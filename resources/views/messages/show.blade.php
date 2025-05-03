<x-messages.layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">
                        {{ $user->name }}
                    </h2>
                    <a href="{{ route('messages.index') }}" class="text-indigo-600 hover:text-indigo-900">
                        Back to messages
                    </a>
                </div>

                <div class="space-y-4 mb-4 h-[32rem] overflow-y-auto p-4 bg-gray-50 rounded-lg">
                    @foreach($messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="{{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-900' }} rounded-lg px-4 py-2 max-w-sm">
                                <p class="text-sm">{{ $message->content }}</p>
                                <span class="text-xs opacity-75">{{ $message->created_at->format('g:i A') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form action="{{ route('messages.store', $user) }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" 
                           name="content" 
                           placeholder="Type your message..." 
                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           required>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-messages.layout>
