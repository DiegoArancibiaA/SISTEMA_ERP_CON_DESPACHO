@extends('include.master')

@section('content')
<div class="container">
    <h2>Listado de Despachos</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vendedor</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dispatches as $dispatch)
                <tr>
                    <td>{{ $dispatch->id }}</td>
                    <td>{{ $dispatch->user->name }}</td>
                    <td>{{ $dispatch->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($dispatch->status) }}</td>
                    <td>
                        <a href="{{ route('dispatches.scan', $dispatch->id) }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-eye"></i> Ver
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a href="{{ route('dispatches.create') }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Nuevo Despacho
    </a>
</div>
@endsection