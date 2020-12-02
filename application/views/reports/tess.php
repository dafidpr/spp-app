<?php



/**

     * APLICATION INFO  : PDF Report with FPDF 1.6

     * DATE CREATED     : 21 juni 2013

   * DEVELOPED BY     : Radi Ganteng   

   */



/* setting zona waktu */ 

//date_default_timezone_set('Asia/Jakarta');



/* konstruktor halaman pdf sbb :    

   P  = Orientasinya "Potrait"

   cm = ukuran halaman dalam satuan centimeter

   A4 = Format Halaman

   

   jika ingin mensetting sendiri format halamannya, gunakan array(width, height)  

   contoh : $pdf->FPDF("P","cm", array(20, 20));  

*/


//$pdf->FPDF("L","cm","L4");
$pdf = new FPDF('L','cm',array('34','36'));
$pdf->SetAutoPageBreak(false);



/* AliasNbPages() merupakan fungsi untuk menampilkan total halaman

   di footer, nanti kita akan membuat page number dengan format : number page / total page

*/

$pdf->AliasNbPages();



// AddPage merupakan fungsi untuk membuat halaman baru

$pdf->AddPage();



// kita set marginnya dimulai dari kiri, atas, kanan. jika tidak diset, defaultnya 1 cm

$pdf->SetMargins(2,0,0.5,2);



// Setting Font : String Family, String Style, Font size 

$pdf->SetFont('Arial','B',12);



//Ngambil Identitas untuk Judul KOP

//$this->m_identitas->headerPdf(20.5);



/* -------------- Header Halaman  ------------------------------------------------*/



//ieu variable kanggo nambahan Kordinat Y




$tambahY = 1.5;

$pdf->Image('http://localhost/demo/assets/images/logo.png',2,1,3);

$pdf->setXY(0,1.2+$tambahY);

$pdf->SetFont('Arial','B',16);

$pdf->Cell(0,1,"Laporan Penerimaan",0,0,'C');


$pdf->setXY(0,2.0+$tambahY);

$pdf->SetFont('Arial','',7);

$pdf->SetFont('Arial','B',14);

$pdf->Cell(0,1,"Periode 2018-09-01 s/d 2018-09-30",0,0,'C');

$pdf->setXY(0,2.8+$tambahY);

$pdf->SetFont('Arial','',7);

/* -------------- Header Halaman  Selesai------------------------------------------------*/







$no=1;

$header=0;


$pdf->setXY(1.5,3.8+($header*0.30)+$tambahY);

$pdf->SetFont('Arial','B',12);

$pdf->Cell(1.2,1, 'NO', 1, 'C', 'C');

$pdf->Cell(4,1, 'Nomor Tagihan', 1, 'C', 'C');

$pdf->Cell(8,1, 'Nama Peserta Didik', 1, 'C', 'C');

$pdf->Cell(5.4,1, 'NIS', 1, 'C', 'C');

$pdf->Cell(3,1, 'Status', 1, 'C', 'C');

$pdf->Cell(6,1, 'Tanggal', 1, 'C', 'C');

$pdf->Cell(5.4,1, 'Jumlah', 1, 'C', 'C');


 
$row = $this->db->query("select * from users")->result();

  $posh     = 6.3;
  $tinggi   = 1;
  $l        = 0;
  $no       = 1;
  $h        = $tinggi;

    
  foreach($row as $arr) { 

    if($no%26==0&&$no!==0) {
    // jalankan method new page
    $pdf->AddPage();
    $posh   = 6.3;
    $tinggi = 1;
    $l      = 0;
    $h      = $tinggi;
 
    }




    $posy = $posh + ($tinggi*$l);     
    $l++;


    $pdf->setXY(1.5,$posy);

    $pdf->SetFont('Arial','',10);

    $pdf->Cell(1.2,$h, $no, 1, 'C', 'C');

    $pdf->Cell(4,$h, '12345678921', 1, 'C', 'C');

    $pdf->Cell(8,$h, 'Arif Hadi Saputra', 1, 'C', 'C');

    $pdf->Cell(5.4,$h, '162300004', 1, 'C', 'C');

    $pdf->Cell(3,$h, 'Paid', 1, 'C', 'C');

    $pdf->Cell(6,$h, '20-09-2018, 16.00', 1, 'C', 'C');

    $pdf->Cell(5.4,$h,'1.000.000.000', 1, 'C', 'C');


    $no++; 

    $posy = $posh + ($tinggi*$l);
  }




$pdf->Output("Transkrip_mahasiswa_"."NPM".".pdf","I");

?>

