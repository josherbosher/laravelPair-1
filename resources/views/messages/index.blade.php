<x-messages.layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-2xl font-bold mb-4 dark:text-white">Messages</h2>
                
                <div class="space-y-4">
                    @foreach($users as $user)
                        <a href="{{ route('messages.show', $user) }}" 
                           class="flex items-center p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
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
