<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Students extends Base_Controller {

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
		$this->data['subview'] = 'students/main';
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
		$this->load->model('students_group_m');
		$this->load->model('students_status_m');
		$data['groups'] = $this->students_group_m->all();
		$data['status'] = $this->students_status_m->all();
		$data['index'] = $this->input->post('index');

		$this->load->view('students/form', $data);
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
        $this->load->model('students_m');
		echo json_encode($this->students_m->getJson($this->input->post()));
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
				'field' => 'students_group_id',
				'label' => 'Student Group',
				'rules' => 'required'
			],
			[
				'field' => 'student_nis',
				'label' => 'Student Number',
				'rules' => 'required'
			],
			[
				'field' => 'student_name',
				'label' => 'Name',
				'rules' => 'required'
			],
			[
				'field' => 'gender',
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

		$users['name'] 			= $this->input->post('student_name');
		$users['username']  	= $this->input->post('student_nis');
		$users['email']   		= $this->input->post('email');
		$users['password']   	= $this->input->post('student_nis');
		$users['created_at']   	= date('Y-m-d H:i:s');
		$users['updated_at']   	= date('Y-m-d H:i:s');
		$users['group_id']   	= '3';
		$users['softdelete']   	= '0';
		$this->db->insert('users', $users); 

		//cari id users
		$query = $this->db->query("SELECT id FROM users WHERE username = '".$this->input->post('student_nis')."'")->row();


		$students['students_group_id'] 	= $this->input->post('students_group_id');
		$students['students_status_id'] = $this->input->post('students_status_id');
		$students['users_id'] 			= $query->id;
		$students['student_nis'] 		= $this->input->post('student_nis');
		$students['student_name'] 		= $this->input->post('student_name');
		$students['gender'] 		 	= $this->input->post('gender');
		$students['birthplace'] 		= $this->input->post('birthplace');
		$students['birthdate'] 		  	= date('Y-m-d',strtotime($this->input->post('birthdate_indo')));
		$students['email'] 		  		= $this->input->post('email');
		$students['phone_number'] 		= $this->input->post('phone_number');
		$students['address'] 		  	= $this->input->post('address');
		$students['created_at']   		= date('Y-m-d H:i:s');
		$students['updated_at']   		= date('Y-m-d H:i:s');
		$students['softdelete']   		= '0';
		$this->db->insert('students', $students); 

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
		$data['students_group_id'] 	= $this->input->post('students_group_id');
		$data['students_status_id'] = $this->input->post('students_status_id');
		$data['student_nis'] 		= $this->input->post('student_nis');
		$data['student_name'] 		= $this->input->post('student_name');
		$data['gender'] 		 	= $this->input->post('gender');
		$data['birthplace'] 		= $this->input->post('birthplace'); 
		$data['birthdate'] 		  	= date('Y-m-d',strtotime($this->input->post('birthdate_indo')));
		$data['email'] 		  		= $this->input->post('email');
		$data['phone_number'] 		= $this->input->post('phone_number');
		$data['address'] 		  	= $this->input->post('address');
		$data['updated_at']   = date('Y-m-d H:i:s');
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('students', $data); 

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
		$this->db->update('students', $data); 


		$this->db->where('id', get_field($this->input->post('id'),'students','users_id'));
		$this->db->update('users', $data); 
	}



	function json_mahasiswa()

	{

		$search = strip_tags(trim($_GET['q'])); 
		//$page = strip_tags(trim($_GET['page'])); 

		$page = '0';
		$limit=30;
		$offset=($limit*$page);

		$this->db->select('id, student_nis, student_name, students_group_id, users_id, gender');
		$this->db->where('(student_nis LIKE "%'.$search.'%" OR student_name LIKE "%'.$search.'%")');
		$this->db->order_by('student_nis');
		$mhs = $this->db->get('students',$limit,$offset)->result();

		$found=count($mhs);

		if($found > 0){

		   foreach ($mhs as $key => $value) {

			$data[] = array(

				'id' => $value->id, 
				'text' => $value->student_nis.' | '.$value->student_name,
				'foto' => get_photo(get_field($value->users_id, 'users' ,'foto'),$value->gender,'students'),
				'student_nis' => $value->student_nis,
				'student_name' => $value->student_name,
				'students_group_name' => get_field(get_field($value->students_group_id,'students_group','schools_group_id'),'schools_group','schools_group_name').' | '.
					get_field(get_field($value->students_group_id,'students_group','students_level_id'),'students_level','students_level_name').' | '.
					get_field($value->students_group_id,'students_group','students_group_name')

			);			 	
		   } 

		}else{

		   $data[] = array(

				'id' => '', 
				'text' => 'Siswa tidak ditemukan.',
				'foto' => 'Empty',
				'student_nis' => '',
				'student_name' => 'Siswa tidak ditemukan.',
				'students_group_name' => ''			
			);			 	

		}

		$this->db->where('(student_nis LIKE "%'.$search.'%" OR student_name LIKE "%'.$search.'%")');
		$tot=$this->db->count_all_results('students');
		$result['total_count'] = $tot;
		$result['items'] = $data;
		// return the result in json
		echo json_encode($result);

	}


	function biodata(){

		@extract($_POST);
		$row=$this->db->query("SELECT * FROM students WHERE id = '$key'")->row();
		$hasil = $row->id."|".$row->student_nis."|".$row->student_name."|". get_field(get_field($row->students_group_id,'students_group','schools_group_id'),'schools_group','schools_group_name').' | '.
					get_field(get_field($row->students_group_id,'students_group','students_level_id'),'students_level','students_level_name').' | '.
					get_field($row->students_group_id,'students_group','students_group_name');




		echo $hasil;

	}

}
