<?php

namespace App\Http\Controllers;

use App\User;

class TestController extends Controller
{
    public function test()
    {
        dump(\DotEnv::getDotEnvFilePath());
        dump(\DotEnv::get('APP_NAME'));
        dump(app()->environmentFilePath());
        dump(env('APP_NAME'));
        return response("this is test", 200);
    }

    public function test2()
    {
        return response()->json(User::first());
    }
}
