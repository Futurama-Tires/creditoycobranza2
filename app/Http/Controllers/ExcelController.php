<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController extends Controller
{
    public function processExcel(Request $request)
    {
        // Validate file input
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        // Load the Excel file
        $file = $request->file('file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());

        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        //dd($rows);
        $clientes = Cliente::all();

        // Process rows and replace missing values in the second column
        foreach ($rows as $index => $row) {
            if ($index === 0)
                continue; // Skip header row if present

            // Check if the second column is empty
            /*if (empty($row[3])) {
                $rows[$index][3] = NULL; // Replace with 'faltante'
            }*/



            if (strpos($row[1], 'BNET') !== false) {
                // Extract the number next to "BNET"
                /*if (preg_match('/BNET\s+(\d+)/', $row[1], $matches)) {
                    $rows[$index][3] = $matches[1]; // Insert the extracted number into row[3]
                }*/

                foreach ($clientes as $cliente) {
                    if (preg_match('/BNET\s+(\d+)/', $row[1], $matches)) {
                        if ($matches[1] === $cliente->numero_de_cuenta) {
                            $rows[$index][3] = $cliente->nombre;
                        }
                    }
                }
            }
            $date = \DateTime::createFromFormat('m/d/Y', $rows[$index][0]);
            $formattedDate = $date->format('d/m/Y'); // Example: 2024-11-25

            $rows[$index][0] = $formattedDate;

            /**if ($row[1] === "x") {
                $rows[$index][3] = 'x en row1'; // Replace with 'faltante'
            }*/
        }

        // Write updated rows back to a new spreadsheet
        $newSpreadsheet = new Spreadsheet();
        $newSheet = $newSpreadsheet->getActiveSheet();
        $newSheet->fromArray($rows);

        // Save the new Excel file
        $outputFile = 'processed_file.xlsx';
        $writer = new Xlsx($newSpreadsheet);
        $filePath = storage_path($outputFile);
        $writer->save($filePath);

        // Return the file as a response for download
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
