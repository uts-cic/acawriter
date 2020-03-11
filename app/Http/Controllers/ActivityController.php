<?php

namespace App\Http\Controllers;

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
     * Collect callback handler
     */
    public function collect()
    {
        $input = $this->validate(request(), [
            'category' => ['required', 'string', 'max:255'],
            'action' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
        ]);
        $user = Auth::user();
        $action = implode('::', array($input['category'], $input['action'], $input['label']));
        event(new UserLog($user, $action));
    }
}
