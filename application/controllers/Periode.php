<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periode extends Base_Controller {

	/**
     * Settings form
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
		$this->data['title'] = 'Periode';
		$this->data['subview'] = 'periode/main';
		$this->data['periode'] = $this->periode_m->all();

		$this->load->view('components/main', $this->data);
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
				'field' => 'periode_name',
				'label' => 'Periode',
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
     * Save Settings Changes
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function save()
	{
		$fulldate = $this->input->post('periode_name');
		$data['periode_name'] = $fulldate;
		$this->db->where('id', '1');
		$this->db->update('periode', $data);

		header("Content-type:application/json");
		echo json_encode('success');
	}

}
