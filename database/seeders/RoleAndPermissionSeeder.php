<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = config('access.permissions', []);

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roles = config('access.roles', []);

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            if (!empty($rolePermissions)) {
                $role->syncPermissions($rolePermissions);
            }
        }

        // Super-admin gets all permissions
        $superAdmin = Role::findByName('super-admin');
        $superAdmin->syncPermissions(Permission::all());

        // Create default admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@control-acceso.test'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('Admin123!'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $admin->assignRole('super-admin');

        // Create default guard user
        $guardia = User::firstOrCreate(
            ['email' => 'guardia@control-acceso.test'],
            [
                'name' => 'Guardia Portero',
                'password' => bcrypt('Guardia123!'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $guardia->assignRole('guardia');

        // Create default host user
        $anfitrion = User::firstOrCreate(
            ['email' => 'anfitrion@control-acceso.test'],
            [
                'name' => 'Anfitrión Ejemplo',
                'password' => bcrypt('Anfitrion123!'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $anfitrion->assignRole('anfitrion');
    }
}
