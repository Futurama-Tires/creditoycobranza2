<?php

namespace App\Services;

class FacturaStatsService
{
    /**
     * Definición de rangos (label => [mín, máx]).
     * Agrega, quita o reordena rangos sin tocar la lógica.
     */
    private const RANGOS_VENCIDOS = [
        'noPagado_1_30'    => [1,  30],
        'noPagado_31_60'   => [31,  60],
        'noPagado_61_90'   => [61,  90],
        'noPagado_91_120'  => [91, 120],
        'noPagado_mas_120' => [121, PHP_INT_MAX],
    ];

    /* ===================================================
     *  API PÚBLICA
     * =================================================== */

    public function calcular(array $items): array
    {
        // ----- Inicializa acumuladores -----
        $rangos            = array_fill_keys(array_keys(self::RANGOS_VENCIDOS), 0.0);
        $subtotalVencido   = 0.0;
        $subtotalNoVencido = 0.0;

        // ----- Único recorrido -----
        foreach ($items as $item) {
            $dias   = (int) ($item['days_overdue']   ?? 0);
            $monto  = (float)($item['amount_unpaid'] ?? 0);

            if ($dias > 0) {                         // factura vencida
                $subtotalVencido += $monto;
                $this->acumularEnRango($rangos, $dias, $monto);
            } else {                                 // dentro de término
                $subtotalNoVencido += $monto;
            }
        }

        $subtotales = [
            'subtotalVencido'    => $subtotalVencido,
            'subtotalNoVencido'  => $subtotalNoVencido,
            'saldoTotal'         => $subtotalVencido + $subtotalNoVencido,
        ];

        return compact('subtotales', 'rangos');
    }

    /* ===================================================
     *  MÉTODOS PRIVADOS
     * =================================================== */

    private function acumularEnRango(array &$rangos, int $dias, float $monto): void
    {
        foreach (self::RANGOS_VENCIDOS as $label => [$min, $max]) {
            if ($dias >= $min && $dias <= $max) {
                $rangos[$label] += $monto;
                break; // termina cuando encuentra su rango
            }
        }
    }
}
