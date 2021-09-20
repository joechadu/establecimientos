<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Models\Establecimiento;
use Intervention\Image\Facades\Image;

class EstablecimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::all();

        return view('establecimientos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validación
        $data = $request->validate([
            'nombre' => 'required',
            'categoria_id' => 'required|exists:App\Models\Categoria,id',
            'imagen_principal' => 'required|image',
            'direccion' => 'required',
            'urbanizacion'=> 'required',
            'lat' => 'required',
            'lng' => 'required',
            'telefono'=> 'required|numeric',
            'descripcion'=> 'required|min:20',
            'apertura' => 'date_format:H:i',
            'cierre'=>'date_format:H:i|after:apertura',
            'uuid' => 'required|uuid'
        ]);

        //guardar la imagen
        $ruta_imagen = $request['imagen_principal']->store('principales', 'public');
        $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(800,600);
        $img->save();

        //guardar en la BD
        auth()->user()->establecimiento()->create([
            'nombre' => $data['nombre'],
            'categoria_id' => $data['categoria_id'],
            'imagen_principal' => $ruta_imagen,
            'direccion' => $data['direccion'],
            'urbanizacion'=> $data['urbanizacion'],
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'telefono'=> $data['telefono'],
            'descripcion'=> $data['descripcion'],
            'apertura' => $data['apertura'],
            'cierre'=>$data['cierre'],
            'uuid' => $data['uuid'],
        ]);

        return back()->with('estado', 'Tú información se almaceno correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Establecimiento  $establecimiento
     * @return \Illuminate\Http\Response
     */
    public function show(Establecimiento $establecimiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Establecimiento  $establecimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(Establecimiento $establecimiento)
    {
        $categorias = Categoria::all();

        //obtener el establecimiento
        $establecimiento =  auth()->user()->establecimiento;
        $establecimiento->apertura = date('H:i', strtotime($establecimiento->apertura));
        $establecimiento->cierre = date('H:i', strtotime($establecimiento->cierre));

        //obtiene las imagenes del establecimiento

        $imagenes = Imagen::where('id_establecimiento', $establecimiento->uuid)->get();
        // dd($imagenes);


        return view('establecimientos.edit', compact('categorias', 'establecimiento', 'imagenes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Establecimiento  $establecimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Establecimiento $establecimiento)
    {

        //ejecutar el policy
        $this->authorize('update', $establecimiento);
        
          //Validación
          $data = $request->validate([
            'nombre' => 'required',
            'categoria_id' => 'required|exists:App\Models\Categoria,id',
            'imagen_principal' => 'image',
            'direccion' => 'required',
            'urbanizacion'=> 'required',
            'lat' => 'required',
            'lng' => 'required',
            'telefono'=> 'required|numeric',
            'descripcion'=> 'required|min:20',
            'apertura' => 'date_format:H:i',
            'cierre'=>'date_format:H:i|after:apertura',
            'uuid' => 'required|uuid'
        ]);

        $establecimiento->nombre = $data['nombre'];
        $establecimiento->categoria_id = $data['categoria_id'];
        $establecimiento->direccion = $data['direccion'];
        $establecimiento->urbanizacion = $data['urbanizacion'];
        $establecimiento->lat = $data['lat'];
        $establecimiento->lng = $data['lng'];
        $establecimiento->telefono = $data['telefono'];
        $establecimiento->descripcion = $data['descripcion'];
        $establecimiento->apertura = $data['apertura'];
        $establecimiento->cierre = $data['cierre'];
        $establecimiento->uuid = $data['uuid'];

        // si el usuario sube una imagen

        if(request('imagen_principal')){
            //guardar la imagen
            $ruta_imagen = $request['imagen_principal']->store('principales', 'public');
            $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(800,600);
            $img->save();

            $establecimiento->imagen_principal = $ruta_imagen;
        }

        $establecimiento->save();

        //mensaje al usuario para

        return back()->with('estado', 'Tú información se almaceno correctamente');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Establecimiento  $establecimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Establecimiento $establecimiento)
    {
        //
    }
}
