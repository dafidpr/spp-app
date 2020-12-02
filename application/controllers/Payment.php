<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends Base_Controller {

	/**
     * List of bills
     *
     * @access 	public
     * @param 	
     * @return 	view
     */
	function __construct()
	{
		parent::__construct();

		if($this->session->userdata('active_user')->group_id != '3'){
			redirect('auth/logout');
		}
	}




	public function index()
	{	
		$this->data['title'] = 'School Payment '. get_field('1','settings','meta_value');
		$this->data['subview'] = 'payment/main';

		//cari tagihan
		$students_id = $this->students_m->get_student_id($this->session->userdata('active_user')->id);

		$SqlYes = $this->db->from('bills b')
		->where('b.students_id', $students_id)
		->where('b.lunas', '0')
		->where('b.status', 'Unpaid')
		->where('b.softdelete', '0')
		->where('b.payment', '1')
		->where('c.bills_category_status', 'Tetap')
		->join('bills_category as c', 'b.bills_category_id = c.id', 'left')
		->get()->num_rows();

		$this->data['BayarID'] = $SqlYes + 1;

		$this->data['bills'] = $this->db->select('b.*, c.bills_category_name, c.bills_category_status')
		->from('bills b')
		->where('b.students_id', $students_id)
		->where('b.lunas', '0')
		->where('b.status', 'Unpaid')
		->where('b.softdelete', '0')
		->where('c.bills_category_status', 'Tetap')
		->join('bills_category as c', 'b.bills_category_id = c.id', 'left')
		->get()->result();

		$this->data['bills_temporary'] = $this->db->select('b.*, c.bills_category_name, c.bills_category_status')
		->from('bills b')
		->where('b.students_id', $students_id)
		->where('b.lunas', '0')
		->where('b.status', 'Unpaid')
		->where('b.softdelete', '0')
		->where('c.bills_category_status', 'Tidak Tetap')
		->join('bills_category as c', 'b.bills_category_id = c.id', 'left')
		->get()->result();


		$this->load->view('components/main', $this->data);
	}

	/**
     * Product Form
     *
     * @access 	public
     * @param 	
     * @return 	view
     */

	public function load_payment()
	{	

		//header('Content-Type: application/json');
		//cari tagihan
		$students_id = $this->students_m->get_student_id($this->session->userdata('active_user')->id);

		$SqlYes = $this->db->from('bills b')
		->where('b.students_id', $students_id)
		->where('b.lunas', '0')
		->where('b.status', 'Unpaid')
		->where('b.softdelete', '0')
		->where('b.payment', '1')
		->where('c.bills_category_status', 'Tetap')
		->join('bills_category as c', 'b.bills_category_id = c.id', 'left')
		->get()->num_rows();
		$this->data['BayarID'] = $SqlYes + 1;

		$this->data['bills'] = $this->db->select('b.*, c.bills_category_name, c.bills_category_status')
		->from('bills b')
		->where('b.students_id', $students_id)
		->where('b.lunas', '0')
		->where('b.status', 'Unpaid')
		->where('b.softdelete', '0')
		->where('c.bills_category_status', 'Tetap')
		->join('bills_category as c', 'b.bills_category_id = c.id', 'left')
		->get()->result();

		$this->data['bills_temporary'] = $this->db->select('b.*, c.bills_category_name, c.bills_category_status')
		->from('bills b')
		->where('b.students_id', $students_id)
		->where('b.lunas', '0')
		->where('b.status', 'Unpaid')
		->where('b.softdelete', '0')
		->where('c.bills_category_status', 'Tidak Tetap')
		->join('bills_category as c', 'b.bills_category_id = c.id', 'left')
		->get()->result();

		$this->load->view('payment/main_load', $this->data);
	}


	function checked($billsID='')
	{
		@extract($_POST);
		$update['payment']=1;
		$this->db->where('id',$billsID);
		$this->db->update('bills',$update);
	}

	function unchecked()
	{
		@extract($_POST);

		$students_id = $this->students_m->get_student_id($this->session->userdata('active_user')->id);

		$sqlDueDate = $this->db->query("SELECT duedate FROM bills WHERE id = '$billsID'")->row();

		$JenisID = get_field(get_field($billsID,'bills','bills_category_id'),'bills_category','bills_category_status');

		if($JenisID == 'Tetap'){
			$where="students_id='$students_id' AND lunas = '0' AND duedate >= '$sqlDueDate->duedate' AND bills_category_id IN (SELECT id FROM bills_category WHERE bills_category_status = 'Tetap')";
  			$this->db->where($where, NULL, FALSE);  
		}else{
			$this->db->where('id',$billsID);
		}


		$update['payment']=0;
		$this->db->update('bills',$update);
		
	}

	function validate()
	{

		$students_id = $this->students_m->get_student_id($this->session->userdata('active_user')->id);
		$item_details = $this->billing_m->setBilling($students_id);

		header("Content-type:application/json");
		echo json_encode(['status' => 'success']);
	}

	function finish()
	{

		$this->data['title'] = 'Billing';
		$this->data['subview'] = 'payment/finish';
		$this->data['result'] = json_decode($this->input->post('result_data'));
		$this->load->view('components/main', $this->data);
	}

	function unfinish()
	{
		echo "unfinish";
	}

	function error()
	{
		echo "unfinish";
	}
}
