<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'applications.view', 'applications.create', 'applications.update', 'applications.delete',
            'applications.assign_manager', 'applications.invite_beneficiary',
            'users.view', 'users.create', 'users.update', 'users.delete',
            'services.view', 'services.create', 'services.update', 'services.delete',
            'tickets.view', 'tickets.reply', 'tickets.assign', 'tickets.update_status',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $managerRole->givePermissionTo([
            'applications.view', 'applications.update', 'applications.assign_manager', 'applications.invite_beneficiary',
            'tickets.view', 'tickets.reply', 'tickets.update_status'
        ]);

        $userRole = Role::firstOrCreate(['name' => 'User']);
        $userRole->givePermissionTo(['applications.view', 'applications.create']);

        $users = User::all();
        foreach ($users as $user) {
            if ($user->role === 'admin') {
                $user->assignRole('Admin');
            } elseif ($user->role === 'manager') {
                $user->assignRole('Manager');
            } else {
                $user->assignRole('User');
            }
        }
    }
}