<?php

namespace App\Http\Controllers\EstadosDeCuenta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetsuiteService;
use Illuminate\Support\Facades\Log;


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

    public function getCustomerData()
    {
        //$dataset = 'custdataset59'; // Tu dataset específico
        //$dataset = 'custdataset65'; // Tu dataset específico DA LOS POSITIVOS
        $dataset = 'custdataset66'; // Tu dataset específico DA LOS NEGATIVOS
        $data = $this->netsuite->queryDataset($dataset);

        $altname = "ZUNYEN REYES NAVARRETE"; // El altname que deseas filtrar

        // Filter the items by entityid:
        // $filteredItems = collect($data['items'])->filter(function ($item) use ($entityId) {
        //     // Trim quotes if needed:
        //     return trim($item['entityid'], '"') === $entityId;
        // })->values();

        // return response()->json([
        //     'count' => $filteredItems->count(),
        //     'items' => $filteredItems,
        // ]);

        //Filter the items by entityid:

        // $filteredItems = collect($data['items'])->filter(function ($item) use ($altname) {
        //     // Trim quotes if needed:
        //     return trim($item['altname'], '"') === $altname;
        // })->values();

        $coleccion_clientes = collect($data['items']);


        $resultado = $coleccion_clientes->filter(function ($item) use ($altname) {
            // Use stripos() for case-insensitive contains
            return stripos($item['altname'], $altname) !== false;
        })->values();
        // return response()->json([
        //     'count' => $filteredItems->count(),
        //     'items' => $filteredItems,
        // ]);


        return response()->json($resultado);
    }

    public function index()
    {


        return view('estados_de_cuenta.index');
    }

    // public function getClientNames(Request $request)
    // {
    //     $dataset = 'custdataset67';
    //     $query = $request->input('query', '');

    //     $limit = 1000;
    //     $offset = 0;
    //     $allItems = [];

    //     do {
    //         $data = $this->netsuite->queryDataset($dataset, $limit, $offset);

    //         if (empty($data) || empty($data['items'])) {
    //             Log::info("No more data or empty result at offset {$offset}");
    //             break;
    //         }

    //         Log::info("Fetched " . count($data['items']) . " items at offset {$offset}");

    //         $items = $data['items'];
    //         $allItems = array_merge($allItems, $items);

    //         $offset += $limit;
    //     } while (count($items) === $limit);

    //     Log::info("Total items fetched: " . count($allItems));

    //     $filtered = collect($allItems)->filter(function ($item) use ($query) {
    //         $name = $item['altname'] ?? '';
    //         Log::info("Checking name: {$name} against query: {$query}");
    //         return stripos($name, $query) !== false;
    //     })->pluck('altname');

    //     Log::info("Filtered results count: " . $filtered->count());

    //     return $filtered->values();
    // }






    /**
     * Show the form for creating a new resource.
     */

    public function getClientNames()
    {
        $clientName = "A & M REFACCIONES Y SERVICIOS AUTOMOTRICES";
        set_time_limit(180);
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
        Customer.altname LIKE '" . '%' . addslashes($clientName) . '%' . "'
        ";

        $result = $this->netsuite->suiteqlQuery($query);

        dd($result);
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
}
