<?php

namespace App\Http\Controllers;



use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->where('tipo', 'cliente')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:255',
            'codigo_cliente' => 'required|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'codigo_cliente' => $request->codigo_cliente,
        ]);
        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }



    // public function import(Request $request)
    // {


    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,csv'
    //     ]);

    //     Excel::import(new UsersImport, $request->file('file'));

    //     return back()->with('success', 'Usuarios importados correctamente.');
    // }

    /*public function import(Request $request)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,ods'
        ]);

        $path = $request->file('file')->store('temp');
        $fullPath = storage_path('app/' . $path);

        $batchSize = 500;

        DB::beginTransaction();

        try {
            // Correct approach - use the LazyCollection directly from SimpleExcelReader
            SimpleExcelReader::create($fullPath)
                ->skip(1)
                ->getRows()
                ->chunk($batchSize)
                ->each(function ($chunk) {
                    DB::transaction(function () use ($chunk) {
                        $insertData = [];

                        foreach ($chunk as $row) {
                            $insertData[] = [
                                'name' => trim($row['cliente']),
                                'codigo_cliente' => trim($row['codigo_cliente']),
                                'email' => trim($row['email']),
                                'password' => Hash::make(trim($row['passwords'])),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        User::insert($insertData);
                    });
                });

            DB::commit();
            return back()->with('success', 'Usuarios importados correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $fullPath
            ]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }*/

    public function import(Request $request)
    {
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 3000);

        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,csv,ods'
            ]);

            $rows = SimpleExcelReader::create($request->file('file')->getPathName(), type: 'xlsx')
                ->noHeaderRow()
                ->skip(1)
                ->getRows()
                ->map(fn($row) => [
                    'name' => $row[0] ?? null,
                    'apellido' => null,
                    'puesto' => null,
                    'foto' => null,
                    'email' => trim($row[2]) ?? null,
                    'email_verified_at' => now(),
                    // Opción 1: misma contraseña temporal para todos
                    'password' => bcrypt($row[3]),
                    'tipo' => 'cliente',
                    'codigo_cliente' => trim($row[1]),
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
                ->toArray();

            DB::beginTransaction();

            foreach (array_chunk($rows, 1000) as $chunk) {
                DB::table('users')->insert($chunk);
            }

            DB::commit();
            return back()->with('success', 'Usuarios importados correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        } finally {
            Storage::delete($request->file('file')->getPathname());
        }
    }
}
