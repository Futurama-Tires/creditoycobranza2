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

        $altname = "ALEJO GAMA RODRIGUEZ"; // El altname que deseas filtrar

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

    public function getClientNames(Request $request)
    {
        $dataset = 'custdataset67'; // Tu dataset específico DA LOS NEGATIVOS
        $data = $this->netsuite->queryDataset($dataset);
        $nombres = collect($data['items'])->pluck('altname');
        Log::info($nombres);
        return $nombres;
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
}
