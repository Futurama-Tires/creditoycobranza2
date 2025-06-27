<?php

namespace App\Http\Controllers\EstadosDeCuenta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetsuiteService;

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
        $dataset = 'custdataset59'; // Tu dataset específico
        $data = $this->netsuite->queryDataset($dataset);
        
        return response()->json($data);
    }

    public function index()
    {
        return view('estados_de_cuenta.index');
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
