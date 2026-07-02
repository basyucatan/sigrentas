@extends('layouts.app')
@section('title', __('Contrato'))
@section('content')
<div class="container-fluid">
    <p>Firmar a continuación:</p>
    <canvas id="canvas"></canvas>
    <br>
    <button id="btnLimpiar">Limpiar</button>
    <button id="btnDescargar">Descargar</button>
    <button id="btnGenerarDocumento">Pasar a documento</button>
    <br>
</div>
@include('livewire.contrato.script')
@endsection