<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Helper\responses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request) {
        $helper = new responses();

        $nama = $request->nama;
        $email = $request->email;
        $password = $request->password;
        $role = 'user';


        if(User::where('email', $email)->count() < 1){
            $data = User::create([
                'name' => $nama,
                'email' => $email,
                'password' => bcrypt($password),
                'role' => $role,
                'api_token' => Str::random(50)
            ]);

            if($data->save()){
                return $helper->responseMessage('Berhasil daftar');
            }
            else{
                return $helper->resposeError('Gagal daftar');
            }
        }
        else{
            return $helper->responseError('Email sudah terdaftar');
        }
    }

    public function login(Request $request){
        $helper = new responses();

        $email = $request->email;
        $password = $request->password;


        $crd = ['email' => $email, 'password' => $password];

        if(Auth::attempt($crd)){
            $data = User::where('email', $email)->first();
            return $helper->responseMessageData('Berhasil Login',$data);
        }
        else{
            return $helper->responseError('Email atau Password anda salah!');
        }
    }
}
