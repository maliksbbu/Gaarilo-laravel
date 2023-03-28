<?php

namespace App\Http\Controllers\WebAdmin\Resources;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Exception;
use Illuminate\Http\Request;

class ColorResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = Color::all();
        return view('admin.colors',compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'hexa' => 'required|string'
        ]);
        try {

            Color::create([
                'name' => $request->name,
                'hexa' => $request->hexa,
            ]);

            return redirect()->back()->with('notify_success', 'Color added successfully');
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
            'hexa' => 'required|string'
        ]);
        try {

            Color::where('id', $id)->update([
                'name' => $request->name,
                'hexa' => $request->hexa,
            ]);

            return redirect()->back()->with('notify_success', 'Color updated successfully');
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
            Color::find($id)->delete();
            return redirect()->back()->with('notify_success', 'Color deleted successfully');
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage());
        }
    }
}
