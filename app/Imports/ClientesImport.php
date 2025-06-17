<?php
namespace App\Imports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {            
        //dd($row);

        if (empty($row['cliente'])) {
            return null; // Skip this row
        }


        $numeroDeCuenta = null;
        if (isset($row["concepto_referencia"])) {
            preg_match('/BNET\s+(\d+)/', $row["concepto_referencia"], $matches);
            if (!empty($matches[1])) {
                $numeroDeCuenta = $matches[1]; // The extracted number
            }
        }

        // Skip the row if no match was found
        if ($numeroDeCuenta === null) {
            return null;
        }

        if (Cliente::where('numero_de_cuenta', $numeroDeCuenta)->exists()) {
            return null; // Skip this row if the numeroDeCuenta is already in the database
        }

        return new Cliente([
            'numero_de_cuenta' => $numeroDeCuenta , // Match the header exactly
            'nombre' => $row['cliente'], // Assuming 'nombre' is the header for the 'nombre' column
        ]);
    }
}
