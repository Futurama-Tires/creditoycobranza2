<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Conciliacion_pagos_factura;

class FacturasPUEOrPPDImport implements ToCollection
{
    public $newRecords = [];

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Skip header row
            if ($index === 0)
                continue;

            $record = Conciliacion_pagos_factura::updateOrCreate(
                ['nombre_factura' => $row[3]], // clave de búsqueda
                ['pue_or_ppd' => $row[13]] // campos a actualizar o crear
            );


            $this->newRecords[] = $record;
        }
    }

    public function getNewRecords()
    {
        return $this->newRecords;
    }
}
