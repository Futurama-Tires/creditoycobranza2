<?php

namespace App\Livewire\EstadosDeCuenta;

use App\Services\NetsuiteService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;


class EstadosCuenta extends Component
{
    /* ────────────────────  Datos de la UI  ──────────────────── */
    public string $search = '';
    public array $suggestions = [];

    public ?int   $clienteId = null;     // ID seleccionado
    public ?array $cliente   = null;     // datos del cliente (altname, rfc …)
    public array  $facturas  = [];
    public array  $pagosNdc  = [];
    public $count = 0;

    /* ────────────────────  Servicio Netsuite  ──────────────────── */
    protected NetsuiteService $netsuite;

    /** 
     * Livewire inyecta dependencias en `mount()` igual que lo hace
     * Laravel en un controlador.  :contentReference[oaicite:1]{index=1}
     */
    public function boot(NetsuiteService $netsuite): void   // ← corre SIEMPRE
    {
        $this->netsuite = $netsuite;
    }


    /* ---------- 1) Autocompletado de clientes ---------- */
    public function updatedSearch(): void
    {
        Log::info($this->search);
        // Limpia en cada pulsación
        $texto = strtoupper(trim($this->search));

        if (strlen($texto) < 3) {            // espera 3 caracteres
            $this->suggestions = [];
            return;
        }

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
        Customer.altname LIKE '" . '%' . addslashes($texto) . '%' . "'
    ORDER BY 
        altname ASC";

        $raw =  $this->netsuite->suiteqlQuery($query);
        $this->suggestions = $raw['items'] ?? [];
    }

    /* ---------- 2) Cuando el usuario elige un cliente ---------- */
    public function selectCliente(int $id): void
    {
        $this->clienteId = $id;
        $this->search    = '';          // limpia input
        $this->suggestions = [];
        

        // 2.2 Facturas pendientes
        $this->facturas = $this->getFacturasPendientes($id);

        // 2.3 Pagos y NC con saldo
        $this->pagosNdc = $this->getPagosYNDCPendientes($id);

        Log::info([$this->facturas, $this->pagosNdc ]);
    }

    /* ---------- 3) Consultas “tal cual” traídas de tu controlador ---------- */
    protected function getFacturasPendientes(int $id): array
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
        Customer.ID =  $id
        ";

        return $this->netsuite->suiteqlQuery($query);
    }

    protected function getPagosYNDCPendientes(int $id): array
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
        Customer.ID =  $id
        ";

        return $this->netsuite->suiteqlQuery($query);
    }

    public function render()
    {
        return view('livewire.estados-de-cuenta.estados-cuenta');
    }
}
