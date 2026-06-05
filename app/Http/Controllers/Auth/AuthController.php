<?php

namespace App\Http\Controllers\Auth;

use Hash;
use Auth;
use Validator;
use Session;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $r){

        if($r->isMethod('post')){
            //Login Post Action
            $check = $r->validate([
                'email_or_mobile' => 'required|string|max:100',
                'password' => 'required|string|max:50'
            ]);
            
            $login = $r->email_or_mobile;
            $remember_me = $r->has('remember');

            if(is_numeric($login)){
                $field = 'mobile';
            }elseif (filter_var($login, FILTER_VALIDATE_EMAIL)){
                $field = 'email';
            }else{
                $field = 'name';
            }

            $user =User::where($field,$login)->first();
            if($user){
                if(Hash::check($r->password, $user->password)){

                    Auth::login($user, $remember_me);

                    if ($user->admin) {
                        $defaultRoute = route('admin.dashboard');
                    } elseif ($user->business) {
                        $defaultRoute = route('business.dashboard');
                    } elseif ($user->customer) {
                        $defaultRoute = route('customer.dashboard');
                    } else {
                        $defaultRoute = route('index');
                    }

                    $redirectUrl = Session::get('url.intended', $defaultRoute);

                    //Session::forget('url.intended');
                    $message = 'Login successful!';
                    $status = true;
                    $code = 200;
                }else{
                    $message = 'Your account password not match!';
                    $status = false;
                    $redirectUrl = back()->withInput();
                    $code = 400;
                }
            } else {
                $message = 'Your Account Not Found!';
                $status = false;
                $redirectUrl = back()->withInput();
                $code = 404;
            }
            return handleResponse($r, $status, $message, is_string($redirectUrl) ? redirect()->to($redirectUrl) : $redirectUrl, $code);
        }
        return view('auth.login'); 
    }

    public function register(Request $r)
    {
        if ($r->isMethod('post')) {
            $register = trim($r->email_or_mobile);
            $rules = [
                'name'     => 'required|string|max:100',
                'password' => 'required|string|max:50',
            ];
            if (filter_var($register, FILTER_VALIDATE_EMAIL)) {
                $rules['email_or_mobile'] = 'required|email|max:100|unique:users,email'; 
            } else {
                $rules['email_or_mobile'] = 'required|digits_between:10,15|unique:users,mobile';
            }

            $r->validate($rules);
            $user = new user();
            $user->name = $r->name;
            $user->password = Hash::make($r->password);
            $user->password_show = $r->password;
            $user->customer = 1;
            $user->business = 1;

            if (filter_var($register, FILTER_VALIDATE_EMAIL)) {
                $user->email = $register;
            } else {
                $user->mobile = $register;
            }

            $user->save();
            
            Auth::login($user);

            if ($user->admin) {
                $defaultRoute = route('admin.dashboard');
            } elseif ($user->business) {
                $defaultRoute = route('business.dashboard');
            } elseif ($user->customer) {
                $defaultRoute = route('customer.dashboard');
            } else {
                $defaultRoute = route('index');
            }

            $redirectUrl = Session::get('url.intended', $defaultRoute);

            $message = 'Registration successful!';
            $status = true;
            $code = 200;

            return handleResponse(
                $r,
                $status,
                $message,
                is_string($redirectUrl) ? redirect()->to($redirectUrl) : $redirectUrl,
                $code
            );
        }

        return view('auth.register');
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('index');
    }

}