<?php

namespace App\Http\Controllers\Business;

use Auth;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BusinessController extends Controller
{

    public function dashboard()
    {
        
        return view(supplierTheme().'dashboard');
    }
}