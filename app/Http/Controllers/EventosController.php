<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\evento;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view("eventos.index");
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
        //$datosEvento = request()->all();
          $datosEvento = request()->except(['_token','_method']);
          evento::insert($datosEvento);
          print_r($datosEvento);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // recolecta todo lo eque ewts aguaradado en la base de  datos

     $data['eventos'] = evento::all();

    return response()->json($data['eventos']);



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

        $datosEvento = request()->except(['_token','_method']);
        $respuesta = evento::where('id','=',$id)->update($datosEvento);

        return response()->json($respuesta);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Eliminacion del evento o agenda
        $eventos= evento::findOrFail($id);
        evento::destroy($id);
        return response()->json($id);
    }
}
