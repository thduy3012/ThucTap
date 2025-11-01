<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['data']) && isset($_POST['h2Content'])) {
    $data = json_decode($_POST['data'], true);
    $h2Content = $_POST['h2Content'];

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the header (H2 content) and make it bold
    $sheet->setCellValue('A1', $h2Content);
    $sheet->mergeCells('A1:E1');
    $sheet->getStyle('A1')->getFont()->setBold(true);

    // Set data starting from row 3
    $sheet->fromArray($data, NULL, 'A3');

    // Calculate the E column values as C * D
    for ($row = 4; $row <= count($data) + 2; $row++) {
        // Đọc giá trị từ cột C và E
        $valueC = $sheet->getCell('C' . $row)->getValue();
    
        $formattedValueC = $valueC ;

        // Gán giá trị đã được định dạng lại vào các ô tương ứng
        $sheet->setCellValue('C' . $row, $formattedValueC);
        $sheet->setCellValue('E' . $row, '=C' . $row . '*D' . $row);
    }

    // Calculate total
    $totalRow = count($data) + 4; // Data starts at row 3
    $sheet->setCellValue('D' . $totalRow, 'Tổng tiền:');
    $sheet->setCellValue('E' . $totalRow, '=SUM(E3:E' . ($totalRow - 1) . ')');

    // Set headers to be bold
    $headerStyle = [
        'font' => [
            'bold' => true,
        ],
    ];
    $sheet->getStyle('A2:E2')->applyFromArray($headerStyle);
    $sheet->getStyle('D' . $totalRow . ':E' . $totalRow)->applyFromArray($headerStyle);

    // Set Content-Type and file name for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="doanh_thu.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
