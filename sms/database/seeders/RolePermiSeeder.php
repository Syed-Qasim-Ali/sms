<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;


class RolePermiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define all roles
        $roles = [
            'super-admin',
            'construction-contractor-admin',
            'construction-contractor-site-contact',
            'construction-contractor-accounts-payable',
            'trucking-contractor-admin',
            'trucking-contractor-dispatcher',
            'trucking-contractor-driver',
            'trucking-contractor-accounts-receivable',
        ];

        // Create roles
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Define permissions
        $permissions = [
            'users-list',
            'users-create',
            'users-edit',
            'users-delete',
            'roles-list',
            'roles-create',
            'roles-edit',
            'roles-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            'company-list',
            'company-create',
            'company-edit',
            'company-delete',
            'invoice',
            'capabilities-list',
            'capabilities-create',
            'capabilities-edit',
            'capabilities-delete',
            'specialties-list',
            'specialties-create',
            'specialties-edit',
            'specialties-delete',
            'trucks-list',
            'trucks-create',
            'trucks-edit',
            'trucks-delete',
            'jobs-list',
            'jobs-create',
            'jobs-edit',
            'jobs-delete',
            'orders-list',
            'orders-create',
            'orders-edit',
            'orders-delete',
            'reports'
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to super-admin
        $adminRole = Role::where('name', 'super-admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::all());
        } else {
            $this->command->warn('Super Admin role was not found.');
        }

        // Optionally: assign selected permissions to other roles later if needed
        // Example:
        // $siteContact = Role::where('name', 'construction-contractor-site-contact')->first();
        // $siteContact->givePermissionTo(['jobs-list', 'orders-list']);

        // Assign super-admin role to user ID 1
        $user = User::find(1);
        if ($user) {
            $user->assignRole('super-admin');
        } else {
            $this->command->warn('User with ID 1 not found.');
        }
    }
}
