<?php

namespace Nksoft\Master\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nksoft\Master\Models\Users;
use Nksoft\Master\Models\Roles;

class UsersController extends Controller
{
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
            $response = [
                'data' => [
                    'formElement' => $this->formElement(),
                    'result' => null,
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

    private function formElement() {
        $roles = Roles::select(['id', 'name'])->get();
        return [
            'general' => [
                'select' => ['key' => 'is_active', 'label' => 'Status', 'data' => config('nksoft.status')],
                'checkbox' => ['key' => 'role_id', 'label' => 'Roles', 'data' => $roles]
            ],
            'input_form' => [
                'text' => ['key' => 'name', 'label' => 'User Name', 'data' => null],
                'email' => ['key' => 'email', 'label' => 'Email', 'data' => null],
                'password' => ['key' => 'password', 'label' => 'Password', 'data' => null],
                'text' => ['key' => 'phone', 'label' => 'Phone', 'data' => null],
                'select' => ['key' => 'area', 'label' => 'Area', 'data' => config('nksoft.status')],
                'textarea' => ['key' => 'content', 'label' => 'Content', 'data' => null, 'editor' => true],
            ],
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
        return view('master::layout');
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
