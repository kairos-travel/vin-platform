<?php

namespace App\Http\Controllers;

use App\Models\Service;

class MainController extends Controller
{
    public function index()
    {
        $services = Service::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        //dd($services);

        return view('main.index', compact('services'));
    }
}
