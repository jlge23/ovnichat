
@if (session()->has('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <div class="row">
        <div class="col-md-3 col-sm-3 col-lg-3">
            <figure class="figure">
                @if (isset($producto->image) && $producto->image)
                    <img src="{{env('APP_URL')}}/storage/images/{{$producto->image}}" class="rounded float-start" alt="{{$producto->image}}" width="150px" height="150px">
                @else
                    <img src="{{env('APP_URL')}}/storage/images/no-photo.png" class="rounded float-start" alt="Sin Imagen (logo)" width="150px" height="150px">
                @endif
                <figcaption class="figure-caption">Imagen cargada previamente</figcaption>
            </figure>
        </div>
        <div class="col-md-9 col-sm-9 col-lg-9">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Imagen (Logo)</span>
                <input type="file" class="form-control" id="image" name="image" @if(!(isset($producto))) @endif>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Código GTIN&nbsp;<i class="fa-solid fa-barcode"></i></span>
                <input type="text" pattern="^\d{8,14}$" inputmode="numeric" name="gtin" id="gtin" class="form-control" value="{{$producto->gtin ?? old('gtin')}}">

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Nombre del producto&nbsp;<i class="fa-brands fa-product-hunt"></i></span>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{$producto->nombre ?? old('nombre')}}" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Descripcion&nbsp;<i class="fa-brands fa-product-hunt"></i></span>
                <textarea cols="150" class="form-control" name="descripcion" type="text" id="descripcion">{{$producto->descripcion ?? old('descripcion')}}</textarea>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4 col-sm-4 col-lg-4">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Marca&nbsp;<i class="fa-solid fa-marker"></i></span>
                <select name="marca_id" id="marca_id" class="form-select">
                    <option value="">Seleccione</option>
                    @if(isset($marcas))
                        @foreach ($marcas as $marca)
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
                        <option value="#" disabled>No hay información de Marcas</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-lg-4">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Categoría&nbsp;<i class="fa-solid fa-list"></i></span>
                <select name="categoria_id" id="categoria_id" class="form-select">
                    <option value="">Seleccione</option>
                    @if(isset($categorias))
                        @foreach ($categorias as $categoria)
                            @if(isset($producto))
                                @if (($producto->categoria_id == $categoria->id) or ($producto->categoria_id == old('categoria_id')))
                                    <option value="{{$categoria->id}}" selected>{{$categoria->nombre}}</option>
                                @else
                                    <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                                @endif
                            @else
                                @if ($categoria->id == old('categoria_id'))
                                    <option value="{{$categoria->id}}" selected>{{$categoria->nombre}}</option>
                                @else
                                    <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                                @endif
                            @endif
                        @endforeach
                    @else
                        <option value="#" disabled>No hay información de Categorias</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-lg-4">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Proveedor&nbsp;<i class="fa-solid fa-industry"></i></span>
                <select name="proveedor_id" id="proveedor_id" class="form-select">
                    <option value="">Seleccione</option>
                    @if(isset($proveedores))
                        @foreach ($proveedores as $proveedor)
                            @if(isset($producto))
                                @if (($producto->proveedor_id == $proveedor->id) or ($producto->proveedor_id == old('proveedor_id')))
                                    <option value="{{$proveedor->id}}" selected>{{$proveedor->nombre}}</option>
                                @else
                                    <option value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                                @endif
                            @else
                                @if ($proveedor->id == old('proveedor_id'))
                                    <option value="{{$proveedor->id}}" selected>{{$proveedor->nombre}}</option>
                                @else
                                    <option value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                                @endif
                            @endif
                        @endforeach
                    @else
                        <option value="#" disabled>No hay información de Proveedores</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Unidad de medida&nbsp;<i class="fa-solid fa-ruler"></i></span>
                <select name="unidad_medida_id" id="unidad_medida_id" class="form-select">
                    <option value="">Seleccione</option>
                    @if(isset($unidadesMedidas))
                        @foreach ($unidadesMedidas as $unidadMedidas)
                            @if(isset($producto))
                                @if (($producto->unidad_medida_id == $unidadMedidas->id) or ($producto->unidad_medida_id == old('unidad_medida_id')))
                                    <option value="{{$unidadMedidas->id}}" selected>{{$unidadMedidas->nombre}}</option>
                                @else
                                    <option value="{{$unidadMedidas->id}}">{{$unidadMedidas->nombre}}</option>
                                @endif
                            @else
                                @if ($unidadMedidas->id == old('unidad_medida_id'))
                                    <option value="{{$unidadMedidas->id}}" selected>{{$unidadMedidas->nombre}}</option>
                                @else
                                    <option value="{{$unidadMedidas->id}}">{{$unidadMedidas->nombre}}</option>
                                @endif
                            @endif
                        @endforeach
                    @else
                        <option value="#" disabled>No hay información de Unides de medida</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <span class="input-group-text">Stock actual&nbsp;<i class="fa-solid fa-warehouse"></i></span>
                <input type="number" min="1" name="stock_actual" id="stock_actual" class="form-control" value="{{($producto->stock_actual) ?? old('stock_actual')}}" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Embalajes&nbsp;<i class="fa-solid fa-pallet"></i></span>
                <select name="embalaje_id" id="embalaje_id" class="form-select">
                    <option value="">Seleccione</option>
                    @if(isset($embalajes))
                        @foreach ($embalajes as $embalaje)
                            @if(isset($producto))
                                @if (($producto->embalaje_id == $embalaje->id) or ($producto->unidad_medida_id == old('embalaje_id')))
                                    <option value="{{$embalaje->id}}" selected>{{$embalaje->descripcion}}</option>
                                @else
                                    <option value="{{$embalaje->id}}">{{$embalaje->descripcion}}</option>
                                @endif
                            @else
                                @if ($embalaje->id == old('embalaje_id'))
                                    <option value="{{$embalaje->id}}" selected>{{$embalaje->descripcion}}</option>
                                @else
                                    <option value="{{$embalaje->id}}">{{$embalaje->descripcion}}</option>
                                @endif
                            @endif
                        @endforeach
                    @else
                        <option value="#" disabled>No hay información de Embalajes</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="input-group mb-3">
                <span class="input-group-text">Unidades por embalaje&nbsp;<i class="fa-solid fa-cart-flatbed-suitcase"></i></span>
                <input type="number" min="1" max="99999" name="unidades_por_embalaje" id="unidades_por_embalaje" class="form-control" value="{{$producto->unidades_por_embalaje ?? old('unidades_por_embalaje')}}" required>
            </div>
        </div>
    </div>
    <hr>
    Precios
    <hr>
    <div class="row">
        <div class="col-md-4 col-sm-4 col-lg-4">
            <div class="input-group mb-3">
                <span class="input-group-text">por unidad&nbsp;<i class="fa-solid fa-money-bill"></i></span>
                <input type="number" step="0.01" min="0" placeholder="0.00" name="precio_detal" id="precio_detal" class="form-control" value="{{$producto->precio_detal ?? old('precio_detal')}}" required>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-lg-4">
            <div class="input-group mb-3">
                <span class="input-group-text">por embalaje&nbsp;<i class="fa-solid fa-money-bill"></i></span>
                <input type="number" step="0.01" min="0" placeholder="0.00" name="precio_embalaje" id="precio_embalaje" class="form-control" value="{{$producto->precio_embalaje ?? old('precio_embalaje')}}" required>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-lg-4">
            <div class="input-group mb-3">
                <span class="input-group-text">Costo &nbsp;<i class="fa-solid fa-money-bill"></i></span>
                <input type="number" step="0.01" min="0" placeholder="0.00" name="costo_detal" id="costo_detal" class="form-control" value="{{$producto->costo_detal ?? old('costo_detal')}}" required>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="input-group mb-3">
                <div class="input-group mb-3">
                    <span class="input-group-text">Estatus del producto&nbsp;<i class="fa-solid fa-charging-station"></i></span>
                    <div class="input-group-text">
                        <input class="form-check-input" type="radio" name="active" id="active1" value="1"
                            {{ (isset($producto->active) && $producto->active) || old("active") == "1" ? 'checked' : '' }}>
                        <label class="form-check-label" for="active1">&nbsp;Disponible</label>
                    </div>

                    <div class="input-group-text">
                        <input class="form-check-input" type="radio" name="active" id="active2" value="0"
                            {{ (isset($producto->active) && !$producto->active) || old("active") == "0" ? 'checked' : '' }}>
                        <label class="form-check-label" for="active2">&nbsp;Agotado</label>
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
