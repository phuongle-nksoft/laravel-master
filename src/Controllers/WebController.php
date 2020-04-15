<?php

namespace Nksoft\Master\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Nksoft\Master\Models\FilesUpload;
use Nksoft\Master\Models\Histories;
use Nksoft\Master\Models\UrlRedirects;
use Str;

class WebController extends Controller
{
    /** Variable module */
    protected $module = '';

    protected $model = CurrentModel::class;

    /** Variable exclude  */
    protected $excludeCol = ['images', 'banner', 'id'];

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
            'button' => trans('nksoft::common.Button'),
            'canDelete' => Auth::user()->role_id == 1,
        ]);
    }

    public function responseViewSuccess(array $data = [])
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'breadcrumb' => $this->breadcrumb(),
        ]);
    }

    public function validateDate($date, $format = 'm/d/Y')
    {
        $d = \DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
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
                    'title' => trans('nksoft::common.' . Str::slug($item, " ")),
                    'link' => $link,
                ];
            }

        }
        return [
            'title' => trans('nksoft::common.' . Str::slug($this->module, ' ')),
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
            $image->order_by = $request->get('order_by');
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
    public function destroyImage($id)
    {
        try {
            FilesUpload::find($id)->delete();
            $response = $this->responseSuccess();
        } catch (\Exception $e) {
            $response = $this->responseError($e);
        }
        return response()->json($response);
    }

    /**
     * function insert media
     */
    public function setMedia($images, $parent_id, $type, $group_id = 1)
    {
        if (isset($images)) {
            foreach ($images as $file) {
                if ($file->isValid()) {
                    $name = request()->get('name') ?? $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $fileName = Str::slug($file->getClientOriginalName(), '-') . '-' . rand(3, time()) . '.' . $extension;
                    $path = putUploadImage($file, $fileName);
                    FilesUpload::create([
                        'image' => $path,
                        'type' => $type,
                        'parent_id' => $parent_id,
                        'name' => $name,
                        'group_id' => $group_id,
                        'order_by' => 0,
                    ]);

                }
            }
        }
    }

    public function getSlug(array $data)
    {
        try {
            $url = !$data['slug'] || is_null($data['slug']) ? Str::slug($data['name'] . rand(100, strtotime('now')), '-') : $data['slug'];
            $url = strpos($url, '/') === false ? Str::slug($url) : $data['slug'];
            return $url;
        } catch (\Execption $e) {
            return $this->responseError($e);
        }
    }

    public function setUrlRedirects($result)
    {
        try {
            $url = !$result->slug || is_null($result->slug) ? Str::slug($result->name . rand(100, strtotime('now')), '-') : strpos($result->slug, '/') === false ? Str::slug($result->slug) : $result->slug;
            $existsUrl = UrlRedirects::where(['url_original' => $url])->first();
            if ($existsUrl) {
                return $this->responseError(trans('nksoft::common.Url exists'));
            }
            UrlRedirects::updateOrCreate(['url_path' => $this->module . '/' . $result->id], ['url_original' => $url, 'url_path' => $this->module . '/' . $result->id]);
        } catch (\Execption $e) {
            return $this->responseError($e);
        }
    }

    /**
     * function insert histories to admin delete
     */
    public function setHistories($parent_id, $type)
    {
        return Histories::firstOrCreate(['parent_id' => $parent_id, 'type' => $type, 'user_id' => Auth::user()->id]);
    }

    /**
     * function delete histories to admin delete
     */
    public function destroyHistories($parent_id, $type)
    {
        $history = Histories::where(['parent_id' => $parent_id, 'type' => $type])->first();
        if ($history) {
            return $history->delete();
        }

    }

    public function getHistories($type)
    {
        return Histories::where(['type' => $type])->get();
    }

    public function destroy($id)
    {
        try {
            if (\Auth::user()->role_id == 1) {
                if (!request()->get('isCancel')) {
                    $this->model::find($id)->delete();
                }

                $this->destroyHistories($id, $this->module);
            } else {
                $this->setHistories($id, $this->module);
            }
            return $this->responseSuccess();
        } catch (\Exception $e) {
            return $this->responseError($e);
        }
    }

}
