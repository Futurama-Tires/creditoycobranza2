<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class FillMissingNamesImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Get the numero_de_cuenta from the current row
            $numeroDeCuenta = $row['numero_de_cuenta'] ?? null;

            // Find the corresponding name in the database
            $cliente = Cliente::where('numero_de_cuenta', $numeroDeCuenta)->first();

            // Add the row to the output with the name filled in (or "Unknown" if not found)
            $this->outputData[] = array_merge($row->toArray(), [
                'nombre' => $cliente->nombre ?? 'Unknown',
            ]);
        }
    }
}
