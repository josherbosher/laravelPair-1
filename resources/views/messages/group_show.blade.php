@extends('messages.layout')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden mb-8">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-5V7a4 4 0 00-8 0v2a4 4 0 00-3 3.87V17a2 2 0 002 2h10a2 2 0 002-2v-2.13A4 4 0 0017 11z" />
                </svg>
                Group: {{ $group->name }}
            </h2>
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Messages</h3>
                <div class="space-y-2 max-h-64 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    @forelse($messages as $message)
                        <div class="flex items-start gap-3">
                            @if($message->sender && $message->sender->profile_picture)
                                <img src="{{ asset('storage/' . $message->sender->profile_picture) }}" alt="{{ $message->sender->name }}'s profile picture" class="h-8 w-8 rounded-full object-cover border border-gray-300 dark:border-gray-700">
                            @else
                                <div class="h-8 w-8 rounded-full bg-indigo-600 dark:bg-indigo-500 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $message->sender->name }}</span>
                                <span class="text-xs text-gray-400 ml-2">{{ $message->created_at->diffForHumans() }}</span>
                                <div class="text-gray-800 dark:text-gray-200">{{ $message->content }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No messages yet.</p>
                    @endforelse
                </div>
                <form method="POST" action="{{ route('groups.message', $group) }}" class="flex gap-2 mt-4">
                    @csrf
                    <input type="text" name="content" placeholder="Type your message..." required class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">Send</button>
                </form>
            </div>
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Group Members</h3>
                <ul class="flex flex-wrap gap-2 mb-2">
                    @foreach($group->users as $member)
                        <li class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                            @if($member->profile_picture)
                                <img src="{{ asset('storage/' . $member->profile_picture) }}" alt="{{ $member->name }}'s profile picture" class="h-6 w-6 rounded-full object-cover border border-gray-300 dark:border-gray-700">
                            @else
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 dark:bg-indigo-500 text-white text-xs font-bold">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                            @endif
                            <span class="font-medium text-gray-900 dark:text-white">{{ $member->name }}</span>
                            @if($member->id !== Auth::id())
                            <form method="POST" action="{{ route('groups.removeUser', $group) }}" class="inline">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $member->id }}">
                                <button type="submit" class="text-red-500 hover:text-red-700 ml-1" title="Remove">&times;</button>
                            </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
                <form method="POST" action="{{ route('groups.addUser', $group) }}" class="flex gap-2 mt-2">
                    @csrf
                    <select name="user_id" required class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Add user to group...</option>
                        @foreach(\App\Models\User::where('id', '!=', Auth::id())->whereNotIn('id', $group->users->pluck('id'))->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">Add</button>
                </form>
            </div>
            <a href="{{ route('groups.index') }}" class="inline-block mt-4 text-green-600 hover:underline">&larr; Back to Groups</a>
        </div>
    </div>
</div>
@endsection
