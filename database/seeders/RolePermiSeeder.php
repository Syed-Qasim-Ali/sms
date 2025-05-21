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
            'Super Admin',
            'Construction Contractor Admin',
            'Construction Contractor Site Contact',
            'Construction Contractor Accounts Payable',
            'Trucking Contractor Admin',
            'Trucking Contractor Dispatcher',
            'Trucking Contractor Driver',
            'Trucking Contractor Accounts Receivable',
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
            'company-list',
            'company-create',
            'company-edit',
            'company-delete',
            'invoice',
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
        $adminRole = Role::where('name', 'Super Admin')->first();
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
        $adminuser = User::find(1);
        if ($adminuser) {
            $adminuser->assignRole('Super Admin');
        } else {
            $this->command->warn('User with ID 1 not found.');
        }
        $user = User::create([
            'name' => 'driver',
            'email' => 'driver@gmail.com',
            'status' => 'active',
            'password' => bcrypt('12345678')
        ]);
        $user->assignRole('Trucking Contractor Driver');
    }
}
