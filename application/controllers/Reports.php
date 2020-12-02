<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Base_Controller {

	/**
     * Transaction List
     *
     * @access  public
     * @param   
     * @return  view
     */

	function __construct() {
		parent::__construct();

		if($this->session->userdata('active_user')->group_id == '3'){
			redirect('auth/logout');
		}		


		$this->getstartDate 		= $this->periode_m->getstartDate(get_field('1','periode','periode_name'));
		$this->getendDate			= $this->periode_m->getendDate(get_field('1','periode','periode_name'));
		$this->getstartDatetime 	= $this->periode_m->getstartDatetime(get_field('1','periode','periode_name'));
		$this->getendDatetime		= $this->periode_m->getendDatetime(get_field('1','periode','periode_name'));
	}


	
	public function index()
	{
		$this->data['title'] = 'Transaction';
		$this->data['subview'] = 'reports/main';

		$this->data['TotalVolume']				=  $this->db->query("SELECT SUM(amount) AS Total FROM transactions WHERE transaction = 'settlement' AND date LIKE '%2018-05%'")->row();
		$this->data['TotalTransaction']			= '10';
		$this->data['Totaluser']				= '10';
		$this->data['Lastsettlementamount']		= '1000000';

		$this->load->view('components/main', $this->data);
	}

	/**
     * Invoice Details
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function reports_v1()
	{
		$this->data['title'] = 'Laporan Penerimaan Harian';
		$this->data['subview'] = 'reports/reports_v1';
		$this->load->view('components/main', $this->data);
	}
	public function reports_v2()
	{
		$this->data['title'] = 'Laporan Penerimaan Bulanan';
		$this->data['subview'] = 'reports/reports_v2';
		$this->load->view('components/main', $this->data);
	}
	public function reports_v3()
	{
		$this->data['title'] = 'Laporan Penerimaan Periode '.$this->getstartDate.' s/d '.$this->getendDate;
		$this->data['subview'] = 'reports/reports_v3';
		$this->load->view('components/main', $this->data);
	}

	public function reports_v3_pdf()
	{
		$this->load->library("Fpdf");
		$this->load->view('reports/reports_v3_pdf', $this->data);
	}

	public function reports_v4()
	{
		$this->data['title'] = 'Tunggakan Tanggal ' .$this->getstartDate;
		$this->data['subview'] = 'reports/reports_v4';
		$this->load->view('components/main', $this->data);
	}

		public function reports_v4_pdf()
	{
		$this->load->library("Fpdf");
		$this->load->view('reports/reports_v4_pdf');
	}

	public function reports_v5()
	{
		$this->data['title'] = 'Piutang Tanggal ' .$this->getendDate;
		$this->data['subview'] = 'reports/reports_v5';
		$this->load->view('components/main', $this->data);
	}

	public function reports_v5_pdf()
	{
		$this->load->library("Fpdf");
		$this->load->view('reports/reports_v5_pdf');
	}

	public function reports_v6()
	{
		$this->data['title'] = 'Jatuh Tempo Periode '.$this->getstartDate.' s/d '.$this->getendDate;
		$this->data['subview'] = 'reports/reports_v6';
		$this->load->view('components/main', $this->data);
	}

	public function reports_v6_pdf()
	{
		$this->load->library("Fpdf");
		$this->load->view('reports/reports_v6_pdf');
	}

	/**
     * Datagrid Data
     *
     * @access  public
     * @param   
     * @return  json(array)
     */
	public function data_reports_v1()
	{
		header('Content-Type: application/json');
		$this->load->model('reports_m');
		echo json_encode($this->reports_m->getJson_v1($this->input->post()));
	}

	public function data_reports_v2()
	{
		header('Content-Type: application/json');
		$this->load->model('reports_m');
		echo json_encode($this->reports_m->getJson_v2($this->input->post()));
	}

	public function data_reports_v3()
	{
		header('Content-Type: application/json');
		$this->load->model('reports_m');
		echo json_encode($this->reports_m->getJson_v3($this->input->post(),$this->getstartDatetime,$this->getendDatetime));
	}

	public function data_reports_v4()
	{
		header('Content-Type: application/json');
		$this->load->model('reports_m');
		echo json_encode($this->reports_m->getJson_v4($this->input->post(),$this->getstartDate));
	}			

	public function data_reports_v5()
	{
		header('Content-Type: application/json');
		$this->load->model('reports_m');
		echo json_encode($this->reports_m->getJson_v5($this->input->post(),$this->getendDate));
	}

	public function data_reports_v6()
	{
		header('Content-Type: application/json');
		$this->load->model('reports_m');
		echo json_encode($this->reports_m->getJson_v6($this->input->post(),$this->getstartDate,$this->getendDate));
	}
	/**
     * Datagrid Data
     *
     * @access  public
     * @param   
     * @return  json(array)
     */

	public function detail_reports_v1()
	{
		header('Content-Type: application/json');
		$this->load->model('reports_m');
		echo json_encode($this->reports_m->detail_reports_v1($this->input->post()));
	}

	public function detail_reports_v2()
	{
		header('Content-Type: application/json');
		$this->load->model('reports_m');
		echo json_encode($this->reports_m->detail_reports_v2($this->input->post()));
	}

	public function detail_reports_v3()
	{
		header('Content-Type: application/json');
		$this->load->model('reports_m');
		echo json_encode($this->reports_m->detail_reports_v3($this->input->post(),$this->getstartDatetime,$this->getendDatetime));
	}

	public function detail_reports_v4()
	{
		//laporan tunggakan
		header('Content-Type: application/json');
		$this->load->model('reports_m');
		echo json_encode($this->reports_m->detail_reports_v4($this->input->post(),$this->getstartDate));
	}


	public function detail_reports_v5()
	{
		//laporan piutang jangka panjang
		header('Content-Type: application/json');
		$this->load->model('reports_m');
		echo json_encode($this->reports_m->detail_reports_v5($this->input->post(),$this->getendDate));
	}

	public function detail_reports_v6()
	{
		//laporan jatuh tempo
		header('Content-Type: application/json');
		$this->load->model('reports_m');
		echo json_encode($this->reports_m->detail_reports_v6($this->input->post(),$this->getstartDate,$this->getendDate));
	}

}
