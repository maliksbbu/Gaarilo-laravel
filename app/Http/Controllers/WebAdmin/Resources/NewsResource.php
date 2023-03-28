<?php

namespace App\Http\Controllers\WebAdmin\Resources;

use App\Http\Controllers\Controller;
use App\Models\News;
use Exception;
use Illuminate\Http\Request;

class NewsResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::all();
        return view('admin.news-tips', compact('news'));
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
            'title' => 'required|string',
            'text' => 'required|string',
        ]);
        try {
            $important = "NO";
            if($request->important == "on")
            {
                $important = "YES";
            }

            News::create([
                'title' => $request->title,
                'text' => $request->text,
                'important' => $important,
            ]);

            return redirect()->back()->with('notify_success', 'News added successfully');
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
        News::find($id)->update(['important'=> 'NO']);
        return redirect()->back()->with('notify_success', 'News added successfully');
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
            'text' => 'required|string',
        ]);
        try {
            News::where('id', $id)->update([
                'text' => $request->text,
            ]);
            return redirect()->back()->with('notify_success', 'News updated successfully');
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
            News::find($id)->delete();
            return redirect()->back()->with('notify_success', 'News deleted successfully');
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage());
        }
    }
}
