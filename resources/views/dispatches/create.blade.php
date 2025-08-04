@extends('include.master')

@section('title', 'Gestión de Despachos | Nuevo Despacho')

@section('content')
<div class="container">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-blue">
                    <h2 class="text-center">
                        <i class="material-icons">local_shipping</i> Crear Nuevo Despacho
                    </h2>
                </div>
                <div class="body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('dispatches.store') }}">
                        {{ csrf_field() }}
                        
                        <div class="form-group form-float">
                            <div class="form-line focused">
                                <select name="user_id" id="user_id" class="form-control show-tick" required>
                                    <option value="">-- Seleccione Vendedor --</option>
                                    @foreach($sellers as $id => $name)
                                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('user_id'))
                                    <span class="text-danger">{{ $errors->first('user_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect btn-lg">
                                <i class="material-icons">add</i>
                                <span>Crear Despacho</span>
                            </button>
                            
                            <a href="{{ route('dispatches.index') }}" class="btn btn-default waves-effect btn-lg">
                                <i class="material-icons">arrow_back</i>
                                <span>Cancelar</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection