<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Events\UserLog;

class ActivityController extends Controller
{
    /**
     * Require authenticated access
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
     * Input: $request
     */
    public function collect(Request $request)
    {
        $user = Auth::user();
        $action = implode('::', array($request['category'], $request['action'], $request['label']));
        event(new UserLog($user, $action));
    }
}
