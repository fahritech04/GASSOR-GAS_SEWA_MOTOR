<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilePemilikController extends Controller
{
    public function index ()
    {
        return view ('pages.pemilik.profile.profile-pemilik');
    }

    public function edit ()
    {
        return view ('pages.pemilik.profile.editprofile-pemilik');
    }
}
