<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

     public function index()
    {
        // Получаем всех пользователей с количеством их замков
        $users = User::withCount('castles')
                    ->orderBy('name')
                    ->paginate(15); // Пагинация по 15 пользователей
        
        return view('users.index', compact('users'));
    }
        
}
