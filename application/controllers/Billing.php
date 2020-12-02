<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing extends Base_Controller {

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

		if($this->session->userdata('active_user')->group_id == '3'){
			redirect('auth/logout');
		}
	}
	
	public function index()
	{	
		$this->data['title'] = 'School Payment '. get_field('1','settings','meta_value');
		$this->data['subview'] = 'billing/main';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Product Form
     *
     * @access 	public
     * @param 	
     * @return 	view
     */

	public function form($id)
	{
		$this->load->model('billing_m');
		$data['category'] = $this->billing_category_m->all();
		$data['index'] = $this->input->post('index');
		$data['students_id'] = $id;

		$this->load->view('billing/form', $data);
	}

	/**
     * Datagrid Data
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	public function data($id)
	{
        header('Content-Type: application/json');
		echo json_encode($this->billing_m->getJson($this->input->post(), $id));
	}

	/**
     * Validate Input
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

    public function validate()
	{
		$rules = [
			[
				'field' => 'students_id',
				'label' => 'students_id',
				'rules' => 'required'
			],
			[
				'field' => 'bills_category_id',
				'label' => 'Category',
				'rules' => 'required'
			],
			[
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'required'
			],
			[
				'field' => 'amount',
				'label' => 'Amount',
				'rules' => 'required|is_natural_no_zero'
			],
			[
				'field' => 'duedate',
				'label' => 'Duedate',
				'rules' => 'required'
			],
			[
				'field' => 'status',
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

	/**
     * Create Update Action
     *
     * @access 	public
     * @param 	
     * @return 	method
     */

	public function action()
	{
		if (!$this->input->post('id')) {
			$this->create();
		} else {
			$this->update();
		}
	}

	/**
     * Create a New Product
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function create()
	{
		$data['students_id'] 			= $this->input->post('students_id');
		$data['bills_category_id']   	= $this->input->post('bills_category_id');
		$data['name']   				= $this->input->post('name');
		$data['amount']   				= $this->input->post('amount');
		$data['duedate']   				= date('Y-m-d',strtotime($this->input->post('duedate')));
		$data['note']   				= $this->input->post('note');
		$data['payment']   				= '0';
		$data['status']   				= $this->input->post('status');
		$data['lunas']   				= '0';
		$data['softdelete']   			= '0';
		$data['users_id']   			= $this->session->userdata('active_user')->id;
		$data['created_at']   			= date('Y-m-d H:i:s');
		$data['updated_at']   			= date('Y-m-d H:i:s');
		$this->db->insert('bills', $data); 

		header('Content-Type: application/json');
    	echo json_encode('success');
	}

	/**
     * Update Existing Product
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function update()
	{
		$data['students_id'] 			= $this->input->post('students_id');
		$data['bills_category_id']  	= $this->input->post('bills_category_id');
		$data['name']   				= $this->input->post('name');
		$data['amount']   				= $this->input->post('amount');
		$data['duedate']   				= date('Y-m-d',strtotime($this->input->post('duedate')));
		$data['note']   				= $this->input->post('note');
		$data['payment']   				= '0';
		$data['status']   				= $this->input->post('status');
		$data['lunas']   				= '0';
		$data['softdelete']   			= '0';
		$data['users_id']   			= $this->session->userdata('active_user')->id;
		$data['updated_at']   			= date('Y-m-d H:i:s');
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('bills', $data); 

		header('Content-Type: application/json');
    	echo json_encode('success');
	}

	/**
     * Delete a Product
     *
     * @access 	public
     * @param 	
     * @return 	redirect
     */

	public function delete()
	{
		$data['users_id']   			= $this->session->userdata('active_user')->id;
		$data['softdelete']   			= '1';
		$data['updated_at']   			= date('Y-m-d H:i:s');
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('bills', $data); 
	}

	function search_bills()
	{
		@extract($_POST);
		$this->data['ID'] = $ID;
		$this->data['bills'] = $this->db->select('b.*, c.bills_category_name, c.bills_category_status')
										->from('bills b')
										->where('b.students_id', $ID)
										->where('b.lunas', '0')
										->where('b.softdelete', '0')
										->join('bills_category as c', 'b.bills_category_id = c.id', 'left')
										->get()->result();
		$this->load->view('billing/search_bills', $this->data);
	}
}
