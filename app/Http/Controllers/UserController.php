<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers()
    {
        return User::where('id', '!=', auth()->id())->get(['id', 'name', 'email']);
    }
}
