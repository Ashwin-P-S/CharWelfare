<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Stichoza\GoogleTranslate\GoogleTranslate;

class HomeController extends Controller
{
    public function index(): View
    {
        $lang = Session::get("lang");

        if ($lang == null) {
            $lang = "en";
            Session::put("lang", $lang);
        }

        $translator = new GoogleTranslate();
        $translator->setTarget($lang);

        return view('home', compact('translator'));
    }

    public function setLanguage(string $lang): RedirectResponse
    {
        Session::put("lang", $lang);

        return redirect()->to('/');
    }
}
