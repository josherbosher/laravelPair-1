<x-messages.layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-lg sm:text-2xl font-bold mb-4 dark:text-white">Messages</h2>
                
                <div class="space-y-2 sm:space-y-4">
                    @foreach($users as $user)
                        <a href="{{ route('messages.show', $user) }}" 
                           class="flex items-center p-3 sm:p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <div class="ml-3 sm:ml-4">
                                <div class="text-sm sm:text-base font-medium text-gray-900 dark:text-gray-100">
                                    {{ $user->name }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-messages.layout>
