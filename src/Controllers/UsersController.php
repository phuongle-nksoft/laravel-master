<?php

namespace Nksoft\Master\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nksoft\Master\Models\Roles;
use Nksoft\Master\Models\Users;

class UsersController extends WebController
{
    private $formData = ['is_active', 'role_id', 'name', 'email', 'password', 'phone', 'birthday', 'area'];

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
            $users = Users::select($columns)->get();
            $response = [
                'data' => [
                    'rows' => $users,
                    'columns' => $columns,
                ],
                'success' => true,
            ];

        } catch (\Execption $e) {
            $response = [
                'data' => null,
                'success' => false,
                'message' => $e->getMessage(),
            ];
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
            \array_push($this->formData, 'image');
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
            $response = [
                'data' => null,
                'success' => false,
                'message' => $e->getMessage(),
            ];
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
                    ['key' => 'image', 'label' => trans('nksoft::users.Area'), 'data' => config('nksoft.area'), 'type' => 'file'],
                ],
            ],
        ];
    }

    private function rules()
    {
        return [
            'email' => 'required|email:rfc,dns',
            'image[]' => 'file',
            'password' => 'required|min:6',
        ];
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
                if ($item != 'image') {
                    $data[$item] = $request->get($item);
                }
            }
            $data['password'] = \Hash::make($data['password']);
            $user = Users::create($data);
            if ($request->hasFile('image')) {
                $images = $request->file('image');
                $this->setMedia($images, $user->id, $this->module);
            }
            return response()->json(['status' => 'success', 'message' => 'Success', 'result' => $user]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
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
            $result = Users::select($this->formData)->with(['images'])->find($id);
            dd($result);
            \array_push($this->formData, 'image');
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
            $response = [
                'data' => null,
                'success' => false,
                'message' => $e->getMessage(),
            ];
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
