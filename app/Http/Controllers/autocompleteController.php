<?php

namespace aidocs\Http\Controllers;

use Illuminate\Http\Request;
use aidocs\Http\Requests;
use aidocs\Http\Controllers\Controller;
use Illuminate\Support\Str;

class autocompleteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function show()
    {
        return view('autocompletado');
    }

    public function autocomplete(Request $request)
    {
        $term = $request -> term;
        $data = [
            'R'   => 'Red',
            'O'   => 'Orange',
            'Y'   => 'Yellow',
            'G'   => 'Green',
        ];
        $result = [];
        foreach($data as $color)
        {
            if(strpos(Str::lower($color),$term) !== false)
            {
                $result[] = $color;
            }
        }

        //dd($result);
        if($request->ajax())
            return response()->json($result);

        return false;
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
