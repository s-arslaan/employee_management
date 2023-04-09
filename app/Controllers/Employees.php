<?php

namespace App\Controllers;

use App\Models\EmployeesModel;
use CodeIgniter\API\ResponseTrait;

class Employees extends BaseController
{
    protected $db;
    protected $employeesModel;
    protected $session;
    protected $helpers = ['form'];
    use ResponseTrait;

    public function __construct()
    {
        $this->employeesModel = new EmployeesModel();
        $this->session = \Config\Services::session();
        $this->db = db_connect();
    }

    public function addEditEmployee()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email',
            'phone' => 'required|min_length[10]|max_length[12]',
            'department' => 'required|numeric',
            'dob' => 'required|valid_date',
            'salary' => 'required|numeric',
        ];

        $data = [
            'id' => '',
            'name' => '',
            'email' => '',
            'phone' => '',
            'department_id' => '',
            'dob' => '',
            'salary' => '',
            'status' => '',
        ];

        $data['departments'] = $this->employeesModel->getDepartments();
        $data['title'] = APP_NAME . ' | Add Employee';
        $data['heading'] = 'Add New Employee';

        if ($this->request->is('post')) {

            $data['id'] = $this->request->getPost('emp_id', FILTER_SANITIZE_SPECIAL_CHARS);
            $data['name'] = $this->request->getPost('name', FILTER_SANITIZE_SPECIAL_CHARS);
            $data['email'] = $this->request->getPost('email', FILTER_SANITIZE_EMAIL);
            $data['phone'] = $this->request->getPost('phone', FILTER_SANITIZE_SPECIAL_CHARS);
            $data['department_id'] = $this->request->getPost('department', FILTER_SANITIZE_SPECIAL_CHARS);
            $data['dob'] = $this->request->getPost('dob', FILTER_SANITIZE_SPECIAL_CHARS);
            $data['salary'] = $this->request->getPost('salary', FILTER_SANITIZE_SPECIAL_CHARS);

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {

                if ($this->request->getFile('image') !== null) {

                    $img = $this->request->getFile('image');

                    $type = $img->getMimeType();
                    $size = $img->getSize();

                    $mime_types = array(
                        'image/bmp',
                        'image/jpeg',
                        'image/x-png',
                        'image/png',
                        'image/gif'
                    );

                    if (in_array($type, $mime_types)) {
                        if ($size <= 5000000) {
                            // size less than 5 MB

                            if ($img->isValid() && !$img->hasMoved()) {
                                $newName = $img->getRandomName();
                                $img->move(WRITEPATH . 'uploads', $newName);
                                // dd($newName);
                            } else {
                                $this->session->setTempdata('error', 'Something went wrong!');
                                return view('employee_edit', $data);
                            }

                        } else {
                            $this->session->setTempdata('error', 'File size should be less than 5 MB!');
                            return view('employee_edit', $data);
                        }
                    } else {
                        $this->session->setTempdata('error', 'Invalid File!');
                        return view('employee_edit', $data);
                        // return redirect()->to(current_url());
                    }
                }

                $newData = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'department_id' => $data['department_id'],
                    'dob' => $data['dob'],
                    'photo' => $newName,
                    'salary' => $data['salary'],
                ];

                $this->employeesModel->addEditEmployee($newData);
                $this->session->setTempdata('success', 'Employee added successfully!');
                return redirect()->to(base_url());
            }
        }
        
        return view('employee_edit', $data);
    }

    public function deleteEmployee($id)
    {
        $this->employeesModel->deleteEmployee($id);
        $this->session->setTempdata('success', 'Employee deleted successfully!');
        return redirect()->to(base_url());
    }

    public function get_employees_api()
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('No direct access allowed', 400);
        }

        $data = [
            'draw' => $this->request->getPost('draw'),
            'recordsTotal' => $this->employeesModel->countAll(),
            'recordsFiltered' => $this->employeesModel->getEmployees($this->request->getPost(), true),
            'data' => $this->employeesModel->getEmployees($this->request->getPost()),
        ];

        return $this->respond($data, 200);
    }
}
