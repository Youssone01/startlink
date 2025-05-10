<?php
require('../../fpdf/fpdf.php');
include __DIR__ . '../../../Model/admin/get_reservations.php';

class PDF extends FPDF
{
    // Header
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Reservation List', 0, 1, 'C');
        $this->Ln(5); // Add space
    }

    // Footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);

// Table Header
$pdf->Cell(10, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Course Title', 1);
$pdf->Cell(30, 10, 'Full Name', 1);
$pdf->Cell(50, 10, 'Email', 1);
$pdf->Cell(25, 10, 'Phone', 1);
$pdf->Cell(25, 10, 'Start Date', 1);
$pdf->Cell(20, 10, 'Time', 1);;
$pdf->Ln();

// Table Body
$pdf->SetFont('Arial', '', 10);
$reservations = get_reservations();

if ($reservations !== null) {
    while ($row = $reservations->fetch(PDO::FETCH_ASSOC)) {
        $pdf->Cell(10, 10, $row['id'], 1);
        $pdf->Cell(40, 10, $row['course_title'], 1);
        $pdf->Cell(30, 10, $row['name'], 1);
        $pdf->Cell(50, 10, $row['email'], 1);
        $pdf->Cell(25, 10, $row['phone'], 1);
        $pdf->Cell(25, 10, date("d-m-Y", strtotime($row['start_date'])), 1);
        $pdf->Cell(20, 10, date("H:i", strtotime($row['time'])), 1);

        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No reservations found.', 1, 1, 'C');
}

// Output the PDF
$pdf->Output();
exit;
