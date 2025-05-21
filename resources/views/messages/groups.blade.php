@extends('messages.layout')

@section('content')
    <h2>Your Groups</h2>
    <ul>
        @foreach($groups as $group)
            <li><a href="{{ route('groups.show', $group) }}">{{ $group->name }}</a></li>
        @endforeach
    </ul>
    <h3>Create New Group</h3>
    <form method="POST" action="{{ route('groups.store') }}">
        @csrf
        <input type="text" name="name" placeholder="Group Name" required>
        <label>Select Users:</label>
        <select name="users[]" multiple required>
            @foreach(Auth::user()->where('id', '!=', Auth::id())->get() as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <button type="submit">Create Group</button>
    </form>
@endsection
