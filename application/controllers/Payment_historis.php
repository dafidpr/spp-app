<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_historis extends Base_Controller {

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
		$this->data['subview'] = 'payment/main_historis';
		$this->load->view('components/main', $this->data);
	}


	public function data()
	{
		header('Content-Type: application/json');
		$this->load->model('payment_m');
		$students_id = $this->students_m->get_student_id($this->session->userdata('active_user')->id);
		echo json_encode($this->payment_m->getJson($this->input->post(), $students_id));
	}

	public function detail()
	{
		header('Content-Type: application/json');
		$this->load->model('payment_m');
		echo json_encode($this->payment_m->getJsonDetail($this->input->post()));
	}

}
