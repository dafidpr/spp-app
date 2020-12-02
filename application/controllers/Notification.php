<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */


	public function __construct()
	{
		parent::__construct();
		//CARI MODE PAYMENT
		$Snap = 'Production';
		$params = array('server_key' => get_field('6','settings','meta_value'), 'production' => true);

		$this->load->library('veritrans');
		$this->veritrans->config($params);
		$this->load->helper('url');	

		
	}

	public function index()
	{
		echo 'test notification handler';
		$json_result = file_get_contents('php://input');
		$result = json_decode($json_result);

		if($result){
			$notif = $this->veritrans->status($result->order_id);
		}

		error_log(print_r($result,TRUE));

	}


	public function handling()
	{
		
		//notification handler sample
		$json_result = file_get_contents('php://input');
		$result = json_decode($json_result);

		if($result){
			$notif = $this->veritrans->status($result->order_id);
		}	
		error_log(print_r($result,TRUE));
		
		$transaction 	= $notif->transaction_status;
		$type 			= $notif->payment_type;
		$order_id 		= $notif->order_id;
		$fraud 			= $notif->fraud_status;

		$data['transaction']= $transaction;
		$data['type']= $type;
		$data['updated_at']= date("Y-m-d H:i:s");
		$this->db->where('invoice_number',$order_id);
		$this->db->update("transactions",$data);

		if($transaction == 'capture' OR $transaction == 'settlement'){
			$query = $this->db->query("SELECT t2.bills_id AS BillsID FROM transactions t1 JOIN transaction_details t2
				ON t1.id = t2.transaction_id
				WHERE t1.invoice_number = '$order_id'
				AND t1.softdelete = '0' AND t2.softdelete = '0'")->result();
			foreach ($query as $key => $value) {
				$databills['status']		= 'Paid';
				$databills['lunas']		= '1';
				$databills['updated_at']	= date("Y-m-d H:i:s");
				$this->db->where('id', $value->BillsID);
				$this->db->update("bills", $databills);
			}
		}


	}
}
