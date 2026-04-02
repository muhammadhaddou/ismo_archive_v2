@extends('adminlte::page')
@section('title', 'Retraits temporaires — Bac')
@section('content_header')
    <h1><i class="fas fa-clock"></i> Bac — Retraits temporaires</h1>
@stop
@section('content')

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('documents.bac.temp-out') }}">
            <div class="row">
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('documents.bac.temp-out') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table id="tempout-table" class="table table-bordered table-hover">
            <thead class="bg-warning">
                <tr>
                    <th>Stagiaire</th>
                    <th>CIN</th>
                    <th>Téléphone</th>
                    <th>Filière</th>
                    <th>Groupe</th>
                    <th>Date retrait</th>
                    <th>Deadline (48h)</th>
                    <th>Statut / Retard</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                @php
                    $lastSortie = $doc->movements
                        ->where('action_type', 'Sortie')
                        ->sortByDesc('date_action')
                        ->first();
                    $deadline   = $lastSortie?->deadline
                        ? \Carbon\Carbon::parse($lastSortie->deadline)
                        : null;
                    $isExpired  = $deadline && now()->gt($deadline);
                    $overdue    = null;
                    if ($isExpired) {
                        $diff    = $deadline->diff(now());
                        $overdue = $diff->days > 0
                            ? $diff->days . 'j ' . $diff->h . 'h ' . $diff->i . 'min'
                            : $diff->h . 'h ' . $diff->i . 'min';
                    }
                    $hoursLeft = (!$isExpired && $deadline)
                        ? now()->diffInHours($deadline, false)
                        : null;
                @endphp
                <tr class="{{ $isExpired ? 'table-danger' : '' }}">
                    <td>{{ $doc->trainee->last_name }} {{ $doc->trainee->first_name }}</td>
                    <td>{{ $doc->trainee->cin }}</td>
                    <td>{{ $doc->trainee->phone ?? '—' }}</td>
                    <td>{{ $doc->trainee->filiere->nom_filiere }}</td>
                    <td>{{ $doc->trainee->group }}</td>
                    <td>{{ $lastSortie ? \Carbon\Carbon::parse($lastSortie->date_action)->format('d/m/Y H:i') : '—' }}</td>
                    <td>{{ $deadline ? $deadline->format('d/m/Y H:i') : '—' }}</td>
                    <td>
                        @if($isExpired)
                            <span class="badge badge-danger">
                                <i class="fas fa-exclamation-triangle"></i> Expiré
                            </span>
                            <br>
                            <small class="text-danger font-weight-bold">
                                <i class="fas fa-hourglass-end"></i> Retard: {{ $overdue }}
                            </small>
                        @elseif($hoursLeft !== null)
                            <span class="badge badge-warning">
                                <i class="fas fa-clock"></i> {{ $hoursLeft }}h restantes
                            </span>
                        @else
                            <span class="badge badge-secondary">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('documents.show', $doc) }}"
                           class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('documents.retour', $doc) }}"
                              method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success"
                                onclick="return confirm('Confirmer le retour?')">
                                <i class="fas fa-undo"></i> Retour
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-success">
                        <i class="fas fa-check-circle"></i> Aucun retrait temporaire en cours
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $documents->links() }}
    </div>
</div>
@stop
@section('js')
<script>
    $('#tempout-table').DataTable({
        "language": {"url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/French.json"},
        "paging": false,
        "order": [[7, "desc"]]
    });
    $('.select2').select2();
</script>
@stop