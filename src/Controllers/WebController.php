<?php

namespace Nksoft\Master\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nksoft\Master\Models\FilesUpload;

class WebController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master::layout', ['element' => 'list']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master::layout', ['element' => 'create']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        return view('master::layout', ['element' => 'edit']);
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

    public function setMedia($images, $parent_id, $type)
    {
        if (isset($images)) {
            foreach ($images as $file) {
                if ($file->isValid()) {
                    $name = $file->getClientOriginalName();
                    $name = \str_slug($name, '-');
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $name . '-' . time() . '.' . $extension;
                    $path = putUploadImage($file, $fileName);
                    FilesUpload::create([
                        'image' => $path,
                        'type' => $type,
                        'parent_id' => $parent_id,
                        'name' => $name,
                        'order_by' => 0,
                    ]);
                }
            }
        }
    }
}
