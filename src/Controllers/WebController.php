<?php

namespace Nksoft\Master\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nksoft\Master\Models\FilesUpload;
use Str;

class WebController extends Controller
{
    protected $module = '';

    public function responseError($message = null)
    {
        return response()->json([
            'status' => 'error',
            'data' => null,
            'success' => false,
            'message' => $message,
        ]);
    }

    public function responseSuccess(array $data = [])
    {
        return response()->json([
            'status' => 'success',
            'message' => [
                'default' => trans('nksoft::message.Success'),
            ],
            'data' => $data,
            'breadcrumb' => $this->breadcrumb(),
            'button' => trans('nksoft::common.Button')
        ]);
    }

    public function status()
    {
        $status = [];
        foreach (config('nksoft.status') as $v => $k) {
            $status[] = ['id' => $k['id'], 'name' => trans('nksoft::common.' . $k['name'])];
        }
        return $status;
    }

    public function breadcrumb()
    {
        $segment = request()->segments();
        $segment = array_slice($segment, 1, 4);
        $breadcrumb = [];
        foreach ($segment as $i => $item) {
            $link = url($item);
            if ($i > 0) {
                $link = url(implode('/', array_slice($segment, 0, 2)));
            }
            if (!intval($item)) {
                $breadcrumb[] = [
                    'title' => trans('nksoft::common.' . $item),
                    'link' => $link,
                ];
            }

        }
        return [
            'title' => trans('nksoft::common.' . $this->module),
            'breadcrumb' => $breadcrumb,
        ];
    }

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
        $validator = Validator($request->all(), ['name' => 'required'], ['name.required' => __('nksoft::message.Field is require!', ['Field' => trans('nksoft::users.Username')])]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator]);
        }
        $image = FilesUpload::find($id);
        if ($image != null) {
            $image->name = $request->get('name');
            $image->save();
        }
        $response = $this->responseSuccess();
        return response()->json($response);
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
            FilesUpload::find($id)->delete();
            $response = $this->responseSuccess();
        } catch (\Exception $e) {
            $response = $this->responseError($e);
        }
        return response()->json($response);
    }

    public function setMedia($images, $parent_id, $type)
    {
        if (isset($images)) {
            foreach ($images as $file) {
                if ($file->isValid()) {
                    $name = $file->getClientOriginalName();
                    $name = Str::slug($name, '-');
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
