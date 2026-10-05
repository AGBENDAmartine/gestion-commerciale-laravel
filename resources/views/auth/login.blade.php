<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YAONABA ET FRERE - Connexion</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50 0%, #f39c12 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
        }
        .login-header {
            background: #2c3e50;
            padding: 35px;
            text-align: center;
        }
        .login-header h3 {
            color: #f39c12;
            font-weight: bold;
            margin: 0;
            font-size: 24px;
        }
        .login-header p {
            color: #bdc3c7;
            margin: 5px 0 0;
            font-size: 13px;
        }
        .login-body {
            padding: 35px;
        }
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }
        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 0.2rem rgba(243,156,18,0.25);
        }
        .input-group-text {
            background: #f39c12;
            border: none;
            color: #fff;
            border-radius: 8px 0 0 8px;
        }
        .btn-login {
            background: #f39c12;
            border: none;
            color: #fff;
            padding: 13px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: background 0.3s;
        }
        .btn-login:hover {
            background: #e67e22;
            color: #fff;
        }
        .login-footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>

<div class="login-card">
    <!-- En-tête -->
    <div class="login-header">
        <i class="fas fa-tire fa-3x text-warning mb-3"></i>
        <h3>YAONABA & FRERE</h3>
        <p>Application de gestion commerciale</p>
    </div>

    <!-- Formulaire -->
    <div class="login-body">
        <h5 class="mb-4 text-center" style="color:#2c3e50;">
            <i class="fas fa-sign-in-alt"></i> Connexion
        </h5>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-envelope"></i> Adresse email
                </label>
                <input
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="votre@email.com"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-lock"></i> Mot de passe
                </label>
                <input
                    type="password"
                    name="mot_de_passe"
                    class="form-control @error('mot_de_passe') is-invalid @enderror"
                    placeholder="••••••••"
                    required
                >
                @error('mot_de_passe')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Bouton connexion -->
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>
    </div>

    <!-- Pied de page -->
    <div class="login-footer">
        <i class="fas fa-shield-alt"></i>
        Accès réservé au personnel autorisé
    </div>
</div>

</body>
</html>