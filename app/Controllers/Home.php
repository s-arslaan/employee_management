<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => APP_NAME.' | Home',
        ];

        return view('index_view', $data);
    }
}
