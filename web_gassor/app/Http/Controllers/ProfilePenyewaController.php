<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilePenyewaController extends Controller
{
    public function index ()
    {
        return view ('pages.profile.profile-penyewa');
    }
}
