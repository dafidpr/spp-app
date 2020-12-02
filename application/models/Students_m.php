<?php

class Students_m extends CI_Model {   

    function __construct()
    {
        parent::__construct();
        $this->load->library('datagrid');
    }

    /**
     * Get List of Groups
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

    public function all()
    {
    	$groups = $this->db->get('students')->result();
		return $groups;
    }

    /**
     * Get Group by ID
     *
     * @access  public
     * @param   
     * @return  json(array)
     */

    public function get_students($id)
    {
        $query = $this->db->from('students g')
                        ->select('g.*')
                        ->where('g.id', $id)
                        ->where('g.softdelete', '0')
                        ->get();

        return $query->row();
    }

    public function get_student_id($id)
    {
        $query = $this->db->from('students')
                        ->select('id')
                        ->where('users_id', $id)
                        ->where('softdelete', '0')
                        ->get();

        return $query->row()->id;
    }

    /**
     * Datagrid Data
     *
     * @access  public
     * @param   
     * @return  json(array)
     */

    public function getJson($input)
    {
        $table  = 'students as a';
        $select = 'a.*, g.students_group_name, s.students_status_name, DATE_FORMAT(a.birthdate, "%m/%d/%Y") AS birthdate_indo';

        $replace_field  = [
            ['old_name' => 'name', 'new_name' => 'a.name'],
            ['old_name' => 'students_group_name', 'new_name' => 'g.students_group_name']
        ];

        $param = [
            'input'     => $input,
            'select'    => $select,
            'table'     => $table,
            'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input) {
            return $data->join('students_group as g', 'g.id = a.students_group_id', 'left')
                        ->join('students_status as s', 's.id = a.students_status_id', 'left')
                        ->where('a.softdelete', '0');
        });

        return $data;
    }

}