<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Snap extends Base_Controller {
	
	function __construct()
	{
		parent::__construct();

		//CARI MODE PAYMENT
		$Snap = 'Production';
		$params = array('server_key' => get_field('6','settings','meta_value'), 'production' => true);

		$this->load->library('midtrans');
		$this->midtrans->config($params);
		$this->load->helper('url');	
	}


	public function index()
	{
		//CARI MODE PAYMENT
		$this->data['title'] = 'School Payment '. get_field('1','settings','meta_value');
		$this->data['subview'] = 'snap/main';
		$this->data['Snap'] 	= 'Production';

		//CARI NOMOR INVOICE
		$students_id = $this->students_m->get_student_id($this->session->userdata('active_user')->id);
		$sql = $this->db->query("SELECT invoice_number, transaction, amount FROM transactions
			WHERE students_id = '$students_id' AND transaction = 'pending' ORDER BY id DESC LIMIT 1")->row();

		$this->data['order_id'] 		= $sql->invoice_number;
		$this->data['gross_amount']		= $sql->amount;
		$this->data['transaction'] 		= $sql->transaction;

		$this->load->view('components/main', $this->data);
	}


	public function token()
	{

		$students_id = $this->students_m->get_student_id($this->session->userdata('active_user')->id);

		//CARI NOMOR INVOICE
		$sql=$this->db->query("SELECT * FROM transactions
			WHERE students_id = '$students_id' AND transaction = 'pending' ORDER BY id DESC LIMIT 1")->row();

		$Email = get_field($students_id,'students','email');
		$EmailCustomer = str_replace(' ','',$Email);
		
		// Required
		$transaction_details = array(
			'order_id' => $sql->invoice_number,
		  'gross_amount' => $sql->amount, // no decimal allowed for creditcard
		  );

		$sql2=$this->db->query("SELECT * FROM transaction_details WHERE transaction_id = '$sql->id' ORDER BY id ASC")->result();
		$sql3=$this->db->query("SELECT * FROM transaction_details WHERE transaction_id = '$sql->id'")->num_rows();
		// Optional
		$no=0;
		foreach ($sql2 as $row) {	
			$no++;

			$billsID = $row->bills_id;
			$items[$no] = array(
				'id' 		=> get_field($billsID,'bills','id'),
				'price' 	=> get_field($billsID,'bills','amount'),
				'quantity' 	=> 1,
				'name' 		=> get_field(get_field($billsID,'bills','bills_category_id'),'bills_category','bills_category_name')." - ".get_field($billsID,'bills','name')
				);

		}
		// Optional
		$item_details = getMidtrans($items, $sql3);

		// Optional
		$billing_address = array(
			'first_name'    => get_field($students_id,'students','student_name'),
			'last_name'     => get_field($students_id,'students','student_nis'),
			'address'       => get_field($students_id,'students','address'),
			'city'          => "Batam",
			'postal_code'   => "",
			'phone'         => get_field($students_id,'students','phone_number'),
			'country_code'  => 'IDN'
			);

		// Optional
		$shipping_address = array(
			'first_name'    => get_field($students_id,'students','student_name'),
			'last_name'     => get_field($students_id,'students','student_nis'),
			'address'       => get_field($students_id,'students','address'),
			'city'          => "Batam",
			'postal_code'   => "",
			'phone'         => get_field($students_id,'students','phone_number'),
			'country_code'  => 'IDN'
			);

		// Optional
		$customer_details = array(
			'first_name'    	=> get_field($students_id,'students','student_name'),
			'last_name'     	=> get_field($students_id,'students','student_nis'),
			'email'         	=> $EmailCustomer,
			'phone'         	=> get_field($students_id,'students','phone_number'),
			'billing_address'  	=> $billing_address,
			'shipping_address' 	=> $shipping_address
			);

		// Fill transaction details
		$transaction = array(
			'transaction_details' 	=> $transaction_details,
			'customer_details' 		=> $customer_details,
			'item_details' 			=> $item_details,
			);
		//error_log(json_encode($transaction));
		$snapToken = $this->midtrans->getSnapToken($transaction);
		error_log($snapToken);
		echo $snapToken;
	}
}
