<?php

class Reports_m extends CI_Model {   

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

    public function getJson_v1($input)
    {
        $table  = 'transactions as a';
        $select = "a.*, s.student_name, s.student_nis, DATE_FORMAT(a.date,'%m/%d/%Y %H:%i:%s') AS tanggal_transaksi_indo";

        $replace_field  = [
        ['old_name' => 'student_name', 'new_name' => 'a.student_name']
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

    public function getJson_v2($input)
    {
        $table  = 'transactions as a';
        $select = "a.*, s.student_name, s.student_nis, DATE_FORMAT(a.date,'%m/%d/%Y %H:%i:%s') AS tanggal_transaksi_indo";

        $replace_field  = [
        ['old_name' => 'student_name', 'new_name' => 'a.student_name']
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

    public function getJson_v3($input,$getstartDatetime,$getendDatetime)
    {
        $table  = 'transactions as a';
        $select = "a.*, s.student_name, s.student_nis, DATE_FORMAT(a.date,'%m/%d/%Y %H:%i:%s') AS tanggal_transaksi_indo";

        $replace_field  = [
        ['old_name' => 'student_name', 'new_name' => 'a.student_name']
        ];

        $param = [
        'input'     => $input,
        'select'    => $select,
        'table'     => $table,
        'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input,$getstartDatetime,$getendDatetime) {
            return $data->join('students as s', 's.id = a.students_id', 'left')
            ->where('a.softdelete', '0')
            ->where('s.softdelete', '0')
            ->where('a.transaction', 'settlement')
            ->where('a.date >=', $getstartDatetime)
            ->where('a.date <=', $getendDatetime);
        });

        return $data;
    }

    public function getJson_v4($input,$getstartDate)
    {
        $table  = 'bills as a';
        $select = "a.*, s.student_name, s.student_nis, g.students_group_name, SUM(a.amount) AS TotalAmount";

        $replace_field  = [
        ['old_name' => 'student_name', 'new_name' => 's.student_name']
        ];

        $param = [
        'input'     => $input,
        'select'    => $select,
        'table'     => $table,
        'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input,$getstartDate) {
            return $data->join('students as s', 's.id = a.students_id', 'left')
            ->join('students_group as g', 'g.id = s.students_group_id', 'left')
            ->where('a.softdelete', '0')
            ->where('a.lunas', '0')
            ->where('s.softdelete', '0')
            ->where('a.duedate <', $getstartDate)
            ->group_by('a.students_id');
        });

        return $data;
    }

    public function getJson_v5($input,$getendDate)
    {
        $table  = 'bills as a';
        $select = "a.*, s.student_name, s.student_nis, g.students_group_name, SUM(a.amount) AS TotalAmount";

        $replace_field  = [
        ['old_name' => 'student_name', 'new_name' => 's.student_name']
        ];

        $param = [
        'input'     => $input,
        'select'    => $select,
        'table'     => $table,
        'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input,$getendDate) {
            return $data->join('students as s', 's.id = a.students_id', 'left')
            ->join('students_group as g', 'g.id = s.students_group_id', 'left')
            ->where('a.softdelete', '0')
            ->where('s.softdelete', '0')
            ->where('a.lunas', '0')
            ->where('a.duedate >', $getendDate)
            ->group_by('a.students_id');
        });

        return $data;
    }

    public function getJson_v6($input,$getstartDate,$getendDate)
    {
        $table  = 'bills as a';
        $select = "a.*, s.student_name, s.student_nis, g.students_group_name, SUM(a.amount) AS TotalAmount";

        $replace_field  = [
        ['old_name' => 'student_name', 'new_name' => 's.student_name']
        ];

        $param = [
        'input'     => $input,
        'select'    => $select,
        'table'     => $table,
        'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input,$getstartDate,$getendDate) {
            return $data->join('students as s', 's.id = a.students_id', 'left')
            ->join('students_group as g', 'g.id = s.students_group_id', 'left')
            ->where('a.softdelete', '0')
            ->where('s.softdelete', '0')
            ->where('a.duedate >=', $getstartDate)
            ->where('a.duedate <=', $getendDate)
            ->group_by('a.students_id');
        });

        return $data;
    }

    public function detail_reports_v1($input)
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

    public function detail_reports_v2($input)
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

    public function detail_reports_v3($input,$getstartDatetime,$getendDatetime)
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

        $data = $this->datagrid->query($param, function($data) use ($input,$getstartDatetime,$getendDatetime) {
            return $data->join('bills as b', 'b.id = a.bills_id', 'left')
            ->where('a.transaction_id', $input['transaction_id'])
            ->where('a.softdelete', '0')
            ->where('b.softdelete', '0')
            ->where('b.lunas', '1');
        });

        return $data;
    }

    public function detail_reports_v4($input,$getstartDate)
    {
        $table  = 'bills as a';
        $select = 'a.*, c.bills_category_name';

        $replace_field  = [
        ['old_name' => 'name', 'new_name' => 'a.name']
        ];

        $param = [
        'input'     => $input,
        'select'    => $select,
        'table'     => $table,
        'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input,$getstartDate) {
            return $data->join('bills_category as c', 'c.id = a.bills_category_id', 'left')
            ->where('a.students_id', $input['students_id'])
            ->where('a.softdelete', '0')
            ->where('a.lunas', '0')
            ->where('a.duedate <', $getstartDate)
            ->order_by('a.lunas', 'ASC');
        });

        return $data;
    }

    public function detail_reports_v5($input,$getendDate)
    {
        $table  = 'bills as a';
        $select = 'a.*, c.bills_category_name';

        $replace_field  = [
        ['old_name' => 'name', 'new_name' => 'a.name']
        ];

        $param = [
        'input'     => $input,
        'select'    => $select,
        'table'     => $table,
        'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input,$getendDate) {
            return $data->join('bills_category as c', 'c.id = a.bills_category_id', 'left')
            ->where('a.students_id', $input['students_id'])
            ->where('a.softdelete', '0')
            ->where('a.lunas', '0')
            ->where('a.duedate >', $getendDate);
        });

        return $data;
    }

    public function detail_reports_v6($input,$getstartDate,$getendDate)
    {
        $table  = 'bills as a';
        $select = 'a.*, c.bills_category_name';

        $replace_field  = [
        ['old_name' => 'name', 'new_name' => 'a.name']
        ];

        $param = [
        'input'     => $input,
        'select'    => $select,
        'table'     => $table,
        'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input,$getstartDate,$getendDate) {
            return $data->join('bills_category as c', 'c.id = a.bills_category_id', 'left')
            ->where('a.students_id', $input['students_id'])
            ->where('a.softdelete', '0')
            ->where('a.duedate >=', $getstartDate)
            ->where('a.duedate <=', $getendDate)
            ->order_by('a.lunas', 'ASC');
        });

        return $data;
    }

}