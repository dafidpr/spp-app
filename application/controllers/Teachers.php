<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teachers extends Base_Controller {

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
		$this->data['subview'] = 'teachers/main';
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
		$this->load->model('teachers_m');
		$data['groups'] = $this->teachers_m->all();
		$data['index'] = $this->input->post('index');
		$this->load->view('teachers/form', $data);
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
        $this->load->model('teachers_m');
		echo json_encode($this->teachers_m->getJson($this->input->post()));
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
				'field' => 'teachers_nik',
				'label' => 'Teachers Number',
				'rules' => 'required'
			],
			[
				'field' => 'teachers_name',
				'label' => 'Name',
				'rules' => 'required'
			],
			[
				'field' => 'Kelamin',
				'label' => 'Gender',
				'rules' => 'required'
			], 
			[
				'field' => 'birthplace',
				'label' => 'Birthplace',
				'rules' => 'required'
			],
			[
				'field' => 'birthdate_indo',
				'label' => 'Birthdate',
				'rules' => 'required'
			],
			[
				'field' => 'email',
				'label' => 'Email Address',
				'rules' => 'required|valid_email'
			],
			[
				'field' => 'phone_number',
				'label' => 'Phone Number',
				'rules' => 'required'
			],
			[
				'field' => 'address',
				'label' => 'Address',
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

		$teachers['teachers_nik'] 		= $this->input->post('teachers_nik');
		$teachers['teachers_name'] 		= $this->input->post('teachers_name');
		$teachers['gender'] 		 	= $this->input->post('Kelamin');
		$teachers['birthplace'] 		= $this->input->post('birthplace');
		$teachers['birthdate'] 		  	= date('Y-m-d',strtotime($this->input->post('birthdate_indo')));
		$teachers['email'] 		  		= $this->input->post('email');
		$teachers['phone_number'] 		= $this->input->post('phone_number');
		$teachers['address'] 		  	= $this->input->post('address');
		$teachers['created_at']   		= date('Y-m-d H:i:s');
		$teachers['updated_at']   		= date('Y-m-d H:i:s');
		$teachers['softdelete']   		= '0';
		$this->db->insert('teachers', $teachers); 

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
		$data['teachers_nik'] 		= $this->input->post('teachers_nik');
		$data['teachers_name'] 		= $this->input->post('teachers_name');
		$data['gender'] 		 	= $this->input->post('gender');
		$data['birthplace'] 		= $this->input->post('birthplace'); 
		$data['birthdate'] 		  	= date('Y-m-d',strtotime($this->input->post('birthdate_indo')));
		$data['email'] 		  		= $this->input->post('email');
		$data['phone_number'] 		= $this->input->post('phone_number');
		$data['address'] 		  	= $this->input->post('address');
		$data['updated_at']   = date('Y-m-d H:i:s');
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('teachers', $data); 

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
		$data['softdelete']   = '1';
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('teachers', $data); 
	}

}
