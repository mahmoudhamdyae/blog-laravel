<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function firstAction()
    {
        $localName = 'ahmed';
        $books = ['php', 'java', 'c'];
        return view('test', ['name' => $localName, 'books' => $books]);
    }

    public function greet() {
        return 'hello this is greet function';
    }
}
