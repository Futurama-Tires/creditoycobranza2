<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelpers;
use App\Models\Conciliacion_pagos_info_primaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Pagos;
use App\Models\Fut_historial_pagos;
use App\Models\Conciliacion_pagos_factura;
use PDO;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Validator; // Add this import
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ConciliacionPagosExport;
use App\Exports\PendientesConciliacionPagosExport;
use App\Imports\FacturasPUEOrPPDImport;

class PagosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pagos = Pagos::all();
        $fut_historial_pagos = Fut_historial_pagos::all();

        return view('conciliacion_pagos.index', [
            'pagos' => $pagos,
            'fut_historial_pagos' => $fut_historial_pagos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function importBancosAndNetsuiteReporte(Request $request)
    {
        set_time_limit(180);
        Pagos::truncate();
        Fut_historial_pagos::truncate();
        Conciliacion_pagos_info_primaria::truncate();

        //Log::info("Starting import");

        try {
            Log::info('Pre-validation file check:', [
                'has_bancos' => $request->hasFile('bancos'),
                'has_fut' => $request->hasFile('fut_historial_pagos'),
                'has_primaria' => $request->hasFile('primari')
            ]);

            $validator = Validator::make($request->all(), [
                'bancos' => [
                    'required',
                    'file',
                    function ($attr, $value, $fail) {
                        $valid = ['xlsx', 'xls'];
                        $ext = strtolower($value->getClientOriginalExtension());
                        if (!in_array($ext, $valid)) {
                            $fail("The $attr must be .xlsx or .xls");
                        }
                    }
                ],

                'fut_historial_pagos' => [
                    'required',
                    'file',
                    function ($attr, $value, $fail) {
                        $valid = ['xlsx', 'xls', 'csv'];
                        $ext = strtolower($value->getClientOriginalExtension());
                        if (!in_array($ext, $valid)) {
                            $fail("The $attr must be .xlsx or .xls");
                        }
                    }
                ],

                'primaria' => [
                    'required',
                    'file',
                    function ($attr, $value, $fail) {
                        $valid = ['xlsx', 'xls'];
                        $ext = strtolower($value->getClientOriginalExtension());
                        if (!in_array($ext, $valid)) {
                            $fail("The $attr must be .xlsx or .xls");
                        }
                    }
                ],
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $file = $request->file('bancos');
            $file2 = $request->file('fut_historial_pagos');
            $file3 = $request->file('primaria');

            // Log::info('Processing file3:', [
            //     'name' => $file3->getClientOriginalName(),
            //     'type' => $file3->getMimeType()
            // ]);

            $rows = SimpleExcelReader::create($file, 'xlsx')
                ->noHeaderRow()  // <-- agrega esto
                ->skip(1)
                ->getRows();

            $rows2 = SimpleExcelReader::create($file2, 'xlsx')
                ->noHeaderRow()  // <-- agrega esto
                ->skip(1)
                ->getRows();

            $rows3 = SimpleExcelReader::create($file3, 'xlsx')
                ->noHeaderRow()  // <-- agrega esto
                ->skip(1)
                ->getRows();

            $rows = $rows->collect();

            $rows2 = $rows2->collect();

            $rows3 = $rows3->collect();


            DB::beginTransaction();

            $insertedCount = 0;
            $skippedCount = 0;


            $rows->each(function ($row, $index) {



                $fecha = $row[0] instanceof \DateTimeInterface
                    ? $row[0]->format('Y-m-d')
                    : null;

                $fecha2 = isset($row[7]) && $row[7] instanceof \DateTimeInterface
                    ? $row[7]->format('Y-m-d')
                    : null;

                try {

                    DB::table('pagos')->insert([
                        'fecha' => $fecha,
                        'concepto_referencia' => $row[1],
                        'abonos' => is_numeric($row[2]) ? $row[2] : 0,
                        'cliente' => $row[3],
                        'pago' => $row[4] ?? null,
                        'forma_pago' => $row[5] ?? null,
                        'banco' => $row[6] ?? null,
                        'fidp' => $fecha2,
                    ]);
                } catch (\Exception $e) {
                    Log::error("Error inserting row $index: " . $e->getMessage(), $row);
                    throw $e; // <- Lanzamos la excepción para que falle la transacción

                    //$skippedCount++;
                }
            });

            $rows2->each(function ($row, $index) {



                $fecha = $row[1] instanceof \DateTimeInterface
                    ? $row[1]->format('Y-m-d')
                    : null;

                try {

                    DB::table('fut_historial_pagos')->insert([
                        'fecha' => $fecha,
                        'creado_desde' => $row[2],
                        'numero_documento' => $row[3],
                        'nombre' => $row[4],
                        'cuenta' => $row[5],
                        'nota' => $row[6],
                        'importe' => $row[7],
                        'estado' => $row[8],
                        'folio_SAT_1' => $row[9],
                        'rfc_1' => $row[10],
                        'forma_pago' => $row[11],
                        'metodo_pago' => $row[12],
                        'uso_del_cfdi' => $row[13],
                        'creado_por' => $row[14],
                        'representante_ventas' => $row[18],
                        'metodo_pago_2' => $row[20],
                        'rfc_2' => $row[21],
                        'uso_de_cfdi_para_pago' => $row[24],
                    ]);
                } catch (\Exception $e) {
                    Log::error("Error inserting row $index: " . $e->getMessage(), $row);
                    //$skippedCount++;
                    throw $e; // <- Lanzamos la excepción para que falle la transacción

                }
            });

            $rows3->each(function ($row, $index) {




                try {

                    DB::table('conciliacion_pagos_info_primarias')->insert([
                        'numero_documento' => $row[3],
                        'forma_pago' => $row[1],

                    ]);
                } catch (\Exception $e) {
                    Log::error("Error inserting row $index: " . $e->getMessage(), $row);
                    throw $e; // <- Lanzamos la excepción para que falle la transacción

                    //$skippedCount++;
                }
            });

            // collect(SimpleExcelReader::create($file2, 'csv')
            //     ->noHeaderRow()
            //     ->skip(1)
            //     ->getRows())
            //     ->chunk(1000)
            //     ->each(function ($chunk) {
            //         $data = [];

            //         foreach ($chunk as $row) {
            //             $fecha = !empty($row[1])
            //                 ? Carbon::createFromFormat('d/m/Y', $row[1])->format('Y-m-d')
            //                 : null;
            //             $data[] = [
            //                 'fecha' => $fecha,
            //                 'creado_desde' => $row[2],
            //                 'numero_documento' => $row[3],
            //                 'nombre' => $row[4],
            //                 'cuenta' => $row[5],
            //                 'nota' => $row[6],
            //                 'importe' => $row[7],
            //                 'estado' => $row[8],
            //                 'folio_SAT_1' => $row[9],
            //                 'rfc_1' => $row[10],
            //                 'forma_pago' => $row[11],
            //                 'metodo_pago' => $row[12],
            //                 'uso_del_cfdi' => $row[13],
            //                 'creado_por' => $row[14],
            //                 'representante_ventas' => $row[18],
            //                 'metodo_pago_2' => $row[20],
            //                 'rfc_2' => $row[21],
            //                 'uso_de_cfdi_para_pago' => $row[24],
            //             ];
            //         }

            //         DB::table('fut_historial_pagos')->insert($data);
            //     });




            DB::commit();

            Log::info("Import completed", [
                'inserted' => $insertedCount,
                'skipped' => $skippedCount
            ]);


            return back()->with('success', 'Los archivos se importaron correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Import failed: " . $e->getMessage());
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }


    public function exportConciliacionPagos()
    {
        try {
            return Excel::download(
                new ConciliacionPagosExport,
                'Conciliacion de Pagos ' . now()->format('Y-m-d_H-i-s') . '.xlsx'
            );
        } catch (\Exception $e) {
            // Puedes personalizar esta respuesta según lo necesites
            return redirect()->back()->with('error', 'Error al generar el archivo de conciliación de pagos: ' . $e->getMessage());
        }
    }

    public function depositosPendientesExportConciliacionPagos()
    {
        try {
            return Excel::download(
                new PendientesConciliacionPagosExport,
                'Conciliacion de Pagos Pendientes ' . now()->format('Y-m-d_H-i-s') . '.xlsx'
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el archivo de pagos pendientes: ' . $e->getMessage());
        }
    }

    // public function ImportarConciliacionPagosFacturas()
    // {
    //     $pdo = DB::connection()->getPdo();
    //     $pdo->setAttribute(PDO::MYSQL_ATTR_LOCAL_INFILE, true);
    //     $filePath = str_replace('\\', '/', $filePath);

    //     $query = <<<SQL

    //     LOAD DATA LOCAL INFILE '$filePath'
    //     INTO TABLE 
    // }

    public function importarFacturas()
    {
        $smallPath = storage_path('app/public/facturas.csv');

        $generaterow = function ($row) {

            if (count($row) < 2 || empty($row[0])) {
                //Log::warning('Skipping malformed row: ' . json_encode($row));
                return null;
            }
            return [
                'nombre_factura' => $row[0] ?? null,
                'pue_or_ppd' => $row[1] ?? null,
                'created_at' => "2025-05-26" ?? null,
                'updated_at' => "2025-05-26" ?? null,
            ];
        };

        // $total = 0;
        foreach (ArrayHelpers::chunkfile($smallPath, $generaterow, 1000) as $chunk) {
            //DB::table('conciliacion_pagos_facturas')->insert($chunk);

            try {
                DB::table('conciliacion_pagos_facturas')->insert($chunk);
            } catch (\Exception $e) {
                //Log::error('DB Insert Error: ' . $e->getMessage());
            }
            //Log::info('Inserting chunk of size: ' . count($chunk));
            // $total += count($chunk);

            unset($chunk);
            gc_collect_cycles();
        }

        // \Log::info('Total rows processed: ' . $total);


    }



    public function importarFacturasUsuario(Request $request)
    {

        set_time_limit(180);

        try {
            Log::info('Pre-validation file check:', [
                'has_facturas' => $request->hasFile('facturas')
            ]);

            $validator = Validator::make($request->all(), [
                'facturas' => [
                    'required',
                    'file',
                    function ($attr, $value, $fail) {
                        $valid = ['xlsx', 'xls'];
                        $ext = strtolower($value->getClientOriginalExtension());
                        if (!in_array($ext, $valid)) {
                            $fail("The $attr must be .xlsx or .xls");
                        }
                    }
                ],
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $file = $request->file('facturas');

            $rows = SimpleExcelReader::create($file, 'xlsx')
                ->noHeaderRow()  // <-- agrega esto
                ->skip(1)
                ->getRows();


            $rows = $rows->collect();


            DB::beginTransaction();

            $insertedCount = 0;
            $skippedCount = 0;


            // Paso 1: Saca los nombres de factura del Excel
            $facturaNombres = $rows->pluck(3)->filter()->unique()->toArray();

            // Paso 2: Consulta solo esas facturas
            $existentes = DB::table('conciliacion_pagos_facturas')
                ->whereIn('nombre_factura', $facturaNombres)
                ->select('nombre_factura', 'pue_or_ppd')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->nombre_factura => $item->pue_or_ppd];
                });

            $rows->each(function ($row, $index) use ($existentes) {
                try {
                    $nombreFactura = $row[3];
                    $pueOrPpd = $row[13];

                    // Si no existe o el valor cambió
                    if (!isset($existentes[$nombreFactura]) || $existentes[$nombreFactura] !== $pueOrPpd) {
                        DB::table('conciliacion_pagos_facturas')->updateOrInsert(
                            ['nombre_factura' => $nombreFactura],
                            ['pue_or_ppd' => $pueOrPpd]
                        );
                    }
                } catch (\Exception $e) {
                    Log::error("Error row $index: " . $e->getMessage(), ['row' => $row]);
                    throw $e;
                }
            });





            DB::commit();

            Log::info("Import completed", [
                'inserted' => $insertedCount,
                'skipped' => $skippedCount
            ]);


            return back()->with('success', 'Los archivos se importaron correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Import failed: " . $e->getMessage());
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function importarFacturasUsuarioVista()
    {
        return view('conciliacion_pagos.facturas', []);
    }
}
