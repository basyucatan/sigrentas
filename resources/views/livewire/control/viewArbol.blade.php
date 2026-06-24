@section('title', __('Centro de Control'))
<div class="container-fluid px-0 px-sm-3" x-data="{ arbolAbierto: true }">
    <div class="row justify-content-center g-0">
        <div class="col-12">
            <div class="cardSec" style="padding-bottom:40px;">
                <div class="cardSec-header">
                    Control
<button class="bot botBlanco" @click="arbolAbierto = !arbolAbierto">
    <i :class="arbolAbierto ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'"></i>
    <i class="bi bi-house-fill"></i>
</button>
                    <button class="btn btn-success btn-sm ms-2">+ Nuevo</button>
                </div>
                <div class="cardSec-body row g-0 p-0">
                    <div class="col-12 col-md-4 border-end p-2" x-show="arbolAbierto" x-transition>
                        @livewire('arbolcasas')
                    </div>
                    <div class="p-2" :class="arbolAbierto ? 'col-12 col-md-8' : 'col-12'">
                        @include('livewire.control.filtros')
                        <div id="contenedor-tickets">
                            @foreach($tickets as $ticket)
                                <div class="card shadow-sm mb-3">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span><strong>ID: #{{ $ticket->id }}</strong> | Casa: {{ $ticket->cuarto->casa->casa }}-{{ $ticket->cuarto->cuarto }}</span>
                                        <span class="badge bg-danger">ROJO</span>
                                    </div>
                                    <div class="card-body">
                                        <p>{{ $ticket->ticket }}</p>
                                    </div>
                                    <div class="card-footer d-flex gap-2">
                                        <button class="btn btn-outline-info btn-sm">Evidencias</button>
                                        <button class="btn btn-primary btn-sm ms-auto">Procesar</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.control.modals')
</div>