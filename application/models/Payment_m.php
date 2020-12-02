<?php

class Payment_m extends CI_Model {   

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

 
    public function getJson($input, $students_id)
    {
        $table  = 'transactions as a';
        $select = "a.*, s.student_name, s.student_nis, DATE_FORMAT(a.date,'%m/%d/%Y %H:%i:%s') AS tanggal_transaksi_indo";

        $replace_field  = [
        ['old_name' => 'student_name', 'new_name' => 'a.student_name'],
        ['old_name' => 'total_price_formatted', 'new_name' => 'a.amount']
        
        ];

        $param = [
        'input'     => $input,
        'select'    => $select,
        'table'     => $table,
        'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input, $students_id) {
            return $data->join('students as s', 's.id = a.students_id', 'left')
            ->where('a.softdelete', '0')
            ->where('s.softdelete', '0')
            ->where('a.students_id', $students_id);
        });

        return $data;
    }

    public function getJsonDetail($input)
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