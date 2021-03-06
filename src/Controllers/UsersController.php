<?php

namespace Nksoft\Master\Controllers;

use Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nksoft\Master\Models\Roles;
use Nksoft\Master\Models\Users as CurrentModel;

class UsersController extends WebController
{
    private $formData = ['id', 'is_active', 'role_id', 'name', 'email', 'password', 'phone', 'birthday', 'area'];

    protected $module = 'users';

    protected $model = CurrentModel::class;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->role_id != 1) {
                return $this->responseError();
            }
            return $next($request);
        })->only(['index', 'create', 'store', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Roles::select(['id', 'name'])->get();
        try {
            $columns = [
                ['key' => 'id', 'label' => 'Id', 'type' => 'hidden'],
                ['key' => 'name', 'label' => trans('nksoft::common.Name')],
                ['key' => 'email', 'label' => trans('nksoft::users.Email')],
                ['key' => 'phone', 'label' => trans('nksoft::users.Phone')],
                ['key' => 'role_id', 'label' => trans('nksoft::users.Roles'), 'data' => $roles, 'type' => 'select'],
                ['key' => 'area', 'label' => trans('nksoft::users.Area'), 'data' => config('nksoft.area'), 'type' => 'select'],
                ['key' => 'is_active', 'label' => trans('nksoft::common.Status'), 'data' => $this->status(), 'type' => 'select'],
            ];
            $select = Arr::pluck($columns, 'key');
            $users = CurrentModel::select($select)->with(['histories'])->get();
            $response = [
                'rows' => $users,
                'columns' => $columns,
                'module' => $this->module,
            ];
            return $this->responseSuccess($response);
        } catch (\Execption $e) {
            return $this->responseError($e);
        }
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
                'formElement' => $this->formElement(),
                'result' => null,
                'formData' => $this->formData,
                'module' => $this->module,
            ];
            return $this->responseSuccess($response);
        } catch (\Execption $e) {
            return $this->responseError($e);
        }
    }

    private function formElement()
    {
        $roles = Roles::select(['id', 'name'])->get();
        return [
            [
                'key' => 'inputForm',
                'label' => trans('nksoft::common.Content'),
                'element' => [
                    ['key' => 'is_active', 'label' => trans('nksoft::common.Status'), 'data' => $this->status(), 'type' => 'select'],
                    ['key' => 'role_id', 'label' => trans('nksoft::users.Roles'), 'data' => $roles, 'type' => 'select'],
                    ['key' => 'name', 'label' => trans('nksoft::users.Username'), 'data' => null, 'class' => 'required', 'type' => 'text'],
                    ['key' => 'email', 'label' => trans('nksoft::users.Email'), 'data' => null, 'class' => 'required', 'type' => 'email'],
                    ['key' => 'password', 'label' => trans('nksoft::users.Password'), 'data' => null, 'class' => 'required', 'type' => 'password'],
                    ['key' => 'phone', 'label' => trans('nksoft::users.Phone'), 'data' => null, 'class' => 'required', 'type' => 'text'],
                    ['key' => 'birthday', 'label' => trans('nksoft::users.Birthday'), 'data' => null, 'type' => 'date', 'class' => 'col-md-3'],
                    ['key' => 'area', 'label' => trans('nksoft::users.Area'), 'data' => config('nksoft.area'), 'type' => 'select'],
                    ['key' => 'images', 'label' => trans('nksoft::users.Avatar'), 'data' => null, 'type' => 'image'],
                ],
                'active' => true,
            ],
        ];
    }

    private function rules($id = 0)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
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
            'phone.required' => __('nksoft::message.Field is require!', ['Field' => trans('nksoft::users.Phone')]),
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
            if ($this->validateDate($data['birthday'])) {
                $data['birthday'] = date('Y-m-d', strtotime($data['birthday']));
            }
            $user = CurrentModel::create($data);
            $this->media($request, $user);
            $response = [
                'result' => $user,
            ];
            return $this->responseSuccess($response);
        } catch (\Exception $e) {
            return $this->responseError($e);
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
        if (!Auth::check() || (Auth::user()->role_id != 1 && Auth::user()->id != $id)) {
            return $this->responseError();
        }
        try {
            $result = CurrentModel::select($this->formData)->with(['images'])->find($id);
            // $result->birthday = $result->birthday ? date('d/m/Y', \strtotime($result->birthday)) : '';
            \array_push($this->formData, 'images');
            $formElement = $this->formElement();
            if (Auth::user()->role_id != 1) {
                $formElement = array_slice($formElement, 1);
                $formElement[0]['active'] = true;
            }

            $response = [
                'formElement' => $formElement,
                'result' => $result,
                'formData' => $this->formData,
                'module' => $this->module,
                'disableDuplicate' => true,
            ];
            return $this->responseSuccess($response);
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
        if (!Auth::check() || (Auth::user()->role_id != 1 && Auth::user()->id != $id)) {
            return $this->responseError();
        }
        $user = CurrentModel::find($id);
        if ($user == null) {
            return $this->responseError();
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
            if ($this->validateDate($data['birthday'])) {
                $data['birthday'] = date('Y-m-d', strtotime($data['birthday']));
            }
            if ($data['password'] && $data['password'] != 'undefined') {
                $data['password'] = \Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            foreach ($data as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            // $user = CurrentModel::save(['id' => $id], $data);
            $this->media($request, $user);
            $response = [
                'result' => $user,
            ];
            return $this->responseSuccess($response);
        } catch (\Exception $e) {
            return $this->responseError($e);
        }
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
            if (Auth::user()->role_id == 3) {
                return redirect()->to('admin/orders');
            }

            return redirect()->to('admin/categories');
        }
        return redirect()->back()->withErrors([trans('nksoft::login.Email or password is incorrect!')], 'login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->to('login');
    }
}
