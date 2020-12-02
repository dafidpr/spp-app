<?php

class Transaction_detail_m extends CI_Model {   

    function __construct()
    {
        parent::__construct();
        $this->load->library('datagrid');
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
        $table  = 'transaction_details as a';
        $select = 'a.*, b.name';

        $replace_field  = [
            ['old_name' => 'bills_name', 'new_name' => 'b.bills_name']
        ];

        $param = [
            'input'     => $input,
            'select'    => $select,
            'table'     => $table,
            'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input) {
            return $data->join('bills as b', 'b.id = a.bills_id', 'left')
                        ->where('a.transaction_id', $input['transaction_id'])
                        ->where('a.softdelete', '0')
                        ->where('b.softdelete', '0');
        });

        return $data;
    }

}