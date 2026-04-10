<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Stagiaire | Connexion</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            width: 400px;
        }
        .portal-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 20px;
            border-radius: .25rem .25rem 0 0;
            text-align: center;
        }
        .portal-header h3 {
            margin: 0;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-box shadow rounded">
    <div class="portal-header">
        <h3><i class="fas fa-user-graduate mr-2"></i>Espace Stagiaire</h3>
        <p class="mb-0 mt-2">ISMO Archive - Retrait de documents</p>
    </div>
    
    <div class="card card-outline card-primary mb-0" style="border-radius: 0 0 .25rem .25rem; border-top: none;">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Connectez-vous pour accéder à vos documents</p>

            @if(session('error'))
                <div class="alert alert-danger text-center p-2 mb-3">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('trainee.login') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="cef" class="form-control" placeholder="Email ou Numéro CEF" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="cin" class="form-control" placeholder="Mot de passe (Votre CIN)" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-id-card"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Se connecter <i class="fas fa-sign-in-alt ml-1"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
