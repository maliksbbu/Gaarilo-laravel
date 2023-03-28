<?php

namespace App\Http\Controllers\WebAdmin\Resources;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Exception;
use Illuminate\Http\Request;

class VehicleTypeResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicles = VehicleType::all();
        return view('admin.vehicle-type', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        ]);
        try {

            $image = "";
            if ($request->hasFile('image')) {
                $image = $request->file("image")->store('/vehicle-type');
                $image = (new CommonController)->StorageFileURL($image);
            }

            VehicleType::create([
                'name' => $request->name,
                'image' => $image,
            ]);

            return redirect()->back()->with('notify_success', 'Vehicle type added successfully');
        } catch (Exception $e) {
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
        //
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
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);
        try {

            VehicleType::where('id', $id)->update([
                'name' => $request->name,
            ]);

            return redirect()->back()->with('notify_success', 'Vehicle type updated successfully');
        } catch (Exception $e) {
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
        try {
            VehicleType::find($id)->delete();
            return redirect()->back()->with('notify_success', 'Vehicle type deleted successfully');
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage());
        }
    }
}
