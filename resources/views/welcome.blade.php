@extends('layouts.app')

@section('title', __('Welcome'))

@section('content')
<style>
    .contenedorPrincipal {
        min-height: calc(100vh - 2rem);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: linear-gradient(45deg, #000, #022b8a);
        border-radius: 20px;
    }

    .textoFuego {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 2px;
        color: #fff;
        text-shadow:
            0 0 10px #fff,
            0 0 20px #ffcc00,
            0 0 30px #ff4d00,
            0 0 40px #ff4d00;
        text-align: center;
    }
</style>

<div class="container py-1">
    <div class="contenedorPrincipal shadow-lg">

        @include('logo')

        <div class="text-center">
            <div class="textoFuego">
                Web Master Model
            </div>

            <p class="text-white-50 lead mb-0">
                WMM © {{ date('Y') }} | Sistemas Informáticos
            </p>
        </div>

        @auth
            <div>
                <a href="/asistencias" class="bot botVerde">
                    Checador
                </a>
            </div>
        @endauth

    </div>
</div>
@endsection