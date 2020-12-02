<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Invoices extends Base_Controller {
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
		$this->data['subview'] = 'invoices/main';
		$this->load->view('components/main', $this->data);
	}
	public function form()
	{
		$data['index'] = $this->input->post('index');
		$this->load->view('invoices/form', $data);
	}
	public function validate()
	{
		$rules = [
			[
				'field' => 'invoice_number',
				'label' => 'Invoice Number',
				'rules' => 'required'
			],
			[
				'field' => 'amount_indo',
				'label' => 'Amount',
				'rules' => 'required'
			],
			[
				'field' => 'transaction',
				'label' => 'Status',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			header("Content-type:application/json");
			echo json_encode('success');
		} else {
			header("Content-type:application/json");
			echo json_encode($this->form_validation->get_all_errors());
		}
	}
	public function update()
	{
		header('Content-Type: application/json');
	
		if (!empty($this->input->post('id'))) {
		$transaction = $this->input->post('transaction');
		$transaction_id    = $this->input->post('id');
		if($transaction == 'Capture' OR $transaction == 'Selesai'){
			$query = $this->db->query("SELECT t2.bills_id AS BillsID FROM transactions t1 JOIN transaction_details t2
				ON t1.id = t2.transaction_id
				WHERE t1.id = '$transaction_id'
				AND t1.softdelete = '0' AND t2.softdelete = '0'")->result();
			foreach ($query as $key => $value) {
						$databills['status']		= 'Paid';
						$databills['lunas']		    = '1';
						$databills['updated_at']	= date("Y-m-d H:i:s");
				$this->db->where('id', $value->BillsID);
				$this->db->update("bills", $databills);
			}
		}else{
			$query = $this->db->query("SELECT t2.bills_id AS BillsID FROM transactions t1 JOIN transaction_details t2
				ON t1.id = t2.transaction_id
				WHERE t1.id = '$transaction_id'
				AND t1.softdelete = '0' AND t2.softdelete = '0'")->result();
			foreach ($query as $key => $value) {
						$databills['status']		= 'Unpaid';
						$databills['lunas']		    = '0';
						$databills['payment']		= '0';
						$databills['updated_at']	= date("Y-m-d H:i:s");
				$this->db->where('id', $value->BillsID);
				$this->db->update("bills", $databills);
			}
		}
		$data['transaction']  = $transaction;
		$data['updated_at']   = date('Y-m-d H:i:s');
		$this->db->where('id', $transaction_id);
		$this->db->update('transactions', $data);
		echo json_encode('success');
	}else{
		echo json_encode('errors');
	}
	}
	public function invoice($invoice_number)
	{
		
		$this->data['title'] = 'Invoice';
		$this->data['subview'] = 'invoices/invoice';
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