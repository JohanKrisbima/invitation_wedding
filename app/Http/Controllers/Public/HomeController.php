<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the Lumina Invites public landing page.
     */
    public function index(): View
    {
        $templates = Template::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact('templates'));
    }
}
