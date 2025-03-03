<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.products');
    }

    public function foodBeverage()
    {
        return view('pages.products', ['category' => 'Food & Beverage']);
    }

    public function beautyHealth()
    {
        return view('pages.products', ['category' => 'Beauty & Health']);
    }

    public function homeCare()
    {
        return view('pages.products', ['category' => 'Home Care']);
    }

    public function babyKid()
    {
        return view('pages.products', ['category' => 'Baby & Kid']);
    }
}
