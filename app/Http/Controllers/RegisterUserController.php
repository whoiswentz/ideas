<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RegisterUserController extends Controller
{
    public function create(Request $request): Factory|View
    {
        return view('auth.register');
    }
}
