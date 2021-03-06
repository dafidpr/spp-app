<?php
$title = 'Laporan Penerimaan';
class PDF extends FPDF
{
// Page header
function Header()
{

// $this->Image('./assets/images/logo.png',2,1,3);

$this->setXY(0,1);
$this->SetFont('Arial','B',16);
$this->Cell(0,1,"Laporan Jatuh Tempo",0,0,'C');

$this->setXY(7.5,5);
$this->SetFont('Arial','B',12);
$this->Cell(1.2,1, 'NO', 1, 'C', 'C');
$this->Cell(8,1, 'Nama Peserta Didik', 1, 'C', 'C');
$this->Cell(3,1, 'NIS', 1, 'C', 'C');
$this->Cell(4,1, 'Kelas', 1, 'C', 'C');
$this->Cell(5.4,1, 'Jumlah', 1, 'C', 'C');

}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-6);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class
$pdf = new PDF('L','cm',array('34','36'));
$pdf->AliasNbPages();
$pdf->AddPage();

$no=1;
$header=0;

$periode        = $this->db->query("SELECT periode_name FROM periode")->row();
$d              = substr($periode->periode_name, 3,2);
$m              = substr($periode->periode_name, 0,2);
$y              = substr($periode->periode_name, 6,4);
$getstartDate   = $y.'-'.$m.'-'.$d;

$dd             = substr($periode->periode_name, 16,2);
$mm             = substr($periode->periode_name, 13,2);
$yy             = substr($periode->periode_name, 19,4);
$getendDate     = $yy.'-'.$mm.'-'.$dd;

$pdf->setXY(0,2);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,1,"Periode ".$getstartDate. " s/d ". $getendDate,0,0,'C');
 
$query = $this->db->query("SELECT s.student_name, s.student_nis,s.students_group_id, SUM(b.amount) AS TotalAmount FROM
                    bills b
                JOIN students s ON b.students_id = s.id
                WHERE
                    b.softdelete = '0'
                AND s.softdelete = '0'
                AND b.lunas = '0'
                AND b.duedate BETWEEN '$getstartDate' AND '$getendDate'
                GROUP BY b.students_id
                ORDER BY b.duedate ASC")->result();

  $posh     = 6;
  $tinggi   = 1;
  $l        = 0;
  $no       = 1;
  $h        = $tinggi;

    
  foreach($query as $row) { 

    if($no%26==0&&$no!==0) {
    // jalankan method new page
    $pdf->AddPage();
    $posh   = 6;
    $tinggi = 1;
    $l      = 0;
    $h      = $tinggi;
 
    }




    $posy = $posh + ($tinggi*$l);     
    $l++;


    //perulangan transaksi
    $pdf->setXY(7.5,$posy);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(1.2,$h, $no, 1, 'C', 'C');
    $pdf->Cell(8,$h, ' '.$row->student_name, 1, 'C', 'L');
    $pdf->Cell(3,$h, $row->student_nis, 1, 'C', 'C');
    $pdf->Cell(4,$h, get_field($row->students_group_id,'students_group','students_group_name'), 1, 'C', 'C');
    $pdf->Cell(5.4,$h, 'Rp. ' . number_format($row->TotalAmount,0,',','.'), 1, 'C', 'C');




    $no++; 
    $posy = $posh + ($tinggi*$l);
  }

//cari total tagihan
$cariTotal = $this->db->query("SELECT SUM(b.amount) AS TotalAmount FROM
                    bills b
                JOIN students s ON b.students_id = s.id
                WHERE
                    b.softdelete = '0'
                AND s.softdelete = '0'
                AND b.lunas = '0'
                AND b.duedate BETWEEN '$getstartDate' AND '$getendDate'
                ORDER BY b.duedate ASC")->row();

    if(!empty($posy)){
    //perulangan transaksi
    $pdf->setXY(7.5,$posy);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(16.2,$h, 'Jumlah', 1, 'C', 'C');
    $pdf->Cell(5.4,$h, 'Rp. ' . number_format($cariTotal->TotalAmount,0,',','.'), 1, 'C', 'C');
    }

$pdf->Output("Laporan_Jatuh_Tempo_".date('Y-m-d').".pdf","I");
?>