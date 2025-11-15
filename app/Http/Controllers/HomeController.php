<?php

namespace App\Http\Controllers;

use App\Models\ServicePackage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with services and pricing
     */
    public function index()
    {
        $services = ServicePackage::with('values')->get();

        return view('home', compact('services'));
    }
}
