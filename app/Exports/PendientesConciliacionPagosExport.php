<?php

namespace App\Exports;

use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray; // Interface to export an array of data.
use Maatwebsite\Excel\Concerns\WithTitle; // Interface to define the sheet title.
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Makes columns auto-size to fit content.
use Maatwebsite\Excel\Concerns\WithStyles; // Allows us to apply styles (like bold) to specific rows.
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // Used in WithStyles to target the worksheet.
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use App\Models\Pagos; // adjust the model name/namespace if needed
use App\Models\Fut_historial_pagos; // adjust the model name/namespace if needed
use App\Models\Conciliacion_pagos_info_primaria; // adjust the model name/namespace if needed
use App\Models\Conciliacion_pagos_factura; // adjust the model name/namespace if needed
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PendientesConciliacionPagosExport implements FromArray, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */



    public function array(): array
    {
        //eliminar registros sin pago
        Pagos::whereNull('pago')
            ->orWhere('pago', "")
            ->delete();



        /* ***************************************        Conciliacion de fecha, monto y clave alfa numerica          *************************************** */
        $bancos = Pagos::orderBy('abonos', 'DESC')
            ->orderBy('fecha', 'DESC')
            ->orderBy('pago', 'ASC')
            ->get();

        $netsuite = Fut_historial_pagos::where('cuenta', 'like', '102.01.19%')
            ->orderBy('importe', 'ASC')
            ->orderBy('fecha', 'DESC')
            ->orderBy('numero_documento', 'ASC')
            ->get();
        //dd($netsuite->count());
        $data = [];
        $data[] = ['Pagos Conciliados'];

        $conciliados = [];
        $no_conciliados_bancos = [];
        $conciliados_parciales = [];
        $final_no_conciliados_bancos = [];
        $no_conciliados_netsuite = $netsuite->toArray(); // Copia inicial
        $final_no_conciliados_netsuite = [];

        foreach ($bancos as $b) {
            $encontrado = false;
            foreach ($netsuite as $key => $n) {
                if ($b->fecha == $this->extraerFechaDeTexto($n->nota) && abs($b->abonos) == abs($n->importe) && $b->pago == $n->numero_documento) {
                    $conciliados[] = [
                        '' => '',
                        'Fecha Bancos' => $b->fecha,
                        'Concepto_referencia Bancos' => $b->concepto_referencia,
                        'Abonos Bancos' => $b->abonos,
                        'Cliente Bancos' => $b->cliente,
                        'Pago Bancos' => $b->pago,
                        'Forma de Pago Bancos' => $b->forma_pago,
                        'Banco' => $b->banco,
                        'FIDP' => $b->fidp,


                        'Fecha Netsuite' => $n->fecha,
                        'Creado Desde Netsuite' => preg_replace('/^.*#/', '', $n->creado_desde),
                        'Numero Documento Netsuite' => $n->numero_documento,
                        'Nombre Netsuite' => $n->nombre,
                        'Cuenta Netsuite' => $n->cuenta,
                        'Nota Netsuite' => $n->nota,
                        'Importe Netsuite' => $n->importe,
                        'Estado Netsuite' => $n->estado,
                        'Folio SAT Netsuite' => $n->folio_SAT_1,
                        'RFC Netsuite' => $n->rfc_1,
                        'Forma de Pago Netsuite' => $n->forma_pago,
                        'Metodo de Pago (SAT) Netsuite' => $n->metodo_pago,
                        'Uso del CFDi Netsuite' => $n->uso_del_cfdi,
                        'Creado Por Netsuite' => $n->creado_po,
                        'Representante de Ventas Netsuite' => $n->representante_ventas,
                        'Metodo de Pago Netsuite' => $n->metodo_pago_2,
                        'RFC 2 Netsuite' => $n->rfc_2,
                        'Uso CFDi para el Pago Netsuite' => $n->uso_de_cfdi_para_pago,
                    ];
                    unset($netsuite[$key]); // Evita reusar este registro
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                $no_conciliados_bancos[] = $b;
            }
        }

        // netsuite ahora solo contiene no conciliados
        $no_conciliados_netsuite = $netsuite;

        // Segunda pasada de conciliación (2 de 3 condiciones)
        foreach ($no_conciliados_bancos as $b) {
            $encontrado = false;
            foreach ($no_conciliados_netsuite as $key => $n) {
                $condiciones_cumplidas = 0;
                $condiciones_falladas = [];

                // Verificar cada condición
                if ($b->fecha == $this->extraerFechaDeTexto($n->nota)) {
                    $condiciones_cumplidas++;
                } else {
                    $fechaExtraida = $this->extraerFechaDeTexto($n->nota);

                    if ($fechaExtraida !== "sin coincidencias") {
                        $condiciones_falladas[] = 'Error Fecha: ' . \Carbon\Carbon::createFromFormat('Y-m-d', $fechaExtraida)->format('d/m/Y');
                    } else {
                        $condiciones_falladas[] = 'Error Fecha: no se pudo extraer una fecha válida';
                    }
                }

                if (abs($b->abonos) == abs($n->importe)) {
                    $condiciones_cumplidas++;
                } else {
                    $dif = number_format($n->importe + $b->abonos, 2);
                    $condiciones_falladas[] = 'Error Monto, Diferencia: ' . $dif;
                }

                if ($b->pago == $n->numero_documento) {
                    $condiciones_cumplidas++;
                } else {
                    $condiciones_falladas[] = 'Error Documento';
                }

                // Si se cumplen 2 de 3 condiciones
                if ($condiciones_cumplidas >= 2) {
                    $conciliados_parciales[] = [
                        '' => '',
                        'Fecha Bancos' => $b->fecha,
                        'Concepto_referencia Bancos' => $b->concepto_referencia,
                        'Abonos Bancos' => $b->abonos,
                        'Cliente Bancos' => $b->cliente,
                        'Pago Bancos' => $b->pago,
                        'Forma de Pago Bancos' => $b->forma_pago,
                        'Banco' => $b->banco,
                        'FIDP' => $b->fidp,

                        'Fecha Netsuite' => $n->fecha,
                        'Creado Desde Netsuite' => preg_replace('/^.*#/', '', $n->creado_desde),
                        'Numero Documento Netsuite' => $n->numero_documento,
                        'Nombre Netsuite' => $n->nombre,
                        'Cuenta Netsuite' => $n->cuenta,
                        'Nota Netsuite' => $n->nota,
                        'Importe Netsuite' => $n->importe,
                        'Estado Netsuite' => $n->estado,
                        'Folio SAT Netsuite' => $n->folio_SAT_1,
                        'RFC Netsuite' => $n->rfc_1,
                        'Forma de Pago Netsuite' => $n->forma_pago,
                        'Metodo de Pago (SAT) Netsuite' => $n->metodo_pago,
                        'Uso del CFDi Netsuite' => $n->uso_del_cfdi,
                        'Creado Por Netsuite' => $n->creado_po,
                        'Representante de Ventas Netsuite' => $n->representante_ventas,
                        'Metodo de Pago Netsuite' => $n->metodo_pago_2,
                        'RFC 2 Netsuite' => $n->rfc_2,
                        'Uso CFDi para el Pago Netsuite' => $n->uso_de_cfdi_para_pago,

                        //'Condiciones Cumplidas' => $condiciones_cumplidas,
                        'Condicion Fallada' => implode(', ', $condiciones_falladas)
                    ];

                    unset($no_conciliados_netsuite[$key]); // Evita reusar este registro
                    $encontrado = true;
                    break;
                }
            }

            if (!$encontrado) {
                $final_no_conciliados_bancos[] = $b;
            }
        }

        // Los que quedan en no_conciliados_netsuite son los que no coincidieron en ninguna condición
        $final_no_conciliados_netsuite = $no_conciliados_netsuite;

        //$data[] = ['conciliados'];



        /** REGISTROS CON 3 COINCIDENCIAS EN FECHA, MONTO Y CLAVE */

        if (count($conciliados)) {
            $header = array_keys($conciliados[0]);

            $data[] = $header;
            foreach ($conciliados as $row) {
                $data[] = array_values($row);
            }
        } else {
            $data[] = ['No se Encontraron Registros'];
        }
        //$data[] = ['parciales'];

        /** REGISTROS CON SOLO 2 COINCIDENCIAS EN FECHA, MONTO Y CLAVE */

        if (count($conciliados_parciales)) {
            //$data[] = array_keys($conciliados_parciales[0]);
            foreach ($conciliados_parciales as $row) {
                $data[] = array_values($row);
            }
        } else {
            //$data[] = ['No se Encontraron Registros'];
        }

        //$data[] = [];
        // Recorrer el arreglo para agregar la validacion de cuenta

        $facturaNombres = collect($data)->skip(2)->pluck(10)->unique()->filter()->values();

        // 2. Query only once for all those facturas
        $facturasData = Conciliacion_pagos_factura::whereIn('nombre_factura', $facturaNombres)
            ->pluck('pue_or_ppd', 'nombre_factura');


        $errores = [];
        $por_aplicar = [];
        $filas_limpias = [];

        foreach ($data as $index => $row) {
            // Saltar la fila 0 y 1 (títulos)
            if ($index < 2) {
                $filas_limpias[] = $row;
                continue;
            }
            if (!empty($row[1])) {
                $row[1] = Carbon::createFromFormat('Y-m-d', $row[1])->format('d/m/Y');
            }

            $row[8] = (!empty($row[8]) && Carbon::hasFormat($row[8], 'Y-m-d'))
                ? Carbon::createFromFormat('Y-m-d', $row[8])->format('d/m/Y')
                : null;

            $row[9] = (!empty($row[9]) && Carbon::hasFormat($row[9], 'Y-m-d'))
                ? Carbon::createFromFormat('Y-m-d', $row[9])->format('d/m/Y')
                : null;


            // Rellenar hasta el índice 30
            for ($i = count($row); $i < 34; $i++) {
                $row[$i] = null;
            }

            // Validaciones
            if (isset($row[7], $row[14])) {
                if (



                    ($row[7] === 'BBVA' && preg_match('/BBVA|BANCOMER/i', $row[14])) &&
                    strpos($row[14], 'USD') === false ||
                    ($row[7] === 'BBVA USD' && strpos($row[14], 'USD') !== false) ||
                    ($row[7] === 'BAJIO CVCA' && preg_match('/BBC|CUERNAVACA|CVCA/i', $row[14])) ||
                    ($row[7] === 'BBQ' && preg_match('/BBQ|QRO|QUERETARO/i', $row[14])) ||
                    ($row[7] === 'INB' && preg_match('/INB|INBURSA/i', $row[14]))
                ) {
                    $row[28] = '';
                } else {
                    $row[28] = 'Error Cuentas';
                }
            } else {
                $row[28] = 'Error Faltan Valores de Cuentas';
            }

            if (isset($row[6], $row[20])) {
                $metodo_banco = trim($row[6]);
                $metodo_sistema = trim($row[20]);

                $validos = [
                    'EFECTIVO' => '01 - EFECTIVO',
                    'CHEQUE' => '02 - CHEQUE',
                    'TRANSFERENCIA' => '03 - TRANSFERENCIA',
                    'TARJETA DE DEBITO' => '28 - TARJETA DE DÉBITO',
                    'TARJETA DE CREDITO' => '04 - TARJETA DE CRÉDITO',
                    'TARJETA DE DÉBITO' => '28 - TARJETA DE DÉBITO',
                    'TARJETA DE CRÉDITO' => '04 - TARJETA DE CRÉDITO',
                ];

                // También permitir cosas como "TRA" que significan transferencia
                $es_transferencia = (
                    strtoupper(substr($metodo_banco, 0, 2)) === 'TR' &&
                    $metodo_sistema === '03 - TRANSFERENCIA'
                );

                $es_efectivo = (
                    strtoupper(substr($metodo_banco, 0, 2)) === 'EF' &&
                    $metodo_sistema === '01 - EFECTIVO'
                );

                $es_cheque = (
                    strtoupper(substr($metodo_banco, 0, 2)) === 'CH' &&
                    $metodo_sistema === '02 - CHEQUE'
                );

                if (
                    (isset($validos[$metodo_banco]) && $validos[$metodo_banco] === $metodo_sistema) ||
                    $es_transferencia || $es_efectivo || $es_cheque
                ) {
                    $row[29] = ''; // Método correcto
                } else {
                    $row[29] = 'Error Forma de Pago';
                }
            } else {
                $row[29] = 'Error Faltan valores de Forma de Pago';
            }

            if (isset($row[21])) {
                $cfdi_validos = [
                    'S01-Sin efectos fiscales',
                    'G01-Adquisición de mercancias',
                    'G03-Gastos en general',
                ];
                $row[30] = in_array(trim($row[21]), $cfdi_validos) ? '' : 'Error Uso del CFDi';
            } else {
                $row[30] = 'Error sin valor CFDi';
            }

            if (isset($row[26])) {
                $row[31] = (trim($row[26]) === 'CP01-Pago') ? '' : 'Error CFDi para pago';
            } else {
                $row[31] = 'Error sin valor CFDi para pago';
            }

            $forma_pago_primaria = Conciliacion_pagos_info_primaria::where('numero_documento', $row[5])
                ->value('forma_pago');

            if (isset($row[20])) {
                $row[32] = ($row[20] === $forma_pago_primaria) ? '' : 'Error, en info primaria tiene: ' . $forma_pago_primaria;
            }


            $nombreFactura = $row[10];
            if (isset($facturasData[$nombreFactura])) {
                $row[24] = $facturasData[$nombreFactura];
            }


            $valor23 = trim($row[24]);
            $valor18 = trim($row[19]);

            if ($valor23 === "") {
                // Por aplicar, aunque $valor18 tenga algo

            } elseif ($valor23 == $valor18) {
                // Todo bien
            } else {
                // Diferente, aquí sí puede marcar como error
                $row[33] = "Error Metodo de Pago";
            }

            if (
                str_contains($row[26], 'Error') ||
                str_contains($row[27], 'Error') ||
                str_contains($row[28], 'Error') ||
                str_contains($row[29], 'Error') ||
                str_contains($row[30], 'Error') ||
                str_contains($row[31], 'Error') ||
                str_contains($row[32], 'Error') ||
                str_contains($row[33], 'Error')
            ) {
                $errores[] = $row;
            } else {
                if ($row[24] == "") {
                    $por_aplicar[] = $row;
                } else {
                    $filas_limpias[] = $row;

                }
            }
        }

        // Reemplazar el arreglo original por el filtrado
        $data = $filas_limpias;



        $data[] = ['Errores'];

        if (count($errores)) {
            //$data[] = array_keys($errores[0]); // Encabezados
            $header = array_keys($conciliados[0]);

            $data[] = $header;

            foreach ($errores as $row) {
                $data[] = array_values($row); // Filas
            }
        } else {
            $data[] = ['No se Encontraron Errores'];
        }

        $data[] = ['Por Aplicar'];

        if (count($por_aplicar)) {
            $header = array_keys($conciliados[0]);

            $data[] = $header;
            foreach ($por_aplicar as $row) {
                $data[] = array_values($row); // Filas
            }
        } else {
            $data[] = ['No se Encontraron registros por aplicar'];
        }


        $data[] = [];
        $data[] = ['Pagos NO Conciliados (Bancos)'];
        $data[] = ['', 'Fecha', 'Concepto', 'Pago', 'Abonos', 'Cliente', 'Banco']; // encabezados
        foreach ($final_no_conciliados_bancos as $b) {
            $fecha = \Carbon\Carbon::createFromFormat('Y-m-d', $b->fecha)->format('d/m/Y');
            $fecha2 = \Carbon\Carbon::createFromFormat('Y-m-d', $b->fidp)->format('d/m/Y');

            $data[] = ['', $fecha, $b->pago, $b->abonos, $b->concepto_referencia, $b->cliente, $b->banco, $fecha2];
        }


        foreach ($conciliados as $con) {

            $importeConciliado = $con['Importe Netsuite'] ?? null;

            foreach ($final_no_conciliados_netsuite as $no_con) {
                if (($importeConciliado == $no_con->importe) && ($con['Nombre Netsuite'] == $no_con->nombre)) {
                    // Log::info('Duplicado encontrado', [
                    //     'Conciliado Num Doc' => $con['Numero Documento Netsuite'] ?? 'N/A',
                    //     'No Conciliado Num Doc' => $no_con->numero_documento
                    // ]);
                    $no_con->posible_duplicado = "Posible Duplicado de " . $con['Numero Documento Netsuite'];

                } else {
                    // Log::info('No entro en la igualdad', [
                    //     'importeConciliado' => $importeConciliado ?? 'N/A',
                    //     'No conciliado importe' => $no_con->importe
                    // ]);
                }
            }
        }

        foreach ($errores as $err) {

            $importeError = $err[15];

            foreach ($final_no_conciliados_netsuite as $no_con) {
                if (($importeError == $no_con->importe) && ($err[12] == $no_con->nombre)) {
                    // Log::info('Duplicado encontrado', [
                    //     'Conciliado Num Doc' => $con['Numero Documento Netsuite'] ?? 'N/A',
                    //     'No Conciliado Num Doc' => $no_con->numero_documento
                    // ]);
                    $no_con->posible_duplicado = "Posible Duplicado de " . $err[11];

                } else {
                    // Log::info('No entro en la igualdad', [
                    //     'importeConciliado' => $importeConciliado ?? 'N/A',
                    //     'No conciliado importe' => $no_con->importe
                    // ]);
                }
            }
        }


        $data[] = [];
        $data[] = ['Pagos NO Conciliados (Netsuite)'];
        $data[] = ['', 'Fecha', 'Numero Documento', 'Importe', 'Cuenta', 'Nombre', 'Creado Desde', 'Nota', 'Estado', 'Creado Por']; // encabezados
        foreach ($final_no_conciliados_netsuite as $n) {
            $fecha = \Carbon\Carbon::createFromFormat('Y-m-d', $n->fecha)->format('d/m/Y');
            $data[] = ['', $fecha, $n->numero_documento, $n->importe, $n->cuenta, $n->nombre, preg_replace('/^.*#/', '', $n->creado_desde), $n->nota, $n->estado, $n->creado_por, $n->posible_duplicado];
        }



        return $data;

    }



    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Fecha
            'D' => NumberFormat::FORMAT_NUMBER_00,     // Abonos
            'O' => NumberFormat::FORMAT_NUMBER_00,     // Saldo
        ];
    }

    public function extraerFechaDeTexto($texto)
    {
        if (preg_match('/\b(\d{1,2})\/(\d{1,2})\/(\d{4})\b/', $texto, $matches)) {
            $part1 = $matches[1]; // Día o Mes
            $part2 = $matches[2]; // Mes o Día
            $year = $matches[3];

            // Intenta primero como DÍA/MES/AÑO (d/m/Y)
            if (checkdate($part2, $part1, $year)) {
                return \Carbon\Carbon::createFromFormat('d/m/Y', "$part1/$part2/$year")->format('Y-m-d');
            }
            // Si no, intenta como MES/DÍA/AÑO (m/d/Y)
            elseif (checkdate($part1, $part2, $year)) {
                return \Carbon\Carbon::createFromFormat('m/d/Y', "$part1/$part2/$year")->format('Y-m-d');
            }
        }
        return "sin coincidencias";
    }
}


