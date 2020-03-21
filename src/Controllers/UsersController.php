<?php

namespace Nksoft\Master\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nksoft\Master\Models\Roles;
use Nksoft\Master\Models\Users as CurrentModule;

class UsersController extends WebController
{
    private $formData = ['id', 'is_active', 'role_id', 'name', 'email', 'password', 'phone', 'birthday', 'area'];

    private $module = 'users';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $columns = ['id', 'name', 'email', 'phone', 'area'];
            $users = CurrentModule::select($columns)->get();
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
                    ['key' => 'name', 'label' => trans('nksoft::users.Username'), 'data' => null, 'class' => 'required', 'type' => 'text'],
                    ['key' => 'email', 'label' => trans('nksoft::users.Email'), 'data' => null, 'class' => 'required', 'type' => 'email'],
                    ['key' => 'password', 'label' => trans('nksoft::users.Password'), 'data' => null, 'class' => 'required', 'type' => 'password'],
                    ['key' => 'phone', 'label' => trans('nksoft::users.Phone'), 'data' => null, 'type' => 'text'],
                    ['key' => 'birthday', 'label' => trans('nksoft::users.Birthday'), 'data' => null, 'type' => 'date'],
                    ['key' => 'area', 'label' => trans('nksoft::users.Area'), 'data' => config('nksoft.area'), 'type' => 'select'],
                    ['key' => 'images', 'label' => trans('nksoft::users.Avatar'), 'data' => config('nksoft.area'), 'type' => 'image'],
                ],
            ],
        ];
    }

    private function rules($id = 0)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
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
            'name.required' => __('nksoft::message.Field is require!', ['Field' => trans('nksoft::Users.Username')]),
            'email.required' => __('nksoft::message.Field is require!', ['Field' => 'Email']),
            'email.email' => __('nksoft::message.Email is incorrect!'),
            'password.required' => __('nksoft::message.Field is require!', ['Field' => trans('nksoft::login.Password')]),
            'password.min' => __('nksoft::message.Field more than number letter!', ['Field' => trans('nksoft::login.Password'), 'number' => 6]),
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
            $data['password'] = \Hash::make($data['password']);
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
            if ($data['password'] !== $user->password) {
                $data['password'] = \Hash::make($data['password']);
            } else {
                unset($data['password']);
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
        try {
            CurrentModule::find($id)->delete();
            $response = $this->responseSuccess();
        } catch (\Exception $e) {
            $response = $this->responseError($e);
        }
        return response()->json($response);
    }

    public function login(Request $request)
    {
        $validator = Validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6|max:32',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors([trans('nksoft::login.Email or password is incorrect!')], 'login');
        }

        $credentials = $request->only('email', 'password', 'active');
        if (Auth::attempt($credentials)) {
            return redirect()->to('admin');
        }
        return redirect()->back()->withErrors([trans('nksoft::login.Email or password is incorrect!')], 'login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->to('login');
    }
}
