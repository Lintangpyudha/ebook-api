<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function me()
    {
        return['nis' => '3103118084',
        'name' => 'Lintang Pinastika Yudha',
        'gender' => 'Female',
        'phone' => '+62852 9074 4132',
        'class' => 'XII RPL 3'];
    }
}
