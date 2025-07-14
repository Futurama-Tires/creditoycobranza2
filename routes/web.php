<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\EstadosDeCuenta\EstadosDeCuentaController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    return view('auth.login');
});

Route::get('/dashboard', function () {

    $user = auth()->user();



    if ($user->tipo == "cliente") {
        //dd([$user->tipo, " en if"]);

        return redirect()->route('fac-vista-cliente');
    } else {
        //dd([$user->tipo, " en else"]);
        //Obtener saludo dependiendo el día
        $hour = now()->hour;
        $greeting = match (true) {
            $hour < 12 => '¡Buenos días',
            $hour < 18 => '¡Buenas tardes',
            default => '¡Buenas noches',
        };


        return view('dashboard', compact('greeting'));
    }
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'empleado'])->group(function () {

    Route::get('/prueba', function () {
        return view('pages.prueba');
    });

    Route::get('/clientes/importar', [ClienteController::class, 'showImportForm'])->name('clientes.importar');
    Route::post('/clientes/importar', [ClienteController::class, 'import'])->name('clientes.importar.post');
    Route::get('/clientes/procesar', function () {
        return view('clientes.procesar'); // Ensure the Blade file is named `upload_excel.blade.php` and placed in the `resources/views` folder
    })->name('clientes.procesar');
    Route::post('/process-excel', [ExcelController::class, 'processExcel']);

    //-----------------------------    CONCILIACION DE PAGOS RUTAS    ------------------------------------------------------------------
    Route::resource('conciliacion_pagos', PagosController::class)->names('conciliacion_pagos');
    Route::post('conciliacion_pagos/importarArchivos', [PagosController::class, 'importBancosAndNetsuiteReporte'])
        ->name('conciliacion_pagos-importBancosAndNetsuiteReporte');
    Route::get('exportar-conciliacion-pagos', [PagosController::class, 'exportConciliacionPagos'])->name('conciliacion-pagos-export');
    Route::get('pendientes-exportar-conciliacion-pagos', [PagosController::class, 'depositosPendientesExportConciliacionPagos'])->name('pendientes-conciliacion-pagos-export');
    Route::get('importar-facturas', [PagosController::class, 'importarFacturas'])->name('importar-facturas');
    Route::post('importar-facturas-usuario', [PagosController::class, 'importarFacturasUsuario'])->name('importar-facturas-usuario');
    Route::get('importar-facturas-usuario-vista', [PagosController::class, 'importarFacturasUsuarioVista'])->name('importar-facturas-usuario-vista');

    //-----------------------------    ESTADOS DE CUENTA    ------------------------------------------------------------------
    Route::resource('estados_de_cuenta', EstadosDeCuentaController::class)->names('estados_de_cuenta');
    Route::post('/netsuite/get-fac-pag-ndc', [EstadosDeCuentaController::class, 'getFacturasPagosNDC'])->name('getFacturasPagosNDC');
    Route::get('/netsuite/get-customers', [EstadosDeCuentaController::class, 'getCustomers']);
    Route::get('/load-customer-data', [EstadosDeCuentaController::class, 'loadCustomerData']);
    Route::get('/estado-cuenta/descargar/{customer_code}', [EstadosDeCuentaController::class, 'downloadExcelEstadoDeCuenta'])
        ->name('estado-cuenta.descargar');
    Route::resource('users', UserController::class);


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/exportar-informacion', [EstadosDeCuentaController::class, 'downloadExcelCliente'])
        ->middleware(['auth', 'verified'])
        ->name('exportar-informacion');
});

Route::get('/netsuite/get-fac-vista-cliente', [EstadosDeCuentaController::class, 'getFacturasVistaCliente'])
    ->middleware(['auth', 'verified'])
    ->name('fac-vista-cliente');


require __DIR__ . '/auth.php';
