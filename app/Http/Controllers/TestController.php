<?php

namespace App\Http\Controllers;

use App\Events\testeWebsocket;
use Illuminate\Http\Request;

class TestController extends Controller
{

    public function test()
    {
        event(new testeWebsocket);
    }
}
