<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Auth::user()->groups;
        return view('messages.groups', compact('groups'));
    }

    public function show(Group $group)
    {
        $messages = $group->messages()->orderBy('created_at', 'asc')->get();
        return view('messages.group_show', compact('group', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $group = Group::create(['name' => $request->name]);
        $group->users()->attach(Auth::id());
        return redirect()->route('groups.show', $group);
    }

    public function addUser(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $group->users()->syncWithoutDetaching($request->user_id);
        return back();
    }

    public function removeUser(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $group->users()->detach($request->user_id);
        return back();
    }

    public function sendMessage(Request $request, Group $group)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
        Message::create([
            'sender_id' => Auth::id(),
            'group_id' => $group->id,
            'content' => $request->content,
        ]);
        return back();
    }
}
