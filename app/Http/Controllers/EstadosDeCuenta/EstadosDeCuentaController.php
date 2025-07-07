<?php

namespace App\Http\Controllers\EstadosDeCuenta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetsuiteService;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

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
        Log::info($results);
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

        $codigo_cliente = auth()->user()->codigo_cliente;
        $datosFacturasPendientes = $this->getClienteFacturasPendientes($codigo_cliente);
        $countVencidos = $this->getClientesVencido($datosFacturasPendientes);
        $countNoVencidos = $this->getClientesNoVencido($datosFacturasPendientes);
        $porcentajes[] = $this->getClientesPorcentajes($countVencidos, $countNoVencidos);
        $saldosVencidos = $this->rangosyTotalVencidas($datosFacturasPendientes);
        dd($saldosVencidos);
        return view('estados_de_cuenta.vista-cliente', compact(
            'datosFacturasPendientes',
            'countVencidos',
            'countNoVencidos',
            'porcentajes',
            'saldosVencidos',
        ));
    }

    private function getClientesVencido(array $datosFacturasPendientes = [])
    {
        $countVencido = count(array_filter($datosFacturasPendientes['items'], function ($item) {
            return $item['days_overdue'] > 0;
        }));
        return $countVencido;
    }

    private function getClientesNoVencido(array $datosFacturasPendientes = [])
    {
        $countNoVencido = count(array_filter($datosFacturasPendientes['items'], function ($item) {
            return $item['days_overdue'] <= 0;
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

    private function rangosyTotalVencidas(array $datosFacturasPendientes = [])
    {
        $facturas = collect($datosFacturasPendientes['items']);
        $totalVencidas = $facturas->sum(function ($factura) {
            return floatval($factura['amount_unpaid']);
        });
        //dd($totalVencidas);

        $totalAmountUnpaid_1_30 = $facturas
            ->filter(function ($factura) {
                return $factura['days_overdue'] >= 1 && $factura['days_overdue'] <= 30;
            })
            ->sum(function ($factura) {
                return (float) $factura['amount_unpaid'];
            });

        $totalAmountUnpaid_31_60 = $facturas
            ->filter(function ($factura) {
                return $factura['days_overdue'] >= 31 && $factura['days_overdue'] <= 60;
            })
            ->sum(function ($factura) {
                return (float) $factura['amount_unpaid'];
            });
        $totalAmountUnpaid_61_90 = $facturas
            ->filter(function ($factura) {
                return $factura['days_overdue'] >= 61 && $factura['days_overdue'] <= 90;
            })
            ->sum(function ($factura) {
                return (float) $factura['amount_unpaid'];
            });

        $totalAmountUnpaid_91_120 = $facturas
            ->filter(function ($factura) {
                return $factura['days_overdue'] >= 91 && $factura['days_overdue'] <= 120;
            })
            ->sum(function ($factura) {
                return (float) $factura['amount_unpaid'];
            });

        $totalAmountUnpaid_mayor_a_120 = $facturas
            ->filter(function ($factura) {
                return $factura['days_overdue'] > 120;
            })
            ->sum(function ($factura) {
                return (float) $factura['amount_unpaid'];
            });

        //dd([$totalAmountUnpaid_1_30, $totalVencidas]);

        return [
            'totalVencidas' => $totalVencidas,
            '1_30' => $totalAmountUnpaid_1_30,
            '31_60' => $totalAmountUnpaid_31_60,
            '61_90' => $totalAmountUnpaid_61_90,
            '91_120' => $totalAmountUnpaid_91_120,
            'mayor_a_120' => $totalAmountUnpaid_mayor_a_120,
        ];
    }


    //Funcion para mandar a llamar los clientes por codigo
    public function getClienteFacturasPendientes($codigo_cliente)
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
        Customer.custentitycodigo_cliente = '$codigo_cliente'
        ";

        return $this->querySuiteQL($query);
    }
}
