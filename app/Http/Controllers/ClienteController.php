<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Cliente;
use App\Imports\ClientesImport;

class ClienteController extends Controller
{
    public function showImportForm()
    {
        return view('clientes.importar');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Procesar el archivo Excel
        Excel::import(new ClientesImport, $request->file('file'));

        return redirect()->route('clientes.importar')->with('success', 'Clientes importados correctamente.');
    }
}
