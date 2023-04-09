<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeesModel extends Model
{
    public function getEmployees($data)
    {
        $query = $this->db->query("SELECT e.id as emp_id, e.name, d.name as department, e.phone, e.dob, e.photo, e.email, e.salary, e.status FROM employees e LEFT JOIN departments d ON(e.department_id = d.id);
        ");

        return $query->getResultArray();
    }
    
    public function countAll()
    {
        $query = $this->db->query("SELECT COUNT(*) as total FROM employees e LEFT JOIN departments d ON(e.department_id = d.id);
        ");

        return $query->getRow()->total;
    }

    public function getEmployees1($data)
    {
        $builder = $this->db->table('employees');
        $builder->select('id, name, email, phone, department, salary');
        if (!empty($data['search']['value'])) {
            $i = 0;
            foreach ($data['column_search'] as $item) {
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, $data['search']['value']);
                } else {
                    $builder->orLike($item, $data['search']['value']);
                }
                if (count($data['column_search']) - 1 == $i) {
                    $builder->groupEnd();
                }
                $i++;
            }
        }
        $builder->orderBy($data['order'], $data['dir']);
        $builder->limit($data['length'], $data['start']);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
}