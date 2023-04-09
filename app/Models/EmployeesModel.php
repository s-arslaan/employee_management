<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeesModel extends Model
{
    public function getEmployees($data, $total_flag = false)
    {
        $sql = "SELECT e.id as emp_id, e.name, d.name as department, e.phone, e.dob, e.photo, e.email, e.salary, e.status FROM employees e LEFT JOIN departments d ON(e.department_id = d.id) ";

        if (!empty($data['search']['value'])) {
            $sql .= "WHERE e.name LIKE '%" . $data['search']['value'] . "%' ";
        }

        $sort_data = array(
            "emp_id",
            "e.name",
            "e.email",
            "department",
            "e.dob",
            "e.phone",
            "e.salary",
            "e.status"
        );

        if (isset($data['order'][0]['column'])) {
            $sql .= " ORDER BY " . $sort_data[$data['order'][0]['column']];
        } else {
            $sql .= " ORDER BY emp_id";
        }

        if (isset($data['order'][0]['column']) && ($data['order'][0]['dir'] == 'desc')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if ((isset($data['start']) || isset($data['length'])) && !$total_flag) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['length'] < 1) {
                $data['length'] = 10;
            }
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['length'];
        }

        $query = $this->db->query($sql);

        if($total_flag)
            return count($query->getResultArray());
            
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