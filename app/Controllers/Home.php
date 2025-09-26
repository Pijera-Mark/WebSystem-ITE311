<?php

namespace App\Controllers;

class Home extends BaseController
{
<<<<<<< HEAD
    public function index()
    {
        return view('template'); // will load app/Views/template.php
=======
    public function index(): string
    {
        $data = [
            'title' => 'Home - ITE311 Web System'
        ];
        return view('home', $data);
>>>>>>> 23e0973a899deb3ad5683eced5a25bd250639e2d
    }
}
