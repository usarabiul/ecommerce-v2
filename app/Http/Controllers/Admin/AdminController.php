<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Validator;
use Session;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    public function dashboard()
    {
        
        return view(adminTheme().'dashboard');
    }
}