@extends('messages.layout')

@section('content')
    <h2>Group: {{ $group->name }}</h2>
    <ul>
        @foreach($messages as $message)
            <li><strong>{{ $message->sender->name }}:</strong> {{ $message->content }}</li>
        @endforeach
    </ul>
    <form method="POST" action="{{ route('groups.message', $group) }}">
        @csrf
        <input type="text" name="content" placeholder="Type your message..." required>
        <button type="submit">Send</button>
    </form>
    <a href="{{ route('groups.index') }}">Back to Groups</a>

    <h3>Group Members</h3>
    <ul>
        @foreach($group->users as $member)
            <li>
                {{ $member->name }}
                @if($member->id !== Auth::id())
                <form method="POST" action="{{ route('groups.removeUser', $group) }}" style="display:inline">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $member->id }}">
                    <button type="submit">Remove</button>
                </form>
                @endif
            </li>
        @endforeach
    </ul>
    <h3>Add User to Group</h3>
    <form method="POST" action="{{ route('groups.addUser', $group) }}">
        @csrf
        <select name="user_id" required>
            <option value="">Select user</option>
            @foreach(\App\Models\User::where('id', '!=', Auth::id())->whereNotIn('id', $group->users->pluck('id'))->get() as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <button type="submit">Add</button>
    </form>
@endsection
