<?php

class Students_group_m extends CI_Model {   

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
        $query = $this->db->from('students_group g')
                        ->select('g.*')
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

    public function get_group($id)
    {
        $query = $this->db->from('students_group g')
                        ->select('g.*')
                        ->where('g.id', $id)
                        ->get();

        return $query->row();
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
        $table  = 'students_group as a';
        $select = 'a.*, l.students_level_name, g.schools_group_name, t.teachers_name';

        $replace_field  = [
            ['old_name' => 'students_group_name', 'new_name' => 'a.students_group_name']
        ];

        $param = [
            'input'     => $input,
            'select'    => $select,
            'table'     => $table,
            'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input) {
                    return $data->join('students_level as l', 'l.id = a.students_level_id', 'left')
                                 ->join('schools_group as g', 'g.id = a.schools_group_id', 'left')
                                 ->join('teachers as t', 't.id = a.teachers_id', 'left')
                                 ->where('a.softdelete', '0')
                                 ->where('l.softdelete', '0')
                                 ->where('g.softdelete', '0')
                                 ->where('t.softdelete', '0');
        });

        return $data;
    }

}