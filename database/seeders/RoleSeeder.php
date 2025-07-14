<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Creación de roles
        $rolAdmin = Role::create(['name' => 'admin']);
        $rolCobranza = Role::create(['name' => 'cobranza']);
        $rolCEDIS_vendedor = Role::create(['name' => 'cedis-vendedor']);
        $rolTesoreria = Role::create(['name' => 'tesoreria']);

        //Creación de permisos - Asignación de permisos a roles
        Permission::create(['name' => 'vista-identificacion-bbva'])->syncRoles([$rolAdmin, $rolCobranza, $rolTesoreria]);
        Permission::create(['name' => 'vista-conciliacion'])->syncRoles([$rolAdmin, $rolCobranza]);
        Permission::create(['name' => 'vista-estado-cuenta'])->syncRoles([$rolAdmin, $rolCobranza, $rolCEDIS_vendedor]);
    }
}
