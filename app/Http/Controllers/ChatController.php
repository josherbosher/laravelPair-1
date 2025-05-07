<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $chat = Chat::create([
                'name' => 'New Chat ' . now()->format('Y-m-d H:i')
            ]);

            // Add current user and selected users to chat
            $userIds = array_unique([auth()->id(), ...$request->users]);
            $chat->users()->attach($userIds);

            DB::commit();
            return response()->json($chat->load('users'));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create chat'], 500);
        }
    }
}
