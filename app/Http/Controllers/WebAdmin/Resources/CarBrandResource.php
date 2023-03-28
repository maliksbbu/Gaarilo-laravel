<?php

namespace App\Http\Controllers\WebAdmin\Resources;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Exception;
use Illuminate\Http\Request;

class CarBrandResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();
        return view('admin.car-make', compact('brands'));
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
                $image = $request->file("image")->store('/brands');
                $image = (new CommonController)->StorageFileURL($image);
            }

            $mark_popular = "NO";
            if($request->has('mark_popular') && $request->mark_popular == "on")
            {
                $mark_popular = "YES";
            }

            Brand::create([
                'name' => $request->name,
                'logo' => $image,
                'mark_popular' => $mark_popular,
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

            Brand::where('id',$id)->update([
                'name' => $request->name,
            ]);

            return redirect()->back()->with('notify_success', 'Vehicle make updated successfully');
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
            Brand::find($id)->delete();
            return redirect()->back()->with('notify_success', 'Vehicle make deleted successfully');
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage());
        }
    }
}
