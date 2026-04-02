@extends('adminlte::page')
@section('title', 'Liste des stagiaires')
@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-users"></i> Liste des stagiaires</h1>
        <a href="{{ route('trainees.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Ajouter
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

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('trainees.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <select name="filiere_id" class="form-control select2">
                        <option value="">— Toutes les filières —</option>
                        @foreach($filieres as $f)
                            <option value="{{ $f->id }}"
                                {{ request('filiere_id') == $f->id ? 'selected' : '' }}>
                                {{ $f->nom_filiere }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="group" class="form-control">
                        <option value="">— Tous les groupes —</option>
                        @foreach($groups as $g)
                            <option value="{{ $g }}"
                                {{ request('group') == $g ? 'selected' : '' }}>
                                {{ $g }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="graduation_year" class="form-control">
                        <option value="">— Toutes les années —</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}"
                                {{ request('graduation_year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('trainees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table id="trainees-table" class="table table-bordered table-hover">
            <thead class="bg-primary">
                <tr>
                    <th>#</th>
                    <th>CIN</th>
                    <th>CEF</th>
                    <th>Nom complet</th>
                    <th>Date naissance</th>
                    <th>Téléphone</th>
                    <th>Filière</th>
                    <th>Groupe</th>
                    <th>Année</th>
                    <th>Documents</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trainees as $trainee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $trainee->cin }}</td>
                    <td>{{ $trainee->cef ?? '—' }}</td>
                    <td>{{ $trainee->last_name }} {{ $trainee->first_name }}</td>
                    <td>{{ $trainee->date_naissance
                        ? \Carbon\Carbon::parse($trainee->date_naissance)->format('d/m/Y')
                        : '—' }}</td>
                    <td>{{ $trainee->phone ?? '—' }}</td>
                    <td>{{ $trainee->filiere->nom_filiere }}</td>
                    <td>{{ $trainee->group }}</td>
                    <td>{{ $trainee->graduation_year }}</td>
                    <td>
                        @php
                            $docs  = $trainee->documents->groupBy('type');
                            $types = ['Bac','Diplome','Attestation','Bulletin'];
                        @endphp
                        @foreach($types as $type)
                            @if(isset($docs[$type]))
                                @php $doc = $docs[$type]->first(); @endphp
                                @if(in_array($doc->status, ['Remis','Final_Out']))
                                    <span class="badge badge-success" title="{{ $type }}">
                                        <i class="fas fa-check"></i> {{ $type }}
                                    </span>
                                @elseif($doc->status == 'Temp_Out')
                                    <span class="badge badge-warning" title="{{ $type }}">
                                        <i class="fas fa-clock"></i> {{ $type }}
                                    </span>
                                @else
                                    <span class="badge badge-secondary" title="{{ $type }}">
                                        <i class="fas fa-archive"></i> {{ $type }}
                                    </span>
                                @endif
                            @else
                                <span class="badge badge-light border" title="{{ $type }}">
                                    <i class="fas fa-times text-danger"></i> {{ $type }}
                                </span>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('trainees.show', $trainee) }}"
                           class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('trainees.edit', $trainee) }}"
                           class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('trainees.destroy', $trainee) }}"
                              method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Confirmer?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $trainees->links() }}
    </div>
</div>
@stop
@section('js')
<script>
    $('#trainees-table').DataTable({
        "language": {"url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/French.json"},
        "paging": false
    });
    $('.select2').select2();
</script>
@stop