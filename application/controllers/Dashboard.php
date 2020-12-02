<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Base_Controller {
	
	/**
     * Dashboard
     *
     * @access  public
     * @param   
     * @return  view
     */
	
	public function index()
	{
		
		if($this->session->userdata('active_user')->group_id == '3'){

		//cari tagihan
		$students_id = $this->students_m->get_student_id($this->session->userdata('active_user')->id);
		$this->data['Lastsettlement'] = $this->db->select('students_id, invoice_number, date, amount')
										->from('transactions')
										->where('transaction', 'Selesai')
										->where('softdelete', '0')
										->where('students_id', $students_id)
										->order_by('id', 'desc')
										->limit('5','0')
										->get()->result();



		//Cari Last settlement
		$query2 = $this->db->query("SELECT amount FROM transactions WHERE transaction = 'Selesai' AND softdelete = '0' AND students_id = '$students_id' ORDER BY id desc LIMIT 1")->row();
		if(!empty($query2)){
			$this->data['Lastsettlementamount'] = $query2->amount;
		}else{
			$this->data['Lastsettlementamount'] = 0;
		}

		$getStartDate 	= date('Y-m-01');
		$getEndDate 	= date('Y-m-31');

		//Cari Duedate
		$this->data['Duedate'] = $this->db->query("SELECT SUM(amount) AS Amount FROM bills WHERE duedate BETWEEN '$getStartDate' AND '$getEndDate' AND lunas = '0' AND softdelete = '0' AND students_id = '$students_id'")->row();

		//Cari Outstanding
		$this->data['Outstanding'] = $this->db->query("SELECT SUM(amount) AS Amount FROM bills WHERE duedate < '$getStartDate' AND lunas = '0' AND softdelete = '0' AND students_id = '$students_id'")->row();

		//Cari TotalUser
		$this->data['Payables'] = $this->db->query("SELECT SUM(amount) AS Amount FROM bills WHERE duedate > '$getEndDate' AND lunas = '0' AND softdelete = '0' AND students_id = '$students_id'")->row();



		//Cari Total Data
		$query3 = $this->db->query("SELECT * FROM transactions WHERE transaction = 'Selesai' AND softdelete = '0' AND students_id = '$students_id'")->num_rows();
		if($query3 >= 5){
			$offset = $query3 - 5;
		}else{
			$offset = 0;	
		}
		
		//Cari Transaction Volume
		$this->data['TransactionVolume'] = $this->db->query("SELECT SUM(amount) AS TotalVolume, DATE_FORMAT(date, '%Y%-%m%-%d') AS DateFormat FROM transactions WHERE transaction = 'Selesai' AND softdelete = '0' AND students_id = '$students_id' GROUP BY DateFormat ORDER BY DateFormat ASC LIMIT 5 OFFSET $offset")->result();

		$this->data['title'] = 'School Payment '. get_field('1','settings','meta_value');
		$this->data['subview'] = 'dashboard/main_student';


	}else{
		$this->data['Lastsettlement'] = $this->db->select('students_id, amount')
										->from('transactions')
										->where('transaction', 'Selesai')
										->where('softdelete', '0')
										->order_by('id', 'desc')
										->limit('5','0')
										->get()->result();


		$getStartDate 	= date('Y-m-01');
		$getEndDate 	= date('Y-m-31');
		//Cari Total Volume
		$query1 = $this->db->query("SELECT SUM(amount) AS TotalVolume FROM transactions WHERE date BETWEEN '$getStartDate' AND '$getEndDate' AND transaction = 'Selesai' AND softdelete = '0'")->row();


		if(!empty($query1)){
			$this->data['TotalVolume'] = $query1->TotalVolume;
		}else{
			$this->data['TotalVolume'] = 0;
		}


		//Cari Total Transaction
		$this->data['TotalTransaction'] = $this->db->query("SELECT * FROM transactions WHERE date BETWEEN '$getStartDate' AND '$getEndDate' AND transaction = 'Selesai' AND softdelete = '0'")->num_rows();

		//Cari Last settlement
		$query2 = $this->db->query("SELECT 	SUM(amount) AS Lastsettlementamount, DATE_FORMAT(date, '%Y%-%m%-%d') AS DateFormat FROM transactions WHERE 	TRANSACTION = 'Selesai' AND softdelete = '0' GROUP BY DateFormat DESC LIMIT 1")->row();

		if(!empty($query2)){
			$this->data['Lastsettlementamount'] = $query2->Lastsettlementamount;
		}else{
			$this->data['Lastsettlementamount'] = 0;
		}

		//Cari TotalUser
		$this->data['TotalUser'] = $this->db->query("SELECT * FROM transactions WHERE date BETWEEN '$getStartDate' AND '$getEndDate' AND transaction = 'Selesai' AND softdelete = '0' GROUP BY students_id")->num_rows();

		//Cari Total Data
		$query3 = $this->db->query("SELECT DATE_FORMAT(date, '%Y%-%m%-%d') AS DateFormat FROM transactions
					WHERE date LIKE '%2018-08%' AND TRANSACTION = 'Selesai' AND softdelete = '0' GROUP BY dateformat")->num_rows();

		if($query3 >= 5){
			$offset = $query3 - 5;
		}else{
			$offset = 0;	
		}


		//Cari Transaction Volume
		$this->data['TransactionVolume'] = $this->db->query("SELECT SUM(amount) AS TotalVolume, DATE_FORMAT(date, '%Y%-%m%-%d') AS DateFormat FROM transactions WHERE transaction = 'Selesai' AND softdelete = '0' GROUP BY dateformat ORDER BY DateFormat ASC LIMIT 5 OFFSET $offset")->result();

		$this->data['title'] = 'School Payment '. get_field('1','settings','meta_value');
		$this->data['subview'] = 'dashboard/main';		
	}


		$this->load->view('components/main', $this->data);
	}
}
