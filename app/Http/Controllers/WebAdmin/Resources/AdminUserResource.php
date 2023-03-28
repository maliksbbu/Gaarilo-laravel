<?php

namespace App\Http\Controllers\WebAdmin\Resources;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class AdminUserResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Admin::all();
        return view('admin.admin-users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.admin-users.create' , compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6|same:password',
            'role_id' => 'required',
        ]);
        try
        {
            $checkRole = Role::where('id', $request->role_id)->first();
            if(empty($checkRole) && $checkRole->id != 0)
            {
                return back()->with('notify_error', 'Role not found.');
            }
            Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => $request->role_id,
            ]);

            return redirect()->route('admin.admin-users.index')->with('notify_success', 'Admin user created successfully');
        }
        catch (Exception $e)
        {
            return back()->with('notify_error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $user = auth()->guard('admin')->user();
            if ($user->role_id != "0") {
                return back()->with('notify_error', "Only super admin should be able to delete admins");
            }


            Admin::find($id)->delete();
            return redirect()->back()->with('notify_success', 'Admin user deleted successfully');
        }
        catch (Exception $e)
        {
            return back()->with('notify_error', $e->getMessage());
        }
    }
}
