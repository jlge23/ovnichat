@if (session()->has('success'))
    <div class="alert alert-success">
        {{ session('success') }}
        @if(session()->has('productoId'))
            <a class="btn btn-secondary" href="/productosunidades/?id={{session('productoId')}}">Asociar Unidades de Medidas ahora</a>
            <a class="btn btn-secondary" href="/imagenesproductos/?id={{session('productoId')}}">Asociar Imágenes al producto ahora</a>
        @endif
    </div>
@endif
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Descripcion</span>
                <textarea cols="150" class="form-control" name="descripcion" type="text" id="descripcion">{{$producto->descripcion ?? old('descripcion')}}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Código GTIN</span>
                <input type="text" name="codigo_gs1" id="codigo_gs1" class="form-control" value="{{$producto->codigo_gs1 ?? old('codigo_gs1')}}" required>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Marca</span>
                <select name="marca_id" id="marca_id" class="form-select">
                    <option value="">Seleccione</option>
                    @if(isset($Marcas))
                        @foreach ($Marcas as $marca)
                            @if(isset($producto))
                                @if (($producto->marca_id == $marca->id) or ($producto->marca_id == old('marca_id')))
                                    <option value="{{$marca->id}}" selected>{{$marca->marca}}</option>
                                @else
                                    <option value="{{$marca->id}}">{{$marca->marca}}</option>
                                @endif
                            @else
                                @if ($marca->id == old('marca_id'))
                                    <option value="{{$marca->id}}" selected>{{$marca->marca}}</option>
                                @else
                                    <option value="{{$marca->id}}">{{$marca->marca}}</option>
                                @endif
                            @endif
                        @endforeach
                    @else
                        <option value="#" disabled>No hay información de Marcas asociadas a empresas</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">SENCAMER</span>
                <input type="text" name="codigo_sencamer" id="codigo_sencamer" class="form-control" value="{{$producto->codigo_sencamer ?? old('codigo_sencamer')}}" required>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">M.P.P.S</span>
                <input type="text" name="codigo_sacs_mpps" id="codigo_sacs_mpps" class="form-control" value="{{$producto->codigo_sacs_mpps ?? old('codigo_sacs_mpps')}}" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <span class="input-group-text">NOTA: Si el precio por unidad se encuentra en Bolivares (Bs.)</span>

                <label for="bsToUsd">
                    <span class="input-group-text">Calcule su equivalente en USD aquí</span>
                </label>
                <div class="input-group-text">
                    <input class="form-check-input mt-0" name="bsToUsd" type="checkbox" id="bsToUsd">
                </div>
            </div>
        </div>
        <div class="col-md-5 col-sm-5 col-lg-5">
            Precio del dolar BCV:&nbsp;
            <div id="dolarInfo">
            </div>
            <input type="hidden" id="usd">
            <button type="button" id="actualizar" class="btn btn-info"><i class="fa-solid fa-spinner">&nbsp;Buscar cambios en la tasa BCV</i></button>
        </div>
        <div class="col-md-1 col-sm-1 col-lg-1">
            <img id="img" src="" class="rounded mx-auto d-block img-fluid" alt="">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4 col-sm-4 col-lg-4">
            <div class="input-group mb-3">
                <span class="input-group-text">$ | Bs</span>
                <span class="input-group-text">Precio por unidad (inicial)</span>
                <input type="text" name="precio_detal" id="precio_detal" class="form-control" value="{{$producto->precio_detal ?? old('precio_detal')}}" required>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-lg-4">
            <div class="input-group mb-3">
                <span class="input-group-text">Calcular precio al detal +:&nbsp;</span>
                {{-- <input class="form-check-input mt-0" name="comision" type="checkbox" id="comision"> --}}
                <select class="form-select" name="comision" id="comision">
                    <option value="">SELECCIONE</option>
                    @for ($i = 0; $i < 17; $i++)
                        @if ($i > -1 && $i < 10)
                            <option value="0.0{{$i}}">{{$i}}%</option>
                        @else
                            <option value="0.{{$i}}">{{$i}}%</option>
                        @endif
                    @endfor
                </select>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-lg-4">
            <div class="input-group mb-3">
                <span class="input-group-text">Unidades por embalaje</span>
                <input type="number" min="1" max="99999" name="unidades_por_embalaje" id="unidades_por_embalaje" class="form-control" value="{{$producto->unidades_por_embalaje ?? old('unidades_por_embalaje')}}" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <span class="input-group-text">$</span>
                <span class="input-group-text">Precio por mayor (inicial)</span>
                <input type="text" name="precio_mayor" id="precio_mayor" class="form-control" value="{{$producto->precio_mayor ?? old('precio_mayor')}}" required>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" name="calcular" type="checkbox" id="calcular">
                    </div>
                    <label for="calcular">
                    <span class="input-group-text">Calcular precio al mayor por cantidad de embalaje</span></label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            Información de clasificación del producto
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Estado físico del producto</span>
                <select name="estado_fisico_producto_id" id="estado_fisico_producto_id" class="form-select">
                    <option value="">Seleccione</option>
                    @if(isset($efps))
                        @foreach ($efps as $efp)
                            @if(isset($producto))
                                @if (($producto->estado_fisico_producto_id == $efp->id) or ($producto->estado_fisico_producto_id == old('estado_fisico_producto_id')))
                                    <option value="{{$efp->id}}" selected>{{$efp->estado}}</option>
                                @else
                                    <option value="{{$efp->id}}">{{$efp->estado}}</option>
                                @endif
                            @else
                                @if ($efp->id == old('estado_fisico_producto_id'))
                                    <option value="{{$efp->id}}" selected>{{$efp->estado}}</option>
                                @else
                                    <option value="{{$efp->id}}">{{$efp->estado}}</option>
                                @endif
                            @endif
                        @endforeach
                    @else
                        <option value="#" disabled>No hay información de Estados físicos de productos</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Propiedades del producto</span>
                <select name="propiedades_producto_id" id="propiedades_producto_id" class="form-select">
                    <option value="">Seleccione</option>
                    @if(isset($proprod))
                        @foreach ($proprod as $prop)
                            @if(isset($producto))
                                @if (($producto->propiedades_producto_id == $prop->id) or ($producto->propiedades_producto_id == old('propiedades_producto_id')))
                                    <option value="{{$prop->id}}" selected>{{$prop->propiedad}}</option>
                                @else
                                    <option value="{{$prop->id}}">{{$prop->propiedad}}</option>
                                @endif
                            @else
                                @if ($prop->id == old('propiedades_producto_id'))
                                    <option value="{{$prop->id}}" selected>{{$prop->propiedad}}</option>
                                @else
                                    <option value="{{$prop->id}}">{{$prop->propiedad}}</option>
                                @endif
                            @endif
                        @endforeach
                    @else
                        <option value="#" disabled>No hay información de Estados físicos de productos</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="input-group mb-3">
                <div class="input-group mb-3">
                    <span class="input-group-text">Procedencia del producto</span>
                    <div class="input-group-text">
                        <input class="form-check-input" type="radio" name="importado" id="importado1" value="0" {{((isset($producto->importado)) and $producto->importado == "0") ? 'checked' : (old("importado")  == "1" ? 'checked' : '')}}>
                        <label class="form-check-label" for="importado1">&nbsp;Producto nacional</label>
                    </div>
                    <div class="input-group-text">
                        <input class="form-check-input" type="radio" name="importado" id="importado2" value="1" {{((isset($producto->importado)) and $producto->importado == "1") ? 'checked' : (old("importado")  == "1" ? 'checked' : '')}}>
                        <label class="form-check-label" for="importado2">&nbsp;Producto importado</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
