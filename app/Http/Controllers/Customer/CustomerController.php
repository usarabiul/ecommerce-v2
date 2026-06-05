<?php

namespace App\Http\Controllers\Customer;

use Auth;
use Validator;
use Session;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{

    public function dashboard()
    {

        return view(customerTheme().'dashboard');
    }
}