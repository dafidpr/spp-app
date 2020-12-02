<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends Base_Controller {

	/**
     * Transaction List
     *
     * @access  public
     * @param   
     * @return  view
     */
	function __construct()
	{
		parent::__construct();

		if($this->session->userdata('active_user')->group_id == '3'){
			redirect('auth/logout');
		}
	}		
	public function index()
	{
		$this->data['title'] = 'Transactions';
		$this->data['subview'] = 'transaction/main';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Invoice Details
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function invoice($invoice_number)
	{
		
		$this->data['title'] = 'Invoice';
		$this->data['subview'] = 'transaction/invoice';
		$this->data['company_info'] = $this->db->from('settings')
										 ->where_in('meta_key', [
        									'company_name', 
        									'company_address', 
        									'company_phone_number',
        									'company_email'
        								 ])->get()->result();

		$this->data['transaction'] = $this->db->select('t.*, s.student_name, s.student_nis, s.students_group_id, g.students_group_name')
										->from('transactions t')
										->where('t.invoice_number', $invoice_number)
										->where('t.softdelete', '0')
										->join('students as s', 's.id = t.students_id', 'left')
										->join('students_group as g', 'g.id = s.students_group_id', 'left')
										->get()->row();

		$this->data['transaction_details'] = $this->db->from('transaction_details')
												->where('transaction_id', $this->data['transaction']->id)
												->where('softdelete', '0')
												->get()->result();

		$this->load->view('components/main', $this->data);
	}

	/**
     * Datagrid Data
     *
     * @access  public
     * @param   
     * @return  json(array)
     */

	public function data()
	{
        header('Content-Type: application/json');
        $this->load->model('transaction_m');
		echo json_encode($this->transaction_m->getJson($this->input->post()));
	}

	/**
     * Datagrid Data
     *
     * @access  public
     * @param   
     * @return  json(array)
     */

	public function detail()
	{
        header('Content-Type: application/json');
        $this->load->model('transaction_detail_m');
		echo json_encode($this->transaction_detail_m->getJson($this->input->post()));
	}


	public function delete()
	{
		$data['softdelete']   = '1';
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('transactions', $data); 



		$data['softdelete']   = '1';
		$this->db->where('transaction_id', $this->input->post('id'));
		$this->db->update('transaction_details', $data); 
	}

}
