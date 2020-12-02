<?php

class Transaction_m extends CI_Model {   

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
        $table  = 'transactions as a';
        $select = "a.*, s.student_name, s.student_nis, DATE_FORMAT(a.date,'%m/%d/%Y %H:%i:%s') AS tanggal_transaksi_indo, FORMAT(a.amount,0) AS amount_indo";

        $replace_field  = [
            ['old_name' => 'student_name', 'new_name' => 's.student_name'],
            ['old_name' => 'total_price_formatted', 'new_name' => 'a.amount']
        ];

        $param = [
            'input'     => $input,
            'select'    => $select,
            'table'     => $table,
            'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input) {
            return $data->join('students as s', 's.id = a.students_id', 'left')
                        ->where('a.softdelete', '0')
                        ->where('s.softdelete', '0');
        });

        return $data;
    }

}