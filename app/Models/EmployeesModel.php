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

        if ($total_flag)
            return count($query->getResultArray());

        return $query->getResultArray();
    }

    public function countAll()
    {
        $query = $this->db->query("SELECT COUNT(*) as total FROM employees e LEFT JOIN departments d ON(e.department_id = d.id);
        ");

        return $query->getRow()->total;
    }


    public function getDepartments()
    {
        $query = $this->db->query("SELECT id, name FROM departments");
        return $query->getResultArray();
    }

    public function getEmployee($id)
    {
        $query = $this->db->query("SELECT * FROM employees WHERE id = $id");
        return $query->getRowArray();
    }
    
    public function getEmployeeByEmail($email)
    {
        $sql = "SELECT * FROM employees WHERE email = '$email'";
        $query = $this->db->query($sql);
        return $query->getRowArray();
    }

    public function addEditEmployee($data)
    {
        if ($data['id'] != '' && $data['id'] > 0) {
            // dd('yo');
            if ($data['photo'] == '')
                unset($data['photo']);

            $this->db->table('employees')->update($data, ['id' => $data['id']]);
            return 1;

        } else {
            $this->db->table('employees')->insert($data);
            return 2;
        }
    }

    public function deleteEmployee($id)
    {
        $query = $this->db->table('employees')->delete(['id' => $id]);
        return $query;
    }

    public function highestSalariesByDepartment()
    {   
        // with employee name
        $sql = "SELECT d.name as department, e.name, e.salary FROM employees e LEFT JOIN departments d ON(e.department_id = d.id) WHERE e.salary = (SELECT MAX(salary) FROM employees WHERE department_id = e.department_id) ORDER BY e.salary DESC";

        // without employee name (only department name and max salary)
        // $sql = "SELECT d.name as department, MAX(e.salary) as max_salary FROM employees e LEFT JOIN departments d ON(e.department_id = d.id) GROUP BY e.department_id order by max_salary DESC";

        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    public function salaryRangeWiseEmployeeCount()
    {
        $sql = "SELECT CASE WHEN salary BETWEEN 0 AND 50000 THEN '0-50000' WHEN salary BETWEEN 50001 AND 100000 THEN '50001-100000' ELSE '100000+' END AS salary_range, COUNT(*) AS employee_count FROM employees GROUP BY salary_range";

        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    public function youngestEmployeesByDepartment()
    {
        $sql = "SELECT d.name as department, e.department_id, e.name AS employee_name, DATEDIFF(CURDATE(), e.dob)/365 AS age FROM employees e LEFT JOIN departments d ON(e.department_id = d.id) WHERE (e.department_id, e.dob) IN (SELECT department_id, MAX(dob) FROM employees GROUP BY department_id) ORDER BY age";

        $query = $this->db->query($sql);
        return $query->getResultArray();
    }
}
