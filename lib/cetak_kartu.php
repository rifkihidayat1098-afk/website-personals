<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('fpdf.php');
include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id = $_GET['id'];
$query = "SELECT * FROM pendaftaran_siswa WHERE id_registration = '$id'";
$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Data tidak ditemukan.");
}

$data = mysqli_fetch_assoc($result);

// Inisialisasi PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// ==== HEADER ==== //
// Logo sekolah di kiri atas
$logoPath = '../assets/img/logo.png'; // pastikan path relatif benar
if (file_exists($logoPath)) {
    $pdf->Image($logoPath, 5, 5, 40); // X=10, Y=10, lebar 25
}

// Nama dan alamat sekolah di tengah
$pdf->SetFont('Helvetica', 'B', 14);
$pdf->Cell(0, 7, 'SMAN 2 SIMEULUE BARAT', 0, 1, 'C');
$pdf->SetFont('Helvetica', '', 10);
$pdf->Cell(0, 7, 'Jl. Nusantara No. 02 Desa Sigulai Kec. Simeulue Barat', 0, 1, 'C');
$pdf->Cell(0, 4, 'Kode Pos 23892', 0, 1, 'C');

// Garis bawah
$pdf->Ln(3);
$pdf->SetLineWidth(0.5);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(10);

// ==== JUDUL ====
$pdf->SetFont('Helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Kartu Pendaftaran Siswa', 0, 1, 'C');
$pdf->Ln(5);

// ==== FOTO SISWA DI KIRI ==== //
$fotoPath = '../uploads/' . $data['pas_photo'];
if (!empty($data['pas_photo']) && file_exists($fotoPath)) {
    $pdf->Image($fotoPath, 10, 60, 40, 50); // kiri atas (X=10, Y=60), ukuran 40x50
}

// Geser posisi X untuk data teks
$pdf->SetY(56);
$pdf->SetX(60); // Mulai setelah foto
$pdf->SetFont('Helvetica', '', 12);

// ==== DATA SISWA ==== //
$pdf->Cell(50, 10, 'Kode Pendaftaran', 0, 0);
$pdf->Cell(0, 10, ': ' . $data['code_registration'], 0, 1);

$pdf->SetX(60);
$pdf->Cell(50, 10, 'Nama Lengkap', 0, 0);
$pdf->Cell(0, 10, ': ' . $data['full_name'], 0, 1);

$pdf->SetX(60);
$pdf->Cell(50, 10, 'Tempat, Tgl Lahir', 0, 0);
$pdf->Cell(0, 10, ': ' . $data['place_birthdate'] . ', ' . $data['birthdate'], 0, 1);

$pdf->SetX(60);
$pdf->Cell(50, 10, 'Alamat', 0, 0);
$pdf->MultiCell(0, 10, ': ' . $data['address'], 0, 1);

$pdf->SetX(60);
$pdf->Cell(50, 10, 'No HP', 0, 0);
$pdf->Cell(0, 10, ': ' . $data['numphone'], 0, 1);

$pdf->SetX(60);
$pdf->Cell(50, 10, 'Asal Sekolah', 0, 0);
$pdf->Cell(0, 10, ': ' . $data['asal_school'], 0, 1);

$pdf->SetX(60);
$pdf->Cell(50, 10, 'Status', 0, 0);
$pdf->Cell(0, 10, ': ' . strtoupper($data['status']), 0, 1);

$pdf->SetX(60);
$pdf->Cell(50, 10, 'Tanggal Pendaftaran', 0, 0);
$pdf->Cell(0, 10, ': ' . strtoupper($data['tanggal_daftar']), 0, 1);

$pdf->Output();
?>