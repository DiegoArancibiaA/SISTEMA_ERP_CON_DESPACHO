@extends('include.master')

@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>Historial de Despachos</h2>
    </div>

    <div class="card">
        <div class="body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha de Despacho</th>
                        <th>Fecha de Retorno</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dispatches as $dispatch)
                    <tr>
                        <td>{{ $dispatch->id }}</td>
                        <td>{{ $dispatch->dispatch_date }}</td>
                        <td>{{ $dispatch->return_date }}</td>
                        <td>{{ ucfirst($dispatch->status) }}</td>
                        <td>
                            <a href="{{ route('dispatches.details', $dispatch->id) }}" class="btn btn-info btn-sm">
                                Ver Detalles
                            </a>
                        </td>
                    </tr>
                    @endforeach

                    @if($dispatches->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">No hay despachos completados.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
