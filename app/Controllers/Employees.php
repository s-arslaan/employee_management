<?php

namespace App\Controllers;

class Employees extends BaseController
{
    protected $db;
    
    public function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        $data = [
            'title' => APP_NAME . ' | Employees',
        ];

        return view('employees_view', $data);
    }
    public function get_employees()
    {
        $request = \Config\Services::request();
        $draw = $request->getPost('draw');
        $start = $request->getPost('start');
        $length = $request->getPost('length');
        $search = $request->getPost('search');
        $order = $request->getPost('order');
        $column_order = array('id', 'name', 'email', 'phone', 'department', 'salary');
        $column_search = array('name', 'email', 'phone', 'department', 'salary');
        $order = $column_order[$order[0]['column']];
        $dir = $order[0]['dir'];

        $builder = $this->db->table('employees');
        $builder->select('id, name, email, phone, department, salary');
        if (!empty($search['value'])) {
            $i = 0;
            foreach ($column_search as $item) {
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, $search['value']);
                } else {
                    $builder->orLike($item, $search['value']);
                }
                if (count($column_search) - 1 == $i) {
                    $builder->groupEnd();
                }
                $i++;
            }
        }
        $builder->orderBy($order, $dir);
        $builder->limit($length, $start);
        $query = $builder->get();
        $data = $query->getResultArray();

        $builder = $this->db->table('employees');
        $builder->select('id, name, email, phone, department, salary');
        if (!empty($search['value'])) {
            $i = 0;
            foreach ($column_search as $item) {
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, $search['value']);
                } else {
                    $builder->orLike($item, $search['value']);
                }
                if (count($column_search) - 1 == $i) {
                    $builder->groupEnd();
                }
                $i++;
            }
        }
        $builder->orderBy($order, $dir);
        $query = $builder->get();
        $total = $query->getResultArray();

        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($total),
            "recordsFiltered" => count($total),
            "data" => $data,
        );
        echo json_encode($output);
    }

}