<?php

class Billing_m extends CI_Model {   

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
    	$groups = $this->db->get('bills')->result();
		return $groups;
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
        $query = $this->db->from('bills g')
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

    public function getJson($input, $id)
    {
        $table  = 'bills as a';
        $select = 'a.*, b.student_name, b.student_nis, c.bills_category_name, DATE_FORMAT(a.duedate, "%m/%d/%Y") AS duedate';

        $replace_field  = [
            ['old_name' => 'name', 'new_name' => 'a.name'],
            ['old_name' => 'total_price_formatted', 'new_name' => 'a.amount']
         ];

        $param = [
            'input'     => $input,
            'select'    => $select,
            'table'     => $table,
            'replace_field' => $replace_field
        ];
        $data = $this->datagrid->query($param, function($data) use ($input, $id) {
            return $data->join('students as b', 'b.id = a.students_id', 'left')
                        ->join('bills_category as c', 'c.id = a.bills_category_id', 'left')
                        ->where('a.students_id', $id)
                        ->where('a.softdelete', '0');
        });

        return $data;
    }

    public function setBilling($students_id)
    {
        
        //insert transactions
        $transaction = $this->db->from('bills')
                        ->select('students_id, SUM(amount) AS Total')
                        ->where('students_id', $students_id)
                        ->where('payment', '1')
                        ->where('lunas', '0')
                        ->where('softdelete', '0')
                        ->get()->row();

        $CountTransactions=$this->db->query("SELECT * FROM transactions")->num_rows();
        $FormatInvoice = date('Ymd').'0000' + $CountTransactions + 1;

        $invoice_number                 = $FormatInvoice;
        $transactions['invoice_number'] = $invoice_number;
        $transactions['students_id']    = $transaction->students_id;
        $transactions['transaction']    = 'pending';
        $transactions['type']           = '';
        $transactions['amount']         = $transaction->Total;
        $transactions['date']           = date('Y-m-d H:i:s');
        $transactions['created_at']     = date('Y-m-d H:i:s');
        $transactions['updated_at']     = date('Y-m-d H:i:s');
        $this->db->insert('transactions', $transactions); 

        //cari id transactions
        $query = $this->db->from('transactions')
                        ->select('id')
                        ->where('students_id', $students_id)
                        ->where('invoice_number', $invoice_number)
                        ->where('transaction', 'pending')
                        ->get()->row();
        $transaction_id = $query->id;

        //insert transactions_details
        $details = $this->db->from('bills')
                        ->select('id, bills_category_id, name, amount')
                        ->where('students_id', $students_id)
                        ->where('payment', '1')
                        ->where('lunas', '0')
                        ->where('softdelete', '0')
                        ->get()->result();

        foreach ($details as $row) {
            $data['transaction_id']     = $transaction_id;
            $data['bills_id']           = $row->id;
            $data['bills_category_id']  = $row->bills_category_id;
            $data['bills_name']         = $row->name;
            $data['bills_amount']       = $row->amount;
            $data['created_at']         = date('Y-m-d H:i:s');
            $data['updated_at']         = date('Y-m-d H:i:s');
            $this->db->insert('transaction_details', $data); 
        }

       // return $data;
    }

}