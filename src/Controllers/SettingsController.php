<?php

namespace Nksoft\Master\Controllers;

use Illuminate\Http\Request;
use Nksoft\Master\Models\Settings as CurrentModule;

class SettingsController extends WebController
{
    private $formData = ['id', 'title', 'email', 'phone', 'address', 'description', 'head_script', 'body_top_script', 'body_bottom_script', 'social'];

    private $module = 'settings';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->create();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $result = CurrentModule::select($this->formData)->with(['images'])->first();
            \array_push($this->formData, 'images');
            $response = [
                'data' => [
                    'formElement' => $this->formElement(),
                    'result' => $result,
                    'formData' => $this->formData,
                    'module' => $this->module,
                ],
                'success' => true,
            ];

        } catch (\Execption $e) {
            $response = $this->responseError($e);
        }
        return response()->json($response);
    }

    private function formElement()
    {
        return [
            [
                'key' => 'general',
                'label' => trans('nksoft::common.General'),
                'element' => [
                    ['key' => 'head_script', 'label' => trans('nksoft::settings.HeadScript'), 'data' => null, 'type' => 'textarea'],
                    ['key' => 'body_top_script', 'label' => trans('nksoft::settings.BodyTopScript'), 'data' => null, 'type' => 'textarea'],
                    ['key' => 'body_bottom_script', 'label' => trans('nksoft::settings.BodyBottomScript'), 'data' => null, 'type' => 'textarea'],
                    ['key' => 'social', 'label' => trans('nksoft::settings.Social'), 'data' => ['fb', 'gg', 'tw', 'zl', 'lk'], 'type' => 'social'],
                ],
                'active' => true,
            ],
            [
                'key' => 'inputForm',
                'label' => trans('nksoft::common.Content'),
                'element' => [
                    ['key' => 'title', 'label' => trans('nksoft::settings.Title'), 'data' => null, 'type' => 'text'],
                    ['key' => 'email', 'label' => trans('nksoft::users.Email'), 'data' => null, 'type' => 'email'],
                    ['key' => 'phone', 'label' => trans('nksoft::users.Phone'), 'data' => null, 'type' => 'text'],
                    ['key' => 'address', 'label' => trans('nksoft::settings.Address'), 'data' => null, 'type' => 'text'],
                    ['key' => 'description', 'label' => trans('nksoft::settings.Description'), 'data' => null, 'type' => 'textarea'],
                    ['key' => 'images', 'label' => trans('nksoft::users.Avatar'), 'data' => null, 'type' => 'image'],
                ],
            ],
        ];
    }

    private function rules($id = 0)
    {
        $rules = [
            'title' => 'required',
        ];

        return $rules;
    }

    private function message()
    {
        return [
            'title.required' => __('nksoft::message.Field is require!', ['Field' => trans('nksoft::settings.Title')]),
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
            return \response()->json(['status' => 'error', 'message' => $validator->customMessages]);
        }
        try {
            $data = [];
            foreach ($this->formData as $item) {
                if ($item != 'images') {
                    $data[$item] = $request->get($item);
                }
            }
            $user = CurrentModule::create($data);
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $this->setMedia($images, $user->id, $this->module);
            }
            $response = $this->responseSuccess();
            $response['result'] = $user;
        } catch (\Exception $e) {
            $response = $this->responseError($e);
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('master::layout');
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
            $result = CurrentModule::select($this->formData)->with(['images'])->find($id);
            \array_push($this->formData, 'images');
            $response = $this->responseSuccess();
            $response['data'] = [
                'formElement' => $this->formElement(),
                'result' => $result,
                'formData' => $this->formData,
                'module' => $this->module,
            ];
        } catch (\Execption $e) {
            $response = $this->responseError($e);
        }
        return response()->json($response);
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
        dd($request->all());
        $user = CurrentModule::find($id);
        if ($user == null) {
            return response()->json($this->responseError());
        }
        $validator = Validator($request->all(), $this->rules($id), $this->message());
        if ($validator->fails()) {
            return \response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }
        try {
            $data = [];
            foreach ($this->formData as $item) {
                if ($item != 'images' && $item != 'id') {
                    $data[$item] = $request->get($item);
                }
            }
            foreach ($data as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            // $user = CurrentModule::save(['id' => $id], $data);
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $this->setMedia($images, $user->id, $this->module);
            }
            $response = $this->responseSuccess();
            $response['result'] = $user;
        } catch (\Exception $e) {
            $response = $this->responseError($e);
        }
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
        //
    }
}
