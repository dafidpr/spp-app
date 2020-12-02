<?php

class Schools_group_m extends CI_Model {   

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
        $query = $this->db->from('schools_group g')
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
        $query = $this->db->from('schools_group g')
                        ->select('g.*')
                        ->where('g.id', $id)
                        ->where('g.softdelete', '0')
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
        $table  = 'schools_group as a';
        $select = 'a.*';

        $replace_field  = [
            ['old_name' => 'schools_group_name', 'new_name' => 'a.schools_group_name']
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