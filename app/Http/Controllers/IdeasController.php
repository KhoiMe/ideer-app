<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IdeasController extends Controller
{
    public function index(): View
    {
        /* join the table users with ideas  for the ideas Feed */
        $ideas = DB::table('ideas')
            ->join('users', 'user_id', '=', 'users.id')
            ->select('users.name', 'ideas.*')
            ->get();
        return view('ideas', ['ideas' => $ideas]);
    }
}
