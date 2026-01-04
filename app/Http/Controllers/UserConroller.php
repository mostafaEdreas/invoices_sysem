<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserConroller extends Controller
{
    use AuthorizesRequests;
    public UserService $userService;
    public function __construct( UserService $userService){
        $this->userService = $userService;  
    }
    public function index()
    {
        $this->authorize('view_all_users');
        $data['users'] = $this->userService->paginateUsers();
        return view('users.index', $data);
    }
    public function create()
    {
        $this->authorize('create_users');
        $data['roles'] = Role::all();
        return view('users.create' , $data);
    }
    public function store(UserRequest $request)
    {
        $this->authorize('create_users');
        $data = $request->validated();
        $this->userService->createUser($data);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $this->authorize('update_users');
        $data['user'] = $this->userService->getUserById($id);
        $data['roles'] = Role::all();
        return view('users.edit', $data);
    }       
    public function update( UserRequest $request, $id   )
    {
        $this->authorize('update_users');
        $data = $request->validated();
        $this->userService->updateUser($id, $data);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $this->authorize('delete_users');
        $this->userService->deleteUser($id);
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }  
    
    public function employeesInvoicesReport()
    {
        $this->authorize('report_users');
        $data['report'] = $this->userService->employeesInvoicesReport();
        return view('users.employees_invoices', $data);
    }
}
