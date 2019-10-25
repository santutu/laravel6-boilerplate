<?php

namespace App\Http\Controllers;

use App\Models\User;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * @Get("/test")
     */
    public function test(Request $req)
    {
        return response("this is test", 200);
    }

    public function test2()
    {
        return response()->json(User::first());
    }

    /**
     * @Get("/throw")
     */
    public function throw()
    {
        throw new \Exception('this is test exception.');
    }
}
