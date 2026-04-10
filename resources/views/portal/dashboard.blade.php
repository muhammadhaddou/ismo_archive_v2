<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Stagiaire | Tableau de bord</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        .portal-navbar {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 10px 20px;
        }
        .portal-navbar .btn-logout {
            color: white;
            border-color: rgba(255,255,255,0.5);
        }
        .portal-navbar .btn-logout:hover {
            background: rgba(255,255,255,0.1);
        }
    </style>
</head>
<body class="layout-top-nav" style="background-color: #f4f6f9;">

<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md portal-navbar border-bottom-0">
        <div class="container">
            <a href="#" class="navbar-brand text-white">
                <i class="fas fa-user-graduate mr-2"></i>
                <span class="brand-text font-weight-light">ISMO Espace Stagiaire</span>
            </a>

            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                <li class="nav-item">
                    <span class="mr-3 font-weight-bold d-none d-sm-inline-block">{{ $trainee->first_name }} {{ $trainee->last_name }}</span>
                    <form action="{{ route('trainee.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">👋 Bienvenue, {{ $trainee->first_name }} {{ $trainee->last_name }}</h1>
                        <p class="text-muted mt-2 mb-0">Cet espace vous permet de formuler vos demandes de retrait de documents administratifs.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <!-- Formulaire de demande -->
                    <div class="col-lg-4">
                        <div class="card card-primary card-outline shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title m-0"><i class="fas fa-plus-circle mr-1"></i> Nouvelle demande</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('trainee.requests.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Document souhaité *</label>
                                        <select name="document_type" id="document_type" class="form-control" required>
                                            <option value="">-- Sélectionnez --</option>
                                            <option value="Bac">Baccalauréat</option>
                                            <option value="Diplome">Diplôme</option>
                                            <option value="Attestation">Attestation de réussite</option>
                                            <option value="Bulletin">Bulletin de notes</option>
                                        </select>
                                    </div>
                                    
                                    <div id="bac_options" style="display: none;" class="mt-2 mb-3 bg-light p-2 border rounded">
                                        <label class="text-sm border-bottom pb-1 mb-2 d-block">Type de retrait pour le Baccalauréat :</label>
                                        <div class="custom-control custom-radio mb-1">
                                            <input class="custom-control-input" type="radio" id="retrait_temp" name="bac_type" value=" - Temporaire" checked>
                                            <label for="retrait_temp" class="custom-control-label font-weight-normal">Retrait Temporaire (Ex: Pour inscription ou concours)</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="retrait_def" name="bac_type" value=" - Définitif">
                                            <label for="retrait_def" class="custom-control-label font-weight-normal text-danger">Retrait Définitif (Fin de formation ou abandon)</label>
                                        </div>
                                    </div>

                                    <div id="document_conditions" class="alert alert-info py-2 px-3 text-sm shadow-sm" style="display: none;">
                                        <!-- Les conditions s'afficheront ici -->
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-paper-plane mr-1"></i> Soumettre la demande</button>
                                </form>

                                <!-- Disponibilités informatives -->
                                @if($availabilities->count() > 0)
                                <div class="mt-4">
                                    <h6><i class="fas fa-clock mr-1 text-info"></i> Horaires de disponibilité annoncés par l'administration :</h6>
                                    <ul class="list-unstyled text-sm text-muted">
                                        @foreach($availabilities as $type => $av)
                                            @if($av->description)
                                                <li class="mb-1"><strong>{{ $type }} :</strong> {{ $av->description }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                
                            </div>
                        </div>
                    </div>

                    <!-- Liste des demandes -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header border-0">
                                <h3 class="card-title"><i class="fas fa-history mr-1"></i> Historique & Réponses (Boîte de réception)</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-striped table-valign-middle">
                                    <thead>
                                        <tr>
                                            <th>Date de demande</th>
                                            <th>Document</th>
                                            <th>Statut</th>
                                            <th><i class="fas fa-envelope text-primary"></i> Message de l'admin & RDV</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($requests as $req)
                                            <tr>
                                                <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                                                <td><span class="font-weight-bold">{{ $req->document_type }}</span></td>
                                                <td>
                                                    @if($req->status == 'en_attente')
                                                        <span class="badge badge-warning">En attente</span>
                                                    @elseif($req->status == 'planifie')
                                                        <span class="badge badge-info">RDV Fixé</span>
                                                    @elseif($req->status == 'termine')
                                                        <span class="badge badge-success">Retrait Terminé</span>
                                                    @elseif($req->status == 'rejete')
                                                        <span class="badge badge-danger">Demande Rejetée</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($req->appointment_date)
                                                        <div class="text-success font-weight-bold mb-1">
                                                            <i class="far fa-calendar-alt"></i> RDV: {{ $req->appointment_date->format('d/m/Y à H:i') }}
                                                        </div>
                                                    @endif
                                                    @if($req->admin_message)
                                                        <div class="p-2 bg-light border rounded text-sm text-muted">
                                                            <i class="fas fa-comment-dots text-primary"></i> "{{ $req->admin_message }}"
                                                        </div>
                                                    @else
                                                        <span class="text-muted small">Aucun message pour le moment</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    Aucune demande soumise.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        const conditions = {
            'Bac': {
                text: "<strong>Conditions Baccalauréat :</strong><br>Le stagiaire doit se présenter personnellement muni de sa Carte d'Identité Nationale (CIN) originale.",
                icon: "fa-graduation-cap"
            },
            'Diplome': {
                text: "<strong>Conditions Diplôme :</strong><br>Le diplôme original n'est remis qu'au stagiaire en personne ou à une personne munie d'une procuration légalisée.",
                icon: "fa-scroll"
            },
            'Attestation': {
                text: "<strong>Conditions Attestation :</strong><br>L'attestation ne peut être délivrée qu'après validation de vos résultats annuels par le conseil de classe.",
                icon: "fa-file-signature"
            },
            'Bulletin': {
                text: "<strong>Conditions Bulletin de notes :</strong><br>Le bulletin est délivré à la fin de chaque semestre après les délibérations officielles.",
                icon: "fa-list-alt"
            }
        };

        $('#document_type').change(function() {
            var selected = $(this).val();
            
            // Affichage options du BAC
            if (selected === 'Bac') {
                $('#bac_options').slideDown(200);
            } else {
                $('#bac_options').slideUp(200);
            }

            // Affichage des conditions
            if (selected && conditions[selected]) {
                var content = '<div class="d-flex align-items-start"><i class="fas ' + conditions[selected].icon + ' mt-1 mr-2 text-primary" style="font-size:1.2rem;"></i><div>' + conditions[selected].text + '</div></div>';
                $('#document_conditions').html(content).slideDown(200);
            } else {
                $('#document_conditions').slideUp(200);
            }
        });
    });
</script>
</body>
</html>
