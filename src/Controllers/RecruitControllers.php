<?php

namespace Nksoft\Master\Controllers;

use Arr;
use Illuminate\Http\Request;
use Nksoft\Master\Models\Recruits as CurrentModel;
use Str;

class RecruitControllers extends WebController
{
    private $formData = CurrentModel::FIELDS;

    protected $module = 'recruits';

    protected $model = CurrentModel::class;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $columns = [
                ['key' => 'id', 'label' => 'Id', 'type' => 'hidden'],
                ['key' => 'name', 'label' => trans('nksoft::common.Name')],
                ['key' => 'phone', 'label' => trans('nksoft::common.Phone')],
                ['key' => 'email', 'label' => 'Email'],
                ['key' => 'status', 'label' => trans('nksoft::common.Status'), 'data' => $this->status(), 'type' => 'select'],
            ];
            $select = Arr::pluck($columns, 'key');
            $results = CurrentModel::select($select)->with(['histories'])->get();
            $listDelete = $this->getHistories($this->module)->pluck('parent_id');
            $response = [
                'rows' => $results,
                'columns' => $columns,
                'module' => $this->module,
                'listDelete' => CurrentModel::whereIn('id', $listDelete)->get(),
                'disableNew' => true,
                'disableDuplicate' => true,
            ];
            return $this->responseSuccess($response);
        } catch (\Execption $e) {
            return $this->responseError($e);
        }
    }

    public function status()
    {
        return [
            ['id' => 1, 'name' => 'Đã xem'],
            ['id' => 0, 'name' => 'Chưa xem'],
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $response = [
                'formElement' => $this->formElement(),
                'result' => null,
                'formData' => $this->formData,
                'module' => $this->module,
            ];
            return $this->responseSuccess($response, false);
        } catch (\Execption $e) {
            return $this->responseError($e);
        }
    }

    private function formElement($result = null)
    {
        $status = $this->status();
        return [
            [
                'key' => 'general',
                'label' => trans('nksoft::common.General'),
                'element' => [
                    ['key' => 'status', 'label' => trans('nksoft::common.Status'), 'data' => $status, 'type' => 'select'],
                    ['key' => 'name', 'label' => trans('nksoft::common.Name'), 'data' => $result ? $result->name : null, 'type' => 'label'],
                    ['key' => 'phone', 'label' => trans('nksoft::common.Phone'), 'data' => $result ? $result->phone : null, 'type' => 'label'],
                    ['key' => 'email', 'label' => 'Email', 'data' => $result ? $result->email : null, 'type' => 'label'],
                    ['key' => 'note', 'label' => trans('nksoft::common.Note'), 'data' => $result ? $result->note : null, 'type' => 'label'],
                    ['key' => 'file', 'label' => 'File', 'data' => $result && $result->file ? url('storage/' . $result->file) : null, 'type' => 'link'],
                    ['key' => 'created_at', 'label' => trans('nksoft::common.Created At'), 'data' => $result ? date('d/m/Y', strtotime($result->created_at)) : null, 'type' => 'label'],
                ],
                'active' => true,
                'selected' => $result && $result->parent_id == 0,
            ],
        ];
    }

    private function rules()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|alpha_num|max:11',
        ];

        return $rules;
    }

    private function message()
    {
        return [
            'name.required' => __('nksoft::message.Field is require!', ['Field' => trans('nksoft::common.Name')]),
            'email.required' => __('nksoft::message.Field is require!', ['Field' => trans('nksoft::common.Email')]),
            'phone.required' => __('nksoft::message.Field is require!', ['Field' => trans('nksoft::common.Phone')]),
            'email.email' => 'Email không đúng định dạng',
            'phone.alpha_num' => 'Số điện thoại không đúng định dạng',
        ];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(), $this->rules(), $this->message());
        if ($validator->fails()) {
            return $this->responseError($validator->errors());
        }
        try {
            $data = [];
            foreach ($this->formData as $item) {
                if (!\in_array($item, $this->excludeCol)) {
                    $data[$item] = $request->get($item);
                }
            }
            $data['status'] = 0;
            if ($request->hasFile('file')) {
                $images = $request->file('file');
                if ($images->isValid()) {
                    $extension = $images->getClientOriginalExtension();
                    $fileName = Str::slug($images->getClientOriginalName(), '-') . '-' . rand(3, time()) . '.' . $extension;
                    $path = putUploadImage($images, $fileName);

                }
            }
            $data['file'] = $path;
            $result = CurrentModel::create($data);
            $response = [
                'result' => $result,
            ];
            return $this->responseViewSuccess($response, ['Thông tin của bạn đã được gửi đến chúng tôi.']);
        } catch (\Exception $e) {
            return $this->responseError([$e->getMessage()]);
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
        return $this->responseSuccess();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            array_push($this->formData, 'created_at');
            $result = CurrentModel::select($this->formData)->find($id);
            $response = [
                'formElement' => $this->formElement($result),
                'result' => $result,
                'formData' => $this->formData,
                'module' => $this->module,
                'disableNew' => true,
                'disableDuplicate' => true,
            ];
            return $this->responseSuccess($response, false);
        } catch (\Execption $e) {
            return $this->responseError($e);
        }
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
        $result = CurrentModel::find($id);
        if ($result == null) {
            return $this->responseError();
        }
        $validator = Validator($request->all(), $this->rules($id), $this->message());
        if ($validator->fails()) {
            return $this->responseError($validator->errors());
        }
        try {
            $status = $request->get('status');
            $result = CurrentModel::updateOrCreate(['id' => $id], ['status' => $status]);
            $response = [
                'result' => $result,
            ];
            return $this->responseSuccess($response, false);
        } catch (\Exception $e) {
            return $this->responseError($e);
        }
    }
}
