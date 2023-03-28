<?php

namespace App\Http\Controllers\WebAdmin\Resources;

use App\Http\Controllers\Controller;
use App\Models\Models;
use App\Models\Version;
use Exception;
use Illuminate\Http\Request;

class CarVersionResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = Models::paginate(10);
        return view('admin.car-version', compact('models'));
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
            'model_id' => 'required|exists:models,id',
            'from_year' => 'required|numeric|not_in:0',
            'to_year' => 'required|numeric|not_in:0',
        ]);
        try {

            $mark_popular = "NO";
            if ($request->has('mark_popular') && $request->mark_popular == "on") {
                $mark_popular = "YES";
            }

            Version::create([
                'name' => $request->name,
                'model_id' => $request->model_id,
                'from_year' => $request->from_year,
                'to_year' => $request->to_year,
                'mark_popular' => $mark_popular,
            ]);

            return redirect()->back()->with('notify_success', 'Vehicle version updated successfully');
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
            'model_id' => 'required|exists:models,id',
            'from_year' => 'required|numeric',
            'to_year' => 'required|numeric',
        ]);
        try {

            Version::where('id', $id)->update([
                'name' => $request->name,
                'model_id' => $request->model_id,
                'from_year' => $request->from_year,
                'to_year' => $request->to_year,

            ]);

            return redirect()->back()->with('notify_success', 'Vehicle version updated successfully');
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
            Version::find($id)->delete();
            return redirect()->back()->with('notify_success', 'Vehicle Version deleted successfully');
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage());
        }
    }
}
