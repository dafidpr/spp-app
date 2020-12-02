<?php

class Billing_category_m extends CI_Model {   

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
        $query = $this->db->from('bills_category g')
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
        $query = $this->db->from('bills_category g')
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
        $table  = 'bills_category as a';
        $select = 'a.*';

        $replace_field  = [
            ['old_name' => 'bills_category_', 'new_name' => 'a.bills_category_']
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