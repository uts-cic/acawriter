<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Assignment;
use App\Document;
use App\Draft;
use App\User;
use App\Role;
use App\Events\UserRegistered;

class DiffController extends Controller 
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function showDraft()
    {
        $data = new \stdClass;
        $documents = Draft::where('document_id', $document->id)->orderBy('created_at', 'desc');
    }
}
?>