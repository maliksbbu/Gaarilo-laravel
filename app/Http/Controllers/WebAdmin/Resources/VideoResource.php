<?php

namespace App\Http\Controllers\WebAdmin\Resources;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Models;
use App\Models\Video;
use Exception;
use Illuminate\Http\Request;

class VideoResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $models = Models::all();
        return view('admin.video',compact('models'));
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
            'url' => 'required|string|max:255',
            'model_id' => 'required',
            'image' => 'required',
        ]);

        try {

            $image = "";
            if ($request->hasFile('image')) {
                $image = $request->file("image")->store('/vehicle-type');
                $image = (new CommonController)->StorageFileURL($image);
            }

            $modelName = "";
            $brandName = "";
            $versionName = "";
            $model = Models::where('id', $request->model_id)->with('brand')->first();
            if(!empty($model))
            {
                $modelName = $model->name;
                $brandName = $model->brand->name;
            }

            if($request->has('version_id') && $request->filled('version_id'))
            {
                $versionName = $request->version_id;
            }

            Video::create([
                'name' => $request->name,
                'image' => $image ,
                'url' => $request->url ,
                'brand_model_version' => $brandName.' '.$modelName.' '.$versionName ,
            ]);

            return redirect()->back()->with('notify_success', 'Video Review added successfully');


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
        //
    }
}
