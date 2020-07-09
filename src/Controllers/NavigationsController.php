<?php

namespace Nksoft\Master\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nksoft\Master\Models\Navigations as CurrentModel;

class NavigationsController extends Controller
{
    private $formData = ['id', 'is_active', 'title', 'link', 'icon', 'child'];

    private $module = 'navigations';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $columns = ['id', 'title', 'link', 'icon'];
            $users = CurrentModel::select($columns)->get();
            $response = [
                'data' => [
                    'rows' => $users,
                    'columns' => $columns,
                    'module' => $this->module,
                ],
                'success' => true,
            ];

        } catch (\Execption $e) {
            $response = $this->responseError($e);
        }
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            \array_push($this->formData, 'images');
            $response = [
                'data' => [
                    'formElement' => $this->formElement(),
                    'result' => null,
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
        $roles = Roles::select(['id', 'name'])->get();
        $status = [];
        foreach (config('nksoft.status') as $v => $k) {
            $status[] = ['id' => $k['id'], 'name' => trans($k['name'])];
        }
        return [
            [
                'key' => 'general',
                'label' => trans('nksoft::common.General'),
                'element' => [
                    ['key' => 'is_active', 'label' => trans('nksoft::common.Status'), 'data' => $status, 'type' => 'select'],
                    ['key' => 'role_id', 'label' => trans('nksoft::users.Roles'), 'data' => $roles, 'type' => 'select'],
                ],
                'active' => true,
            ],
            [
                'key' => 'inputForm',
                'label' => trans('nksoft::common.Content'),
                'element' => [
                    ['key' => 'name', 'label' => trans('nksoft::users.Username'), 'data' => null, 'type' => 'text'],
                    ['key' => 'email', 'label' => trans('nksoft::users.Email'), 'data' => null, 'type' => 'email'],
                    ['key' => 'password', 'label' => trans('nksoft::users.Password'), 'data' => null, 'type' => 'password'],
                    ['key' => 'phone', 'label' => trans('nksoft::users.Phone'), 'data' => null, 'type' => 'text'],
                    ['key' => 'birthday', 'label' => trans('nksoft::users.Birthday'), 'data' => null, 'type' => 'date'],
                    ['key' => 'area', 'label' => trans('nksoft::users.Area'), 'data' => config('nksoft.area'), 'type' => 'select'],
                    ['key' => 'images', 'label' => trans('nksoft::users.Area'), 'data' => config('nksoft.area'), 'type' => 'file'],
                ],
            ],
        ];
    }

    private function rules($id = 0)
    {
        $rules = [
            'email' => 'required|email:rfc,dns',
            'images[]' => 'file',
        ];
        if ($id == 0) {
            $rules['password'] = 'required|min:6';
        }

        return $rules;
    }

    private function message()
    {
        return [
            'email.required' => trans('nksoft::common.Email is require!'),
            'email.email' => trans('nksoft::common.Email is incorrect!'),
            'password.required' => trans('nksoft::common.Password is require!'),
            'password.min' => trans('nksoft::common.Password more than 6 letter!'),
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
            return \response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }
        try {
            $data = [];
            foreach ($this->formData as $item) {
                if ($item != 'images') {
                    $data[$item] = $request->get($item);
                }
            }
            $data['password'] = \Hash::make($data['password']);
            $user = CurrentModel::create($data);
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
            array_push($this->formData, 'id');
            $result = CurrentModel::select($this->formData)->with(['images'])->find($id);
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
        $validator = Validator($request->all(), $this->rules($id), $this->message());
        if ($validator->fails()) {
            return \response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }
        try {
            $data = [];
            foreach ($this->formData as $item) {
                if ($item != 'images') {
                    $data[$item] = $request->get($item);
                }
            }
            if ($data['password']) {
                $data['password'] = \Hash::make($data['password']);
            }

            $user = CurrentModel::create($data);
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
        dd($id);
        try {
            CurrentModel::find($id)->delete();
            $response = $this->responseSuccess();
        } catch (\Exception $e) {
            $response = $this->responseError($e);
        }
        return response()->json($response);
    }
}
