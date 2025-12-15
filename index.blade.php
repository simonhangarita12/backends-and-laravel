@extends('layouts.dashboard')

@section('page_heading', 'Cuadro de Ventas')
@section('section')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <button class="btn btn-default" onclick="goBack()">Regresar</button>
                <script>
                    function goBack() {
                        window.history.back();
                    }
                </script>
            </div>
        </div>
        <div class="mt-5">
            <ul class="nav nav-tabs">
                <li class="{{ request('tab', 'ventas') == 'ventas' ? 'active' : '' }}">
                    <a href="#ventas" data-toggle="tab">Ventas</a>
                </li>

                <li class="{{ request('tab') == 'ventas-mes' ? 'active' : '' }}">
                    <a href="#ventas-mes" data-toggle="tab">Ventas mes</a>
                </li>

                <li class="{{ request('tab') == 'cumplimiento-mes' ? 'active' : '' }}">
                    <a href="#cumplimiento-mes" data-toggle="tab">Cumplimiento mes</a>
                </li>
                <li class="{{ request('tab') == 'cumplimiento-asesor' ? 'active' : '' }}">
                    <a href="#cumplimiento-asesor" data-toggle="tab">Cumplimiento asesor</a>
                </li>
                <li class="{{ request('tab') == 'prediccion' ? 'active' : '' }}">
                    <a href="#prediccion" data-toggle="tab">Predicción</a>
                </li>
                <li class="{{ request('tab') == 'cumplimiento_anual' ? 'active' : '' }}">
                    <a href="#cumplimiento_anual" data-toggle="tab">Cumplimiento anual</a>
                </li>
                <li class="{{ request('tab') == 'comisiones' ? 'active' : '' }}">
                    <a href="#comisiones" data-toggle="tab">Comisiones</a>
                </li>
                <li class="{{ request('tab') == 'ventas-producto' ? 'active' : '' }}">
                    <a href="#ventas-producto" data-toggle="tab">Ventas productos</a>
                </li>
                <li class="{{ request('tab') == 'editar' ? 'active' : '' }}">
                    <a href="#editar" data-toggle="tab">Editar</a>
                </li>
                <li class="{{ request('tab') == 'iframe' ? 'active' : '' }}">
                    <a href="#iframe" data-toggle="tab">IFRAME</a>
                </li>
            </ul>

            <div class="tab-content" style="margin-top: 20px;">

                <div class="tab-pane fade in {{ request('tab', 'ventas') == 'ventas' ? 'active' : '' }}" id="ventas">
                    @include('CuadroVentas.partials.ventas')
                </div>

                <div class="tab-pane fade in {{ request('tab') == 'ventas-mes' ? 'active' : '' }}" id="ventas-mes">
                    @include('CuadroVentas.partials.ventas-mes')
                </div>

                <div class="tab-pane fade in {{ request('tab') == 'cumplimiento-mes' ? 'active' : '' }}" id="cumplimiento-mes">
                    @include('CuadroVentas.partials.cumplimiento-mes')
                </div>
                <div class="tab-pane fade in {{ request('tab') == 'cumplimiento-asesor' ? 'active' : '' }}" id="cumplimiento-asesor">
                    @include('CuadroVentas.partials.cumplimiento-asesores')
                </div>
                <div class="tab-pane fade in {{ request('tab') == 'prediccion' ? 'active' : '' }}" id="prediccion">
                    @include('CuadroVentas.partials.prediccion')
                </div>
                <div class="tab-pane fade in {{ request('tab') == 'cumplimiento_anual' ? 'active' : '' }}" id="cumplimiento_anual">
                    @include('CuadroVentas.partials.cumplimiento-anual')
                </div>
                <div class="tab-pane fade in {{ request('tab') == 'comisiones' ? 'active' : '' }}" id="comisiones">
                    @include('CuadroVentas.partials.comisiones')
                </div>
                <div class="tab-pane fade in {{ request('tab') == 'ventas-producto' ? 'active' : '' }}" id="ventas-producto">
                    @include('CuadroVentas.partials.ventas-productos')
                </div>
                <div class="tab-pane fade in {{ request('tab') == 'editar' ? 'active' : '' }}" id="editar">
                    @include('CuadroVentas.partials.tabla-edicion')
                </div>
                <div class="tab-pane fade in {{ request('tab') == 'iframe' ? 'active' : '' }}" id="iframe">
                    @include('CuadroVentas.partials.iframe')
                </div>

            </div>

        </div>
    </div>
@endsection
