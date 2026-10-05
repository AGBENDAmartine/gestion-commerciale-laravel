<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YAONABA ET FRERE - @yield('titre', 'Gestion Commerciale')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar {
    height: 100vh;
    background: #2c3e50;
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: scroll;
    overflow-x: hidden;
    z-index: 100;
    padding-bottom: 60px;
    scrollbar-width: thin;
    scrollbar-color: #f39c12 #2c3e50;
}
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: #2c3e50; }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: #f39c12;
            border-radius: 10px;
        }
        .sidebar .logo {
            padding: 20px;
            background: #1a252f;
            text-align: center;
        }
        .sidebar .logo h4 {
            color: #f39c12;
            font-weight: bold;
            margin: 0;
            font-size: 16px;
        }
        .sidebar .logo p {
            color: #bdc3c7;
            font-size: 11px;
            margin: 0;
        }
        .sidebar .nav-link {
            color: #bdc3c7;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #f39c12;
            color: #fff;
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        .sidebar .menu-title {
            color: #7f8c8d;
            font-size: 11px;
            padding: 15px 20px 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .sidebar-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 250px;
            background: #1a252f;
            padding: 0;
            z-index: 101;
            height: 55px;
        }
        .main-content {
            margin-left: 250px;
            padding: 0;
        }
        .topbar {
            background: #fff;
            padding: 15px 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar .page-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar .user-info .badge-role {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
        }
        .content-area { padding: 25px; }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .card-header {
            background: #fff;
            border-bottom: 2px solid #f4f6f9;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
        }
        .btn-primary { background: #f39c12; border-color: #f39c12; }
        .btn-primary:hover { background: #e67e22; border-color: #e67e22; }
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            color: #fff;
            margin-bottom: 20px;
        }
        .stat-card .stat-number { font-size: 28px; font-weight: bold; }
        .stat-card .stat-label { font-size: 13px; opacity: 0.9; }
        .alert { border-radius: 8px; }
    </style>
    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="logo">
        <h4><i class="fas fa-tire"></i> YAONABA</h4>
        <p>& FRERE</p>
    </div>

    @auth
        {{-- MENU ADMINISTRATEUR --}}
        @if(auth()->user()->role === 'administrateur')
            <div class="menu-title">Principal</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </a>
            <div class="menu-title">Gestion</div>
            
            </a>
            <div class="menu-title">Administration</div>
            <a href="{{ route('utilisateurs.index') }}" class="nav-link {{ request()->routeIs('utilisateurs.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i> Utilisateurs
            </a>
            <a href="{{ route('parametres.index') }}" class="nav-link {{ request()->routeIs('parametres.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Paramètres
            </a>
        @endif

        {{-- MENU GESTIONNAIRE --}}
        @if(auth()->user()->role === 'gestionnaire')
            <div class="menu-title">Principal</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </a>
            <div class="menu-title">Gestion</div>
            <a href="{{ route('produits.index') }}" class="nav-link {{ request()->routeIs('produits.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Produits
            </a>
            <a href="{{ route('fournisseurs.index') }}" class="nav-link {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}">
                <i class="fas fa-truck"></i> Fournisseurs
            </a>
            <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Clients
            </a>
            <a href="{{ route('achats.index') }}" class="nav-link {{ request()->routeIs('achats.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Achats
            </a>
            <a href="{{ route('ventes.index') }}" class="nav-link {{ request()->routeIs('ventes.*') ? 'active' : '' }}">
                <i class="fas fa-cash-register"></i> Ventes
            </a>
            <a href="{{ route('factures.index') }}" class="nav-link {{ request()->routeIs('factures.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice"></i> Factures
            </a>
            <div class="menu-title">Stock</div>
            <a href="{{ route('stock.index') }}" class="nav-link {{ request()->routeIs('stock.index') ? 'active' : '' }}">
                <i class="fas fa-warehouse"></i> Stock
            </a>
            <a href="{{ route('stock.mouvements') }}" class="nav-link {{ request()->routeIs('stock.mouvements') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i> Mouvements
            </a>
            <a href="{{ route('entrepots.index') }}" class="nav-link {{ request()->routeIs('entrepots.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i> Entrepôts
            </a>
            <div class="menu-title">Rapports</div>
            <a href="{{ route('statistiques.index') }}" class="nav-link {{ request()->routeIs('statistiques.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Statistiques
            </a>
        @endif

        {{-- MENU VENDEUR --}}
        @if(auth()->user()->role === 'vendeur')
            <div class="menu-title">Principal</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </a>
            <div class="menu-title">Ventes</div>
            <a href="{{ route('ventes.index') }}" class="nav-link {{ request()->routeIs('ventes.*') ? 'active' : '' }}">
                <i class="fas fa-cash-register"></i> Ventes
            </a>
            <a href="{{ route('factures.index') }}" class="nav-link {{ request()->routeIs('factures.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice"></i> Factures
            </a>
            <div class="menu-title">Gestion</div>
            <a href="{{ route('produits.index') }}" class="nav-link {{ request()->routeIs('produits.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Produits
            </a>
            <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Clients
            </a>
        @endif

        {{-- MENU MAGASINIER --}}
        @if(auth()->user()->role === 'magasinier')
            <div class="menu-title">Principal</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </a>
            <div class="menu-title">Stock</div>
            <a href="{{ route('stock.index') }}" class="nav-link {{ request()->routeIs('stock.index') ? 'active' : '' }}">
                <i class="fas fa-warehouse"></i> Stock
            </a>
            <a href="{{ route('stock.mouvements') }}" class="nav-link {{ request()->routeIs('stock.mouvements') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i> Mouvements
            </a>
            <a href="{{ route('entrepots.index') }}" class="nav-link {{ request()->routeIs('entrepots.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i> Entrepôts
            </a>
            <div class="menu-title">Produits</div>
            <a href="{{ route('produits.index') }}" class="nav-link {{ request()->routeIs('produits.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Produits
            </a>
        @endif
    @endauth
</div>

<!-- BOUTON DÉCONNEXION FIXE EN BAS -->
@auth
<div class="sidebar-footer">
    <form action="{{ route('logout') }}" method="POST" style="margin:0; padding:0;">
        @csrf
        <button type="submit"
            style="background:none; border:none; color:#bdc3c7; padding:15px 20px; width:100%; text-align:left; font-size:14px; cursor:pointer; display:block; border-top: 1px solid #2c3e50;"
            onmouseover="this.style.background='#e74c3c'; this.style.color='#fff';"
            onmouseout="this.style.background='none'; this.style.color='#bdc3c7';">
            <i class="fas fa-sign-out-alt" style="width:20px; margin-right:10px;"></i> Déconnexion
        </button>
    </form>
</div>
@endauth

<!-- CONTENU PRINCIPAL -->
<div class="main-content">
    <div class="topbar">
        <h5 class="page-title">@yield('titre', 'Tableau de bord')</h5>
        <div class="user-info">
            @auth
                <i class="fas fa-user-circle fa-lg text-secondary"></i>
                <span><strong>{{ auth()->user()->nom }}</strong></span>
                @php
                    $roleColors = [
                        'administrateur' => 'danger',
                        'gestionnaire'   => 'primary',
                        'vendeur'        => 'success',
                        'magasinier'     => 'warning',
                    ];
                    $color = $roleColors[auth()->user()->role] ?? 'secondary';
                @endphp
                <span class="badge bg-{{ $color }} badge-role">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            @endauth
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> {!! session('error') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle"></i>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('contenu')
    </div>
</div>

@stack('scripts')
</body>
</html>