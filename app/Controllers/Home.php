<?php

namespace App\Controllers;

use App\Models\EmployeesModel;

class Home extends BaseController
{
    protected $employeesModel;
    public function __construct()
    {
        $this->employeesModel = new EmployeesModel();
    }

    public function index()
    {
        $data = [
            'title' => APP_NAME . ' | Home',
            'departments' => $this->employeesModel->getDepartments(),
        ];

        return view('index_view', $data);
    }
}
