<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Home - ITE311 Web System'
        ];
        return view('index', $data);
    }

    public function about(): string
    {
        $data = [
            'title' => 'About - ITE311 Web System'
        ];
        return view('about', $data);
    }

    public function contact(): string
    {
        $data = [
            'title' => 'Contact - ITE311 Web System'
        ];
        return view('contact', $data);
    }
}
