<?php

namespace App\Http\Controllers\WebAdmin\Resources;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Models;
use App\Models\VehicleType;
use Exception;
use Illuminate\Http\Request;

class CarModelResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();
        $types = VehicleType::all();
        return view('admin.car-model', compact('brands','types'));
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
            'brand_id' => 'required|exists:brands,id',
            'type_id' => 'required|exists:vehicle_types,id',
        ]);
        try {

            $mark_popular = "NO";
            if ($request->has('mark_popular') && $request->mark_popular == "on") {
                $mark_popular = "YES";
            }

            Models::create([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'type_id' => $request->type_id,
                'mark_popular' => $mark_popular,

            ]);

            return redirect()->back()->with('notify_success', 'Vehicle model updated successfully');
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
            'brand_id' => 'required|exists:brands,id',
            'type_id' => 'required|exists:vehicle_types,id',
        ]);
        try {
            Models::where('id',$id)->update([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'type_id' => $request->type_id,

            ]);
            return redirect()->back()->with('notify_success', 'Vehicle model updated successfully');
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
            Models::find($id)->delete();
            return redirect()->back()->with('notify_success', 'Vehicle make deleted successfully');
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage());
        }
    }
}
