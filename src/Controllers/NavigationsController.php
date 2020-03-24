<?php

namespace Nksoft\Master\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nksoft\Master\Models\Navigations;
class NavigationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $columns = ['id', 'title', 'link', 'icon'];
            $result = Navigations::select($columns)->get();
            $response = [
                'data' => [
                    'rows' => $result,
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
                    'formElement' => $this->renderElement(),
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

    private function renderElement()
    {
        return [

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
        //
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
