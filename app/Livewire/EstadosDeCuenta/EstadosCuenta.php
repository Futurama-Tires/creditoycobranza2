<?php

namespace App\Livewire\EstadosDeCuenta;

use App\Services\NetsuiteService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Services\FacturaStatsService;


class EstadosCuenta extends Component
{
    /* ────────────────────  Datos de la UI  ──────────────────── */
    public string $search = '';
    public array $suggestions = [];

    public ?int $clienteId = null;     // ID seleccionado
    public $clienteNombre = '';     // datos del cliente (altname, rfc …)
    public $codigoCliente = null;
    //Datos de factuas
    public $facturasVencidas = 0;
    public $facturasNoVencidas = 0;
    public $porcentajesFacturas = [];
    public $saldosFacturas = [];
    public $facturasVencidasTabla = null;
    public $saldosFacturasNumericos;

    //Datos de saldos a favor
    public $totalSaldoAFavor = 0;
    public $datosSaldoAFavor = [];
    public $datosSaldoAFavorProgress = [];

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

        $raw = $this->netsuite->suiteqlQuery($query);
        $this->suggestions = $raw['items'] ?? [];
    }

    /* ---------- 2) Cuando el usuario elige un cliente ---------- */
    public function selectCliente(int $id): void
    {
        $this->clienteId = $id;
        $this->search = '';          // limpia input
        $this->suggestions = [];

        #---- DATOS DE FACTURAS
        // Facturas pendientes
        $facturas = $this->getFacturasPendientes($id);
        Log::info($facturas);
        // --- Métricas --------------------------------------------------------------
        [$vencidas, $noVencidas] = $this->contarFacturas($facturas['items'] ?? []);
        $this->facturasVencidas = $vencidas;
        $this->facturasNoVencidas = $noVencidas;
        $this->porcentajesFacturas = $this->calcularPorcentajes($vencidas, $noVencidas);

        // ------- Saldos de facturas (Vencidas no vencidas)
        $statsService = new FacturaStatsService();
        $resultado = $statsService->calcular($facturas['items'] ?? []);

        // 1. Mantén los valores numéricos para cálculos
        $this->saldosFacturasNumericos = $resultado['subtotales']; // Valores numéricos

        # 1) Subtotales en vista
        $this->saldosFacturas = array_map(
            fn($v) => number_format($v, 2, '.', ','),
            $resultado['subtotales']
        );

        # 2) Datos a Chart.js
        $this->dispatch(
            'actualizar-grafico-fac-vencidas',
            $resultado['rangos']
        );

        #Facturas vencidas para tabla por rangos
        $this->facturasVencidasTabla = array_map(
            fn($v) => number_format($v, 2, '.', ','),
            $resultado['rangos']
        );

        #Tabla detalles de factura (saldos pendientes)
        $this->dispatch(
            'tabla-detalle-facturas',
            datos: array_map(function ($f) {
                return [
                    'transaction_date' => $f['transaction_date'] ?? null,
                    'document_number' => $f['document_number'] ?? null,
                    'folio_sat' => $f['folio_sat'] ?? null,
                    'due_date' => $f['due_date'] ?? null,
                    'days_overdue' => $this->getClientesDiasVencidos($f['due_date']),
                    'total_amount' => $f['total_amount'] ?? null,
                    'amount_unpaid' => $f['amount_unpaid'] ?? null,
                    'currency_name' => $f['currency_name'] ?? null,
                    'status' => $f['status'] ?? null,
                    'memo' => $f['memo'] ?? null,
                    'porcentaje_pagado' => $this->saldosFacturasNumericos['saldoTotal'] != 0
                        ? (($f['total_amount'] - $f['amount_unpaid']) * 100 / $f['total_amount'])
                        : 0
                ];
            }, $facturas['items'] ?? [])
        );

        # ---- DATOS DE SALDO A FAVOR
        // Pagos y NC con saldo
        $pagosNdc = $this->getPagosYNDCPendientes($id);
        $this->totalSaldoAFavor = $pagosNdc['total_payment_amount_unused'];

        //Tabla de saldo a favor
        $this->dispatch(
            'tabla-detalle-saldos-a-favor',
            datos: array_map(function ($p) {
                return [
                    'transaction_date' => $p['transaction_date'] ?? null,
                    'document_number' => $p['document_number'],
                    'folio_sat' => $p['folio_sat'] ?? null,
                    'total_amount' => $p['total_amount'],
                    'payment_amount_unused' => $p['payment_amount_unused'],
                    'currency_name' => $p['currency_name'],
                    'status' => $p['status'],

                ];
            }, $pagosNdc['items']) // Pasas el array como segundo parámetro
        );

        /************ */
        $items = $pagosNdc['items'];
        if (is_object($items) && method_exists($items, 'toArray')) {
            $items = $items->toArray();
        }

        $this->datosSaldoAFavorProgress = array_map(
            fn($p) => [
                'document_number' => $p['document_number'],
                'payment_amount_unused' => $p['payment_amount_unused'],
                'porcentaje' => $this->totalSaldoAFavor
                    ? ($p['payment_amount_unused'] * 100 / $this->totalSaldoAFavor)
                    : 0
            ],
            array_slice(
                collect($items)
                    ->sortByDesc('payment_amount_unused')
                    ->toArray(),
                0,
                5
            )
        );

        // --- Nombre del cliente ------------------------------    ----------------------
        $this->clienteNombre = $facturas['items'][0]['altname'] ?? '';
        $this->codigoCliente = $facturas['items'][0]['customer_code'] ?? null;
    }

    /**
     * Cuenta facturas vencidas y no vencidas en un solo recorrido.
     *
     * @return int[] [vencidas, noVencidas]
     */
    private function contarFacturas(array $items): array
    {
        $vencidas = $noVencidas = 0;

        foreach ($items as $item) {
            (($item['days_overdue'] ?? 0) > 0) ? $vencidas++ : $noVencidas++;
        }

        return [$vencidas, $noVencidas];
    }

    /**
     * Devuelve porcentajes redondeados a dos decimales.
     */
    private function calcularPorcentajes(int $vencidas, int $noVencidas): array
    {
        $total = $vencidas + $noVencidas;

        if ($total === 0) {
            return ['porcentajeVencido' => 0, 'porcentajeNoVencido' => 0];
        }

        return [
            'porcentajeVencido' => round(($vencidas / $total) * 100, 2),
            'porcentajeNoVencido' => round(($noVencidas / $total) * 100, 2),
        ];
    }

    public function descargarEstadoCuenta()
    {
        if (!$this->clienteId)
            return;

        return redirect()->route('estado-cuenta.descargar', ['id' => $this->clienteId]);
    }

    //Calcular días vencidos
    protected function getClientesDiasVencidos($fecha)
    {
        // Due date en formato d/m/Y
        if (isset($fecha)) {
            $dueDate = \Carbon\Carbon::createFromFormat('d/m/Y', $fecha);

            // Fecha actual
            //$hoy = \Carbon\Carbon::now(); // sin hora
            $hoy = \Carbon\Carbon::now()->endOfDay();

            // Diferencia en días
            $diasVencidos = $dueDate->diffInDays($hoy, false); // false = conserva signo

            return $diasVencidos;
        } else {
            return "-";
        }
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
            transaction_SUB.unused_payment_criteria > 0
            AND
                Customer.ID =  $id
                ";
        $resultado = $this->netsuite->suiteqlQuery($query);

        $suma = array_reduce(
            $resultado['items'] ?? [],
            fn($carry, $item) => $carry + (float) ($item['payment_amount_unused'] ?? 0),
            0
        );

        return [
            'items' => $resultado['items'] ?? [],
            'total_payment_amount_unused' => $suma
        ];
    }

    public function render()
    {
        return view('livewire.estados-de-cuenta.estados-cuenta');
    }
}
