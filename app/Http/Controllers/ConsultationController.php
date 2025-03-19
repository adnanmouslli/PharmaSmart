<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index()
    {
        return view('consultations.index');
    }

    public function chat()
    {
        return view('consultations.chat');
    }
}