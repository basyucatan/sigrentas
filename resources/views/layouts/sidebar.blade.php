<nav class="navbar bg-body-tertiary fixed-top" style="font-size: 1.2rem;">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="mx-auto">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.png') }}" style="width:40px; height:auto;" alt="Logo">
            </a>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            @guest
                <a href="{{ route('login') }}" class="bot botNegro" title="Iniciar sesión">🟠👤</a>
            @else
                <span class="small fw-semibold text-truncate d-inline-block" style="max-width:120px;">
                    {{ explode(' ', Auth::user()->name)[0] }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="bot botNegro" title="Cerrar sesión">
                        @canany(['admin', 'adminMax']) 🟢⚙️ @else 🟢👤 @endcanany
                    </button>
                </form>
            @endguest
        </div>
        <div class="offcanvas offcanvas-start cardSec" tabindex="-1" id="offcanvasNavbar">
            <div class="cardSec-header">
                <span class="fs-5">Menú</span>
                <button type="button" class="bot botNegro" data-bs-dismiss="offcanvas">X</button>
            </div>
            <div class="cardSec-body">
                <ul class="navbar-nav pe-3">
                    <li class="nav-item custom-dropdown-item">
                        <a href="#" class="nav-link menu-trigger">💼 Admin</a>
                        <ul class="submenu d-none list-unstyled ps-2 border-start">
                            <li><a href="{{ url('/control') }}" class="nav-link small">✨ Control</a></li>
                        </ul>                        
                    </li>
                </ul>                
                <ul class="navbar-nav pe-3">
                    <li class="nav-item custom-dropdown-item">
                        <a href="#" class="nav-link menu-trigger">🔗 Catálogos</a>
                        <ul class="submenu d-none list-unstyled ps-3">
                            <li class="nav-item">
                                <a href="#" class="nav-link menu-trigger">🏠 Organización</a>
                                <ul class="submenu d-none list-unstyled ps-3 border-start">
                                    <li><a href="{{ url('/arbolcasas') }}" class="nav-link small">🌳 Árbol de Casas</a></li>
                                    <li><a href="{{ url('/casas') }}" class="nav-link small">🏡 Casas</a></li>
                                    <li><a href="{{ url('/cuartos') }}" class="nav-link small">🚪 Cuartos</a></li>
                                    <li><a href="{{ url('/inquilinos') }}" class="nav-link small">👥 Inquilinos</a></li>
                                    <li><a href="{{ url('/asignacions') }}" class="nav-link small">📋 Asignaciones</a></li>
                                    <li><a href="{{ url('/contratos') }}" class="nav-link small">📄 Contratos</a></li>
                                    <li><a href="{{ url('/evidencias') }}" class="nav-link small">📷 Evidencias</a></li>
                                    <li><a href="{{ url('/ticketssegs') }}" class="nav-link small">🎫 Seguimiento Tickets</a></li>
                                </ul>
                                <a href="#" class="nav-link menu-trigger">🏠 Generales</a>
                                <ul class="submenu d-none list-unstyled ps-3 border-start">
                                    <li><a href="{{ url('/fallas') }}" class="nav-link small">🚗 Tipos de Falla</a></li>
                                    <li><a href="{{ url('/vehiculos') }}" class="nav-link small">🚗 Vehículos</a></li>
                                    <li><a href="{{ url('/tecnicos') }}" class="nav-link small">🧰 Técnicos</a></li>
                                    <li><a href="{{ url('/prioridads') }}" class="nav-link small">🚨 Prioridades</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link menu-trigger">🧰 Configuración</a>
                                <ul class="submenu d-none list-unstyled ps-3 border-start">
                                    <li><a href="{{ url('/users') }}" class="nav-link small">🧑‍💻 Usuarios</a></li>
                                    <li><a href="{{ url('/catalogos') }}" class="nav-link small">🧩 Básicos</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>                              
            </div>
        </div>
    </div>
</nav>
<style>
    .menu-trigger { cursor: pointer; position: relative; }
    .menu-trigger::after { content: ' ▾'; font-size: 0.8em; color: gray; }
    .menu-trigger.active::after { content: ' ▴'; }
    .submenu { background: rgba(0,0,0,0.02); border-radius: 4px; }
    .nav-link:hover { color: #0d6efd; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuTriggers = document.querySelectorAll('.menu-trigger');
    menuTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const nextSubmenu = this.nextElementSibling;
            if (nextSubmenu) {
                const isHidden = nextSubmenu.classList.contains('d-none');
                this.classList.toggle('active');
                if (isHidden) {
                    nextSubmenu.classList.remove('d-none');
                } else {
                    nextSubmenu.classList.add('d-none');
                }
            }
        });
    });
});
</script>