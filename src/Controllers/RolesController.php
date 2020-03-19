<?php

namespace Nksoft\Master\Controllers;

use Illuminate\Http\Request;
use Nksoft\Master\Models\Roles;

class RolesController extends WebController
{
    private $formData = ['is_active', 'name'];

    private $module = 'roles';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $columns = ['id', 'name'];
            $users = Roles::select($columns)->get();
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
                    ['key' => 'name', 'label' => trans('nksoft::users.Username'), 'data' => null, 'type' => 'text'],
                ],
                'active' => true,
            ],
        ];
    }

    private function rules()
    {
        return [
            'name' => 'required',
        ];
    }

    private function message()
    {
        return [
            'name.required' => trans('nksoft::common.Email is require!'),
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
            $user = Roles::create($data);
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
            $result = Roles::select($this->formData)->with(['users'])->find($id);
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
}
