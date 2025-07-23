<?php

namespace App\Http\Controllers\EstadosDeCuenta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetsuiteService;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class EstadosDeCuentaController extends Controller
{
    protected $netsuite;

    /**
     * Display a listing of the resource.
     */
    public function __construct(NetsuiteService $netsuite)
    {
        $this->netsuite = $netsuite;
    }

    public function index()
    {
        return view('estados_de_cuenta.index');
    }

    public function getCustomers(Request $request)
    {

        $customerName = strtoupper($request->input('query'));

        $query = "SELECT DISTINCT
    BUILTIN.DF(Customer.altname) AS altname,
    Customer.ID AS customer_id
    FROM 
        Customer
    LEFT JOIN employee ON Customer.salesrep = employee.ID
    WHERE 
        employee.subsidiary IN ('3') AND 
        Customer.altname IS NOT NULL AND 
        Customer.custentitycodigo_cliente IS NOT NULL AND 
        Customer.custentity_rfc IS NOT NULL AND
        Customer.altname LIKE '" . '%' . addslashes($customerName) . '%' . "'
    ORDER BY 
        altname ASC";


        $results = $this->querySuiteQL($query);
        // Log::info($results);
        return response()->json($results);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function getFacturasPagosNDC(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $datosFacturasPendientes = $this->getFacturasPendientes($customer_id);
        $pagosYNDC = $this->getPagosYNDCPendientes($customer_id);

        dd([$datosFacturasPendientes, $pagosYNDC]);
    }

    public function getFacturasPendientes($customer_id)
    {


        $query = "SELECT 
        BUILTIN.DF(Customer.altname) AS altname,
        BUILTIN.DF(Customer.custentitycodigo_cliente) AS customer_code,
        BUILTIN.DF(Customer.custentity_rfc) AS rfc,
        BUILTIN.DF(transaction_SUB.fullname) AS status,
        BUILTIN.DF(transaction_SUB.trandate) AS transaction_date,
        BUILTIN.DF(transaction_SUB.typebaseddocumentnumber) AS document_number,
        BUILTIN.DF(transaction_SUB.custbody_foliosat) AS folio_sat,
        BUILTIN.DF(employee.lastname) AS salesrep_lastname,
        BUILTIN.DF(employee.firstname) AS salesrep_firstname,
        BUILTIN.DF(CUSTOMLIST423.name) AS customer_type,
        BUILTIN.DF(transaction_SUB.memo) AS memo,
        BUILTIN.DF(transaction_SUB.custbody_nso_notas_de_usuario) AS user_notes,
        BUILTIN.DF(transaction_SUB.duedate) AS due_date,
        transaction_SUB.foreigntotal AS total_amount,
        transaction_SUB.foreignamountunpaid AS amount_unpaid,
        transaction_SUB.daysoverduesearch AS days_overdue,
        Customer.creditlimit AS credit_limit,
        BUILTIN.DF(term.name) AS payment_terms,
        BUILTIN.DF(currency.name) AS currency_name,
        BUILTIN.DF(Customer.email) AS email,
        BUILTIN.DF(Customer.phone) AS phone,
        BUILTIN.DF(Customer.custentity5) AS additional_field
    FROM 
        Customer
    LEFT JOIN currency ON Customer.currency = currency.ID
    LEFT JOIN CUSTOMLIST423 ON Customer.custentity4 = CUSTOMLIST423.ID
    LEFT JOIN (
        SELECT 
            t.entity,
            ts.fullname,
            t.trandate,
            t.typebaseddocumentnumber,
            t.custbody_foliosat,
            t.memo,
            t.custbody_nso_notas_de_usuario,
            t.duedate,
            t.foreigntotal,
            t.foreignamountunpaid,
            t.daysoverduesearch
        FROM 
            transaction t
        LEFT JOIN TransactionStatus ts ON 
            t.TYPE = ts.trantype AND 
            t.status = ts.ID AND 
            t.customtype = ts.trancustomtype
    ) transaction_SUB ON Customer.ID = transaction_SUB.entity
    LEFT JOIN employee ON Customer.salesrep = employee.ID
    LEFT JOIN term ON Customer.terms = term.ID
    WHERE 
        employee.subsidiary IN ('3') AND 
        Customer.altname IS NOT NULL AND 
        Customer.custentitycodigo_cliente IS NOT NULL AND 
        Customer.custentity_rfc IS NOT NULL AND
        transaction_SUB.foreignamountunpaid > 0 AND
        Customer.ID =  $customer_id
        ";

        return $this->querySuiteQL($query);
    }

    public function getPagosYNDCPendientes($customer_id)
    {

        $query = "SELECT
    BUILTIN.DF(Customer.entityid) AS entity_id,
    BUILTIN.DF(Customer.altname) AS alt_name,
    BUILTIN.DF(Customer.custentitycodigo_cliente) AS customer_code,
    BUILTIN.DF(Customer.custentity_rfc) AS rfc,
    BUILTIN.DF(transaction_SUB.fullname) AS status,
    BUILTIN.DF(transaction_SUB.trandate) AS transaction_date,
    BUILTIN.DF(transaction_SUB.typebaseddocumentnumber) AS document_number,
    BUILTIN.DF(transaction_SUB.custbody_foliosat) AS folio_sat,
    BUILTIN.DF(transaction_SUB.lastname) AS employee_lastname,
    BUILTIN.DF(transaction_SUB.firstname) AS employee_firstname,
    BUILTIN.DF(transaction_SUB.duedate) AS due_date,
    transaction_SUB.foreigntotal AS total_amount,
    transaction_SUB.foreignpaymentamountunused AS payment_amount_unused,
    transaction_SUB.foreignamountunpaid AS amount_unpaid
FROM
    Customer
LEFT JOIN (
    SELECT
        t.entity,
        ts.fullname,
        t.trandate,
        t.typebaseddocumentnumber,
        t.custbody_foliosat,
        e.lastname,
        e.firstname,
        t.duedate,
        t.foreigntotal,
        t.foreignpaymentamountunused,
        t.foreignamountunpaid,
        t.TYPE AS transaction_type,
        t.foreignpaymentamountunused AS unused_payment_criteria
    FROM
        transaction t
    LEFT JOIN employee e ON t.employee = e.ID
    LEFT JOIN TransactionStatus ts ON
        t.TYPE = ts.trantype AND
        t.status = ts.ID AND
        t.customtype = ts.trancustomtype
) transaction_SUB ON Customer.ID = transaction_SUB.entity
LEFT JOIN employee employee_0 ON Customer.salesrep = employee_0.ID
WHERE
    employee_0.subsidiary IN ('3') AND
    Customer.altname IS NOT NULL AND
    transaction_SUB.transaction_type IN ('CustCred', 'CustPymt') AND
    transaction_SUB.unused_payment_criteria > 0
       AND
        Customer.ID =  $customer_id
        ";

        return $this->querySuiteQL($query);
    }

    private function querySuiteQL($query)
    {
        $result = $this->netsuite->suiteqlQuery($query);

        return $result;
    }




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

    //seccion clientes
    public function getFacturasVistaCliente()
    {
        //NOTA IMPORTANTEEEEE codigo_cliente realmente es el id interno en netsuite
        $clienteId = auth()->user()->codigo_cliente;
        //mandar a llamar consulta de facturas pendientes
        $datosFacturasPendientes = $this->getClienteFacturasPendientes($clienteId);
        //contar las facturas vencidas
        $countVencidos = $this->getClientesVencido($datosFacturasPendientes);
        //contar las facturas no vencidas
        $countNoVencidos = $this->getClientesNoVencido($datosFacturasPendientes);
        //llamar porcentajes de facturas vencidas y no vencidas
        $porcentajes = $this->getClientesPorcentajes($countVencidos, $countNoVencidos);
        //mandar a llamar rangos de facturas vencidas
        $saldos = $this->rangosYTotales($datosFacturasPendientes);
        //agregar los dias vencidos ya que hay facturas que no tienen el valor
        $facturas = $this->getClientesDiasVencidos($datosFacturasPendientes);
        //mandar a llamar consulta de saldos a favor
        $datosSaldosAFavorPendientes = $this->getClientesPagosYNDCPendientes($clienteId);
        //filtrar saldos a favor
        $pagosYNDC = $this->getClientesPagosYNDCFiltrados($datosSaldosAFavorPendientes);
        //conseguir el total de notas de credito filtradas y los valores para la grafica
        $datosGraficaSaldosAFavor = $this->getClientesGraficaSaldosAFavor($pagosYNDC);

        return view('estados_de_cuenta.vista-cliente', compact(
            'datosFacturasPendientes',
            'countVencidos',
            'countNoVencidos',
            'porcentajes',
            'saldos',
            'facturas',
            'pagosYNDC',
            'datosGraficaSaldosAFavor',
        ));
    }

    private function getClientesGraficaSaldosAFavor($pagosYNDC)
    {
        //dd($pagosYNDC);

        $totalGeneral = $pagosYNDC->sum(function ($pago) {
            return floatval($pago['payment_amount_unused']);
        });

        $pagosConPorcentaje = $pagosYNDC->map(function ($pago) use ($totalGeneral) {
            $monto = floatval($pago['payment_amount_unused']);
            $porcentaje = $totalGeneral != 0 ? ($monto / $totalGeneral) * 100 : 0;

            return array_merge($pago, [
                'percentage' => round($porcentaje, 2)
            ]);
        });
        // Ordenar por porcentaje descendente y tomar los primeros 5
        $pagosConPorcentaje = $pagosConPorcentaje
            ->sortByDesc('percentage')
            ->take(5)
            ->values(); // Para reindexar la colección

        // Ver resultado
        //dd($totalGeneral, $pagosConPorcentaje);

        return [
            'totalGeneral' => $totalGeneral,
            'pagosConPorcentaje' => $pagosConPorcentaje,
        ];
    }

    private function getClientesPagosYNDCFiltrados(array $datosSaldosAFavorPendientes = [])
    {
        $pagosYNDC = collect($datosSaldosAFavorPendientes['items']);

        // $documentosFiltrados = $pagosYNDC->filter(function ($documento) {
        //     return $documento['payment_amount_unused'] >= 1;
        // });
        $documentosFiltrados = $pagosYNDC->filter(function ($documento) {
            $unused = $documento['payment_amount_unused'] >= 1;

            // Aquí ajustamos el formato
            $fechaTransaccion = \Carbon\Carbon::createFromFormat('d/m/Y', $documento['transaction_date']);
            $diasDiferencia = $fechaTransaccion->diffInDays(now());

            $vigente = $diasDiferencia <= 300;

            // Log::info('Filtro PagosYNDC', [
            //     'transaction_date' => $documento['transaction_date'],
            //     'diasDiferencia' => $diasDiferencia,
            //     'vigente' => $vigente,
            //     'payment_amount_unused' => $documento['payment_amount_unused'],
            //     'unused false or true' => $unused,

            // ]);

            return $unused && $vigente;
        });

        return $documentosFiltrados->values();
    }

    private function getClientesDiasVencidos(array $datosFacturasPendientes = [])
    {
        $facturas = collect($datosFacturasPendientes['items']);

        $facturasConDiasVencidos = $facturas->map(function ($factura) {
            // Due date en formato d/m/Y
            if (isset($factura['due_date'])) {
                $dueDate = \Carbon\Carbon::createFromFormat('d/m/Y', $factura['due_date']);

                // Fecha actual
                //$hoy = \Carbon\Carbon::now(); // sin hora
                $hoy = \Carbon\Carbon::now()->endOfDay();

                // Diferencia en días
                $diasVencidos = $dueDate->diffInDays($hoy, false); // false = conserva signo

                // Agregar nuevo campo
                $factura['dias_vencidos'] = $diasVencidos;
            } else {
                $factura['dias_vencidos'] = "-";
            }
            return $factura;
        });
        return $facturasConDiasVencidos;
    }

    private function getClientesVencido(array $datosFacturasPendientes = [])
    {
        $countVencido = count(array_filter($datosFacturasPendientes['items'], function ($item) {
            return isset($item['days_overdue']) && is_numeric($item['days_overdue']) && $item['days_overdue'] > 0;
        }));
        return $countVencido;
    }

    private function getClientesNoVencido(array $datosFacturasPendientes = [])
    {
        $countNoVencido = count(array_filter($datosFacturasPendientes['items'], function ($item) {
            return isset($item['days_overdue']) && is_numeric($item['days_overdue']) && $item['days_overdue'] <= 0;
        }));
        return $countNoVencido;
    }

    private function getClientesPorcentajes(int $vencido, int $no_vencido)
    {

        $total = $vencido + $no_vencido;

        // Evitar división entre cero
        if ($total === 0) {
            $porcentajeVencido = 0;
            $porcentajeNoVencido = 0;
        } else {
            $porcentajeVencido = ($vencido / $total) * 100;
            $porcentajeNoVencido = ($no_vencido / $total) * 100;
        }

        return [
            'vencido' => $porcentajeVencido,
            'no_vencido' => $porcentajeNoVencido,
        ];
    }

    private function rangosYTotales(array $datosFacturasPendientes = [])
    {
        $facturas = collect($datosFacturasPendientes['items']);

        $saldo_total = $facturas->sum(function ($factura) {
            return floatval($factura['amount_unpaid']);
        });

        $totalVencidas = $facturas
            ->filter(function ($factura) {
                return isset($factura['days_overdue']) && $factura['days_overdue'] >= 1;
            })
            ->sum(function ($factura) {
                return floatval($factura['amount_unpaid']);
            });

        $totalNoVencidas = $facturas
            ->filter(function ($factura) {
                return isset($factura['days_overdue']) && $factura['days_overdue'] <= 0;
            })
            ->sum(function ($factura) {
                return floatval($factura['amount_unpaid']);
            });

        $totalAmountUnpaid_1_30 = $facturas
            ->filter(function ($factura) {
                return isset($factura['days_overdue']) && $factura['days_overdue'] >= 1 && $factura['days_overdue'] <= 30;
            })
            ->sum(function ($factura) {
                return (float) $factura['amount_unpaid'];
            });

        $totalAmountUnpaid_31_60 = $facturas
            ->filter(function ($factura) {
                return isset($factura['days_overdue']) && $factura['days_overdue'] >= 31 && $factura['days_overdue'] <= 60;
            })
            ->sum(function ($factura) {
                return (float) $factura['amount_unpaid'];
            });

        $totalAmountUnpaid_61_90 = $facturas
            ->filter(function ($factura) {
                return isset($factura['days_overdue']) && $factura['days_overdue'] >= 61 && $factura['days_overdue'] <= 90;
            })
            ->sum(function ($factura) {
                return (float) $factura['amount_unpaid'];
            });

        $totalAmountUnpaid_91_120 = $facturas
            ->filter(function ($factura) {
                return isset($factura['days_overdue']) && $factura['days_overdue'] >= 91 && $factura['days_overdue'] <= 120;
            })
            ->sum(function ($factura) {
                return (float) $factura['amount_unpaid'];
            });

        $totalAmountUnpaid_mayor_a_120 = $facturas
            ->filter(function ($factura) {
                return isset($factura['days_overdue']) && $factura['days_overdue'] > 120;
            })
            ->sum(function ($factura) {
                return (float) $factura['amount_unpaid'];
            });

        $otras = $facturas
            ->filter(function ($factura) {
                return !isset($factura['days_overdue']);
            })
            ->sum(function ($factura) {
                return (float) $factura['amount_unpaid'];
            });

        return [
            'saldo_total' => $saldo_total,
            'totalNoVencidas' => $totalNoVencidas,
            'totalVencidas' => $totalVencidas,
            '1_30' => $totalAmountUnpaid_1_30,
            '31_60' => $totalAmountUnpaid_31_60,
            '61_90' => $totalAmountUnpaid_61_90,
            '91_120' => $totalAmountUnpaid_91_120,
            'mayor_a_120' => $totalAmountUnpaid_mayor_a_120,
            'otras' => $otras,
        ];
    }



    //Funcion para mandar a llamar los clientes por codigo
    public function getClienteFacturasPendientes(int $id)
    {
        $query = "SELECT 
        BUILTIN.DF(Customer.altname) AS altname,
        BUILTIN.DF(Customer.custentitycodigo_cliente) AS customer_code,
        BUILTIN.DF(Customer.custentity_rfc) AS rfc,
        BUILTIN.DF(transaction_SUB.fullname) AS status,
        BUILTIN.DF(transaction_SUB.trandate) AS transaction_date,
        BUILTIN.DF(transaction_SUB.typebaseddocumentnumber) AS document_number,
        BUILTIN.DF(transaction_SUB.custbody_foliosat) AS folio_sat,
        BUILTIN.DF(employee.lastname) AS salesrep_lastname,
        BUILTIN.DF(employee.firstname) AS salesrep_firstname,
        BUILTIN.DF(CUSTOMLIST423.name) AS customer_type,
        BUILTIN.DF(transaction_SUB.memo) AS memo,
        BUILTIN.DF(transaction_SUB.custbody_nso_notas_de_usuario) AS user_notes,
        BUILTIN.DF(transaction_SUB.duedate) AS due_date,
        transaction_SUB.foreigntotal AS total_amount,
        transaction_SUB.foreignamountunpaid AS amount_unpaid,
        transaction_SUB.daysoverduesearch AS days_overdue,
        Customer.creditlimit AS credit_limit,
        BUILTIN.DF(term.name) AS payment_terms,
        BUILTIN.DF(currency.name) AS currency_name,
        BUILTIN.DF(Customer.email) AS email,
        BUILTIN.DF(Customer.phone) AS phone,
        BUILTIN.DF(Customer.custentity5) AS additional_field
    FROM 
        Customer
    LEFT JOIN currency ON Customer.currency = currency.ID
    LEFT JOIN CUSTOMLIST423 ON Customer.custentity4 = CUSTOMLIST423.ID
    LEFT JOIN (
        SELECT 
            t.entity,
            ts.fullname,
            t.trandate,
            t.typebaseddocumentnumber,
            t.custbody_foliosat,
            t.memo,
            t.custbody_nso_notas_de_usuario,
            t.duedate,
            t.foreigntotal,
            t.foreignamountunpaid,
            t.daysoverduesearch
        FROM 
            transaction t
        LEFT JOIN TransactionStatus ts ON 
            t.TYPE = ts.trantype AND 
            t.status = ts.ID AND 
            t.customtype = ts.trancustomtype
    ) transaction_SUB ON Customer.ID = transaction_SUB.entity
    LEFT JOIN employee ON Customer.salesrep = employee.ID
    LEFT JOIN term ON Customer.terms = term.ID
    WHERE 
        employee.subsidiary IN ('3') AND 
        Customer.altname IS NOT NULL AND 
        Customer.custentitycodigo_cliente IS NOT NULL AND 
        Customer.custentity_rfc IS NOT NULL AND
        transaction_SUB.foreignamountunpaid > 0 AND
        Customer.ID = $id
        ";

        return $this->querySuiteQL($query);
    }

    public function getClientesPagosYNDCPendientes(int $clienteId)
    {

        $query = "SELECT
        BUILTIN.DF(Customer.entityid) AS entity_id,
        BUILTIN.DF(Customer.altname) AS alt_name,
        BUILTIN.DF(Customer.custentitycodigo_cliente) AS customer_code,
        BUILTIN.DF(Customer.custentity_rfc) AS rfc,
        BUILTIN.DF(transaction_SUB.fullname) AS status,
        BUILTIN.DF(transaction_SUB.trandate) AS transaction_date,
        BUILTIN.DF(transaction_SUB.typebaseddocumentnumber) AS document_number,
        BUILTIN.DF(transaction_SUB.custbody_foliosat) AS folio_sat,
        BUILTIN.DF(transaction_SUB.lastname) AS employee_lastname,
        BUILTIN.DF(transaction_SUB.firstname) AS employee_firstname,
        BUILTIN.DF(transaction_SUB.duedate) AS due_date,
        BUILTIN.DF(currency.name) AS currency_name,
        transaction_SUB.foreigntotal AS total_amount,
        transaction_SUB.foreignpaymentamountunused AS payment_amount_unused,
        transaction_SUB.foreignamountunpaid AS amount_unpaid
        FROM
            Customer
        LEFT JOIN currency ON Customer.currency = currency.ID
        LEFT JOIN (
            SELECT
                t.entity,
                ts.fullname,
                t.trandate,
                t.typebaseddocumentnumber,
                t.custbody_foliosat,
                e.lastname,
                e.firstname,
                t.duedate,
                t.foreigntotal,
                t.foreignpaymentamountunused,
                t.foreignamountunpaid,
                t.TYPE AS transaction_type,
                t.foreignpaymentamountunused AS unused_payment_criteria
            FROM
                transaction t
            LEFT JOIN employee e ON t.employee = e.ID
            LEFT JOIN TransactionStatus ts ON
                t.TYPE = ts.trantype AND
                t.status = ts.ID AND
                t.customtype = ts.trancustomtype
        ) transaction_SUB ON Customer.ID = transaction_SUB.entity
        LEFT JOIN employee employee_0 ON Customer.salesrep = employee_0.ID
        WHERE
            employee_0.subsidiary IN ('3') AND
            Customer.altname IS NOT NULL AND
            transaction_SUB.transaction_type IN ('CustCred', 'CustPymt') AND
            transaction_SUB.unused_payment_criteria > 0 AND
            Customer.ID = $clienteId
        ";

        return $this->querySuiteQL($query);
    }

    public function downloadExcelCliente()
    {
        $clienteId = auth()->user()->clienteId;
        $nombreCliente = auth()->user()->name . " " . auth()->user()->apellido;

        return $this->generarEstadoCuentaExcel($clienteId, $nombreCliente);
    }

    public function downloadExcelEstadoDeCuenta($codigoCliente)
    {
        $datosFacturasPendientes = $this->getClienteFacturasPendientes($codigoCliente);
        $nombreCliente = $datosFacturasPendientes['items'][0]['altname'] ?? 'Cliente';

        return $this->generarEstadoCuentaExcel($codigoCliente, $nombreCliente, $datosFacturasPendientes);
    }

    private function generarEstadoCuentaExcel(string $codigoCliente, string $nombreCliente, $datosFacturasPendientes = null)
    {
        $templatePath = storage_path('app/Templates/plantilla_estado_de_cuenta_cliente.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath(storage_path('app/public/logo.png'));
        $drawing->setCoordinates('B1');
        $drawing->setHeight(140);
        $drawing->setWorksheet($sheet);

        // Reutiliza datos si vienen del controlador externo o haz la consulta si no
        $datosFacturasPendientes = $datosFacturasPendientes ?? $this->getClienteFacturasPendientes($codigoCliente);
        $countVencidos = $this->getClientesVencido($datosFacturasPendientes);
        $countNoVencidos = $this->getClientesNoVencido($datosFacturasPendientes);
        $porcentajes = [$this->getClientesPorcentajes($countVencidos, $countNoVencidos)];
        $saldos = $this->rangosYTotales($datosFacturasPendientes);
        $facturas = $this->getClientesDiasVencidos($datosFacturasPendientes);

        // Insertar datos en celdas
        $sheet->setCellValue('C10', $saldos['mayor_a_120']);
        $sheet->setCellValue('C11', $saldos['91_120']);
        $sheet->setCellValue('C12', $saldos['61_90']);
        $sheet->setCellValue('C13', $saldos['31_60']);
        $sheet->setCellValue('C14', $saldos['1_30']);
        $sheet->setCellValue('G12', $saldos['totalVencidas']);
        $sheet->setCellValue('G13', $saldos['totalNoVencidas']);
        $sheet->setCellValue('G14', $saldos['saldo_total']);
        $sheet->setCellValue('E6', $nombreCliente . " - " . $codigoCliente);
        $sheet->getStyle('E6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle("C10:C14")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->getStyle("G12:G14")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);

        //Autoajuste de celdas
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);

        // Llenar tabla con facturas
        $row = 19;
        foreach ($facturas as $factura) {
            $sheet->setCellValue("B{$row}", $factura['transaction_date'] ?? '');
            $sheet->setCellValue("C{$row}", $factura['document_number'] ?? '');
            $sheet->setCellValue("D{$row}", $factura['folio_sat'] ?? '');
            $sheet->setCellValue("E{$row}", $factura['due_date'] ?? '');
            $sheet->setCellValue("F{$row}", $factura['dias_vencidos'] ?? '');
            $sheet->setCellValue("G{$row}", $factura['total_amount'] ?? '');
            $sheet->setCellValue("H{$row}", $factura['amount_unpaid'] ?? '');
            $sheet->getStyle("B{$row}:H{$row}")
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("G{$row}:H{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
            $row++;
        }

        // Crear nombre del archivo
        $fecha = now()->format('d-m-Y');
        $nombreArchivo = "ESTADO DE CUENTA {$nombreCliente} {$fecha}.xlsx";

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $nombreArchivo);
    }
}
