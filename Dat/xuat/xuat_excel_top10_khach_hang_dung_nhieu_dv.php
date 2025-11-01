<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['data'])) {
    $data = json_decode($_POST['data'], true);
    // $h2Content = $_POST['h2Content'];

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the header (H2 content) and make it bold
    // $sheet->setCellValue('A1', $h2Content);
    // $sheet->mergeCells('A1:D1');
    // $sheet->getStyle('A1')->getFont()->setBold(true);

    // Set headers for the columns
    // $headers = ['TenGoiDichVu', 'GiaTien', 'TongSoLuong', 'ThanhTien'];
    // $sheet->fromArray($headers, NULL, 'A2');

    // Set data starting from row 3
    $sheet->fromArray($data, NULL, 'A1');

    // Calculate total
    // $totalRow = count($data) + 3; // Data starts at row 3
    // $sheet->setCellValue('C' . $totalRow, 'Tổng tiền:');
    // $sheet->setCellValue('D' . $totalRow, '=SUM(D3:D' . ($totalRow - 1) . ')');

    // Set headers to be bold
    $headerStyle = [
        'font' => [
            'bold' => true,
        ],
    ];
    $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);
    // $sheet->getStyle('C' . $totalRow . ':D' . $totalRow)->applyFromArray($headerStyle);

    // Set Content-Type and file name for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="top10_khach_hang.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
?>