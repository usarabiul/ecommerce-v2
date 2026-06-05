<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontendController extends Controller
{

    public function index()
    {
        
        return view(welcomeTheme().'index');
    }
}