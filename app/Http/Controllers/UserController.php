<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers()
    {
        return User::where('id', '!=', auth()->id())
            ->select(['id', 'name', 'email'])
            ->get();
    }
}
