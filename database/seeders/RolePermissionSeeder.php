<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     // define permissions aliases
    private  $permissions = [
            'c'  => 'create',
            'v'  => 'view',
            'u'  => 'update',
            'd'  => 'delete',
            'p'  => 'print',
            'va' => 'view_all',
            'r'  => 'report',
        ];


        // define roles with their permissions
    private   $roles_has_permissions = [
            'admin' => [
                'products'    => 'c,v,u,d,p,va,r',
                'invoices'    => 'v,va,r',
                'customers'   => 'c,v,u,d,p,va,r',
                'roles'       => 'c,v,u,d,va,r',
                'permissions' => 'c,v,u,d,va,r',
                'users'       => 'c,v,u,d,va,r',
            ],

            'employee' =>[
                'invoices' => 'c,v,p,va',
            ]
            
        ];
    public function run(): void
    {
        $roles['admin'] = Role::firstOrCreate(['name' => 'admin']);
        $roles['employee'] = Role::firstOrCreate(['name' => 'employee']);
       
        
        // loop through roles and assign permissions
        foreach ($roles as $role) {
            // get permissions for current role 
            foreach ($this->roles_has_permissions[$role->name] as $model =>  $permissions_alias) {
              
                $this->createPermissionsForRole($role, $model, explode(',', $permissions_alias));
                
              
            }
        }
    }


    private function createPermissionsForRole($role, $model, $permissions_alias)
    {
        foreach ($permissions_alias as $alias) {
            $permission_name = $this->permissions[$alias] . '_' . $model;
            // create permission if not exists
            $permission = Permission::firstOrCreate(['name' => $permission_name]);
            // assign permission to role
            $role->givePermissionTo($permission);
        }
    }
}
