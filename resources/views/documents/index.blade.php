@extends('adminlte::page')
@section('title', 'Documents')
@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-folder"></i> Documents — {{ $type ?? 'Tous' }}</h1>
        <a href="{{ route('documents.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter
        </a>
    </div>
@stop
@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('success') }}
    </div>
@endif
<div class="card">
    <div class="card-body">
        <table id="docs-table" class="table table-bordered table-hover">
            <thead class="bg-primary">
                <tr>
                    <th>Stagiaire</th>
                    <th>CIN</th>
                    <th>Type</th>
                    <th>Référence</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                <tr>
                    <td>{{ $doc->trainee->last_name }} {{ $doc->trainee->first_name }}</td>
                    <td>{{ $doc->trainee->cin }}</td>
                    <td><span class="badge badge-primary">{{ $doc->type }}</span></td>
                    <td>{{ $doc->reference_number ?? '—' }}</td>
                    <td>
                        @if($doc->status == 'Stock')
                            <span class="badge badge-success">En stock</span>
                        @elseif($doc->status == 'Temp_Out')
                            <span class="badge badge-warning">Retrait temporaire</span>
                        @elseif($doc->status == 'Final_Out')
                            <span class="badge badge-danger">Retrait définitif</span>
                        @else
                            <span class="badge badge-info">Remis</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('documents.show', $doc) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $documents->links() }}
    </div>
</div>
@stop
@section('js')
<script>
    $('#docs-table').DataTable({
        "language": {"url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/French.json"},
        "paging": false
    });
</script>
@stop