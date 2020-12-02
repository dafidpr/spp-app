<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_Category extends Base_Controller {

	/**
     * List of Users
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
		$this->data['subview'] = 'billing_category/main';
		$this->load->view('components/main', $this->data);
	}

	/**
     * User Form
     *
     * @access 	public
     * @param 	
     * @return 	view
     */

	public function form()
	{
		$data['index'] = $this->input->post('index');
		$this->load->view('billing_category/form', $data);
	}

	/**
     * Datagrid Data
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	public function data()
	{
        header('Content-Type: application/json');
        $this->load->model('billing_category_m');
		echo json_encode($this->billing_category_m->getJson($this->input->post()));
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
				'field' => 'bills_category_name',
				'label' => 'Category Name',
				'rules' => 'required'
			],
			[
				'field' => 'bills_category_status',
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
     * Create a New User
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function create()
	{
		$data['bills_category_name'] 		  = $this->input->post('bills_category_name');
		$data['bills_category_status']   	  = $this->input->post('bills_category_status');
		$data['created_at']   = date('Y-m-d H:i:s');
		$data['updated_at']   = date('Y-m-d H:i:s');
		$this->db->insert('bills_category', $data); 

		header('Content-Type: application/json');
    	echo json_encode('success');
	}

	/**
     * Update Existing User
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function update()
	{
		$data['bills_category_name'] 		  = $this->input->post('bills_category_name');
		$data['bills_category_status']   	  = $this->input->post('bills_category_status');
		$data['updated_at']   = date('Y-m-d H:i:s');
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('bills_category', $data); 

		header('Content-Type: application/json');
    	echo json_encode('success');
	}

	/**
     * Delete a User
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function delete()
	{
		$data['softdelete']   			= '1';
		$data['updated_at']   			= date('Y-m-d H:i:s');
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('bills_category', $data); 
	}

}
