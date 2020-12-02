<?php

class Teachers_m extends CI_Model {   

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
        $query = $this->db->from('teachers g')
                        ->where('g.softdelete', '0')
                        ->get();

        return $query->result();
    }

    /**
     * Get Group by ID
     *
     * @access  public
     * @param   
     * @return  json(array)
     */

    public function get_teachers($id)
    {
        $query = $this->db->from('teachers g')
                        ->select('g.*')
                        ->where('g.id', $id)
                        ->where('g.softdelete', '0')
                        ->get();

        return $query->row();
    }

    public function get_student_id($id)
    {
        $query = $this->db->from('teachers')
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
        $table  = 'teachers as a';
        $select = 'a.*, DATE_FORMAT(a.birthdate, "%m/%d/%Y") AS birthdate_indo';

        $replace_field  = [
            ['old_name' => 'name', 'new_name' => 'a.name']
        ];

        $param = [
            'input'     => $input,
            'select'    => $select,
            'table'     => $table,
            'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input) {
            return $data->where('a.softdelete', '0');
        });

        return $data;
    }

}