@extends('layouts.app')

@section('styles')
{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
crossorigin=""/>
{{-- esri geocoder --}}
<link
rel="stylesheet"
href="https://unpkg.com/esri-leaflet-geocoder/dist/esri-leaflet-geocoder.css"
/>
{{-- Dropzone --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css" integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')

<div class="container">
    <h1 class="text-center" mt-4>Registrar Establecimiento</h1>
    <div class="mt-5 row justify-content-center">
        <form 
        class="col-md-9 col-xs-12 card card-body"
        action="{{ route('establecimiento.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <fieldset class="border p-4">
            <legend class="text-primary"> Nombre, Categoría e Imagen Principal </legend>           
            <div class="form-group">
                <label for="nombre">Nombre del Establecimiento</label>               
                <input type="text"
                       id="nombre"
                       class="form-control @error('nombre') is-invalid @enderror"
                       placeholder="Nombre"
                       name="nombre"
                       value="{{ old('nombre') }}">
                @error('nombre')
                   <div class="invalid-feedback">
                             {{$message}}
                   </div>
                @enderror                
            </div>  

            <div class="form-group">
                <label for="categoria">Categoria</label>

                <select class="form-control  @error('categoria_id') is-invalid @enderror"           name="categoria_id" id="categoria">
                    <option value="" selected disabled>--Seleccione--</option>

                    @foreach ( $categorias as $categoria )
                            <option value="{{$categoria->id}}" 
                                {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{$categoria->nombre}}</option>
                    @endforeach

                </select>

                @error('categoria_id')

                <div class="invalid-feedback">
                    {{$message}}
                </div>
                    
                @enderror
            </div>

            <div class="form-group">
                <label for="imagen_principal">Imagen Principal</label>
                <input type="file"
                       id="imagen_principal"
                       class="form-control @error('imagen_principal') is-invalid @enderror"
                       name="imagen_principal"
                       value="{{old('imagen_principal')}}" 
                       >
                @error('imagen_principal')

                <div class="invalid-feedback">
                    {{$message}}
                </div>
                    
                @enderror

            </div>
            
        </fieldset>
        

        <fieldset class="border p-4 mt-5">
            <legend class="text-primary">Ubicación:</legend>           
            <div class="form-group">
                <label for="formbuscador">Coloca la Dirección de tú Establecimiento</label>               
                <input type="text"
                       id="formbuscador"
                       class="form-control"
                       placeholder="Calle del negocio o establecimiento">

               <p class="text-secondary mt-5 mb-3 text-center">El asistente colocará una dirección estimada o mueve el pin correcto</p>
            </div> 
               <div class="form-group">
                   <div id="mapa" style="height:400px"></div>

               </div>

               <p class="informacion">Confirma que los siguientes campos son correctos</p>

               <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text"
                           id="direccion"
                           name="direccion"
                           class="form-control @error('direccion') is-invalid @enderror"
                           placeholder="Dirección"
                           value="{{ old('dirección')}}">

                     @error('direccion')
                    <div class="invalid-feedback">{{$message}}</div>                         
                     @enderror
               </div>

               <div class="form-group">
                <label for="urbanizacion">Urbanización:</label>
                <input type="text"
                       id="urbanizacion"
                       name="urbanizacion"
                       class="form-control @error('urbanizacion') is-invalid @enderror"
                       placeholder="Urbanización"
                       value="{{ old('urbanizacion')}}">

                 @error('urbanizacion')
                <div class="invalid-feedback">{{$message}}</div>                         
                 @enderror
           </div>

           <input type="hidden" id="lat" name="lat" value="{{old('lat')}}">
           <input type="hidden" id="lng" name="lng" value="{{old('lng')}}">

           

        </fieldset>

        <fieldset class="border p-4 mt-5">
            <legend  class="text-primary">Información Establecimiento: </legend>
                <div class="form-group">
                    <label for="nombre">Teléfono</label>
                    <input 
                        type="tel" 
                        class="form-control @error('telefono')  is-invalid  @enderror" 
                        id="telefono" 
                        placeholder="Teléfono Establecimiento"
                        name="telefono"
                        value="{{ old('telefono') }}"
                    >

                        @error('telefono')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                </div>

                

                <div class="form-group">
                    <label for="nombre">Descripción</label>
                    <textarea
                        class="form-control  @error('descripcion')  is-invalid  @enderror" 
                        name="descripcion"
                    >{{ old('descripcion') }}</textarea>

                        @error('descripcion')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                </div>

                <div class="form-group">
                    <label for="nombre">Hora Apertura:</label>
                    <input 
                        type="time" 
                        class="form-control @error('apertura')  is-invalid  @enderror" 
                        id="apertura" 
                        name="apertura"
                        value="{{ old('apertura') }}"
                    >
                    @error('apertura')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nombre">Hora Cierre:</label>
                    <input 
                        type="time" 
                        class="form-control @error('cierre')  is-invalid  @enderror" 
                        id="cierre" 
                        name="cierre"
                        value="{{ old('cierre') }}"
                    >
                    @error('cierre')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
        </fieldset>

        <fieldset class="border p-4 mt-5">
            <legend  class="text-primary">Imagenes del Establecimiento: </legend>
                <div class="form-group">
                    <label for="imagenes">Imagenes</label>

                    <div id="dropzone" class="dropzone form-control"></div>
                </div>
        </fieldset>

        <input type="hidden" id="uuid" name="uuid" value="{{Str::uuid()->toString()}}">

        <input type="submit" class="btn btn-primary mt-3 d-block" value="Registrar Establecimiento">


        </form>
    </div>
    </div>    
@endsection

@section('scripts')
{{-- Leaflet --}}
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
crossorigin=""></script>
{{-- Esri Leaflet --}}
<script src="https://unpkg.com/esri-leaflet" defer></script>
{{-- Geocoder --}}
<script src="https://unpkg.com/esri-leaflet-geocoder" defer></script>
{{-- Dropzone --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.js" integrity="sha512-llCHNP2CQS+o3EUK2QFehPlOngm8Oa7vkvdUpEFN71dVOf3yAj9yMoPdS5aYRTy8AEdVtqUBIsVThzUSggT0LQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
@endsection