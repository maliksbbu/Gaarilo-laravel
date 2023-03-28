<?php

namespace App\Http\Controllers\WebAdmin\Resources;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class RoleResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rights = Role::$roleRights;
        return view('admin.roles.create', compact('rights'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles',
            'checked_roles' => 'required',
        ]);
        try
        {
            $roles_json = json_encode($request->checked_roles);
            $role = Role::create([
                'name' => $request->name,
                'role_json' => $roles_json,
            ]);
            return redirect()->route('admin.roles.index')->with('notify_success', 'Role created successfully.');
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
        $rights = Role::$roleRights;
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role', 'rights'));
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
        $request->validate([
            'checked_roles' => 'required',
        ]);

        try
        {
            $role = Role::where('id', $id)->first();
            $roles_json = json_encode($request->checked_roles);
            $role->update([
                'role_json' => $roles_json,
            ]);
            return redirect()->back()->with('notify_success', 'Role updated successfully.');
        }
        catch (Exception $e)
        {
            return back()->with('notify_error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
