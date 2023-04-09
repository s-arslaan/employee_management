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
            'salary_range_wise_emp' => $this->employeesModel->salaryRangeWiseEmployeeCount(),
            'highest_department_salaries' => $this->employeesModel->highestSalariesByDepartment(),
            'youngest_employees_by_department' => $this->employeesModel->youngestEmployeesByDepartment(),
        ];

        return view('index_view', $data);
    }
}
