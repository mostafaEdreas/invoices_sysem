<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function paginateUsers()
    {
        $paginate_number = config('app.paginate_number', 10);
        return User::with('roles')->paginate($paginate_number); 
    }

    public function getUserById(int $id)
    {
        return User::findOrFail($id);
    }
    public function createUser($data)
    {
         $data['password'] = Hash::make($data['password']);

        $user = User::create($data) ;

        $roles = $data['role'] ?? []   ;
        $user->roles()->sync($roles);

        return $user;
    
    }


    public function updateUser(int $id ,array $data)
    {
        $user = $this->getUserById($id);
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        
        $user->update($data);

        $roles = $data['role'] ?? []   ;
        $user->roles()->sync($roles);

        return $user;
    }

    public function deleteUser(int $id)
    {
        $user = $this->getUserById($id,);
        $user->validateNoDependencies();
        return $user->delete();
    }
    public function employeesInvoicesReport()
    {
      return User::role('employee')
    ->withCount('invoices')
    ->withSum('invoices as total_sales', 'total') 
    ->get(['id', 'name']); 
       
    }

}