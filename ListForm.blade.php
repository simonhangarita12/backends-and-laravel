@extends('layouts.dashboard')
<style type="text/css">
    .switch {
        position: relative;
        display: inline-block;
        width: 90px;
        height: 34px;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ca2222;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2ab934;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(55px);
        -ms-transform: translateX(55px);
        transform: translateX(55px);
    }

    /*------ ADDED CSS ---------*/
    .on {
        display: none;
    }

    .on,
    .off {
        color: white;
        position: absolute;
        transform: translate(-50%, -50%);
        top: 50%;
        left: 50%;
        font-size: 10px;
        font-family: Verdana, sans-serif;
    }

    input:checked+.slider .on {
        display: block;
    }

    input:checked+.slider .off {
        display: none;
    }

    /*--------- END --------*/

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
@section('section')

    <body>
        <div id="">
            <div class="row">
                <div class="col-lg-12">
                    {{-- <h1 class="page-header" style="color:green;"><i class="fa fa-sign-in"></i>PLAN ESTRATÉGICO DE
                        SEGURIDAD VIAL</h1> --}}
                    <h1 class="page-header text-5xl font-medium text-corporativoS"><i class="fas fa-sign-in-alt"></i>
                        I Planear
                    </h1>
                    <h3 class="text-corporativoS text-2xl font-semibold">{{ $nombreempresa[0]->razonSocial ?? '' }}</h3>
                    <h7 class="text-corporativoN">NOTA: Los archivos mostrados son los del año en curso.</h7> <br><br>
                    <a href="{{ route('panelCentralPesvSGSST', [$id_empresa, 1]) }}" class="btn btn-default">Regresar</a>

                    <div class="col-md-3" style="float: right;">
                        @foreach ($acordionint as $keyint)
                            <select class="form-control" name="selectAnio" id="selectAnio"
                                onchange="selectAnio2(this.value,'{{ $id_diagnostico }}','{{ $id_empresa }}','{{ $keyint->variable ?? '' }}')">
                                <option selected disabled>Seleccione año</option>
                                <script>
                                    var d = new Date();
                                    var n = d.getFullYear();
                                    for (var i = n; i >= 2019; i--)
                                        document.write('<option>' + i + '</option>');
                                </script>
                            </select>
                        @endforeach

                    </div>
                    <!--  <input type="checkbox" name="Te10" id="Te10">
                                                                                                      <input type="text" name="campoinvisible" id="campoinvisible" hidden=""> -->

                    <!-- <script>
                        window.onload = function() {
                            $('#Te10').on('click', function() {
                                if ($("#Te10").is(':checked')) {
                                    $('#campoinvisible').removeAttr('hidden');
                                } else {
                                    $('#campoinvisible').attr('hidden', 'true');
                                }
                            });
                        };
                    </script> -->
                    <br>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <br>
            <!-- /.row -->


            @if (
                $id_diagnostico == 113 ||
                    $id_diagnostico == 55 ||
                    $id_diagnostico == 65 ||
                    $id_diagnostico == 33 ||
                    $id_diagnostico == 86 ||
                    $id_diagnostico == 157 ||
                    $id_diagnostico == 71 ||
                    $id_diagnostico == 152 ||
                    $id_diagnostico == 151 ||
                    $id_diagnostico == 99 ||
                    $id_diagnostico == 120 ||
                    $id_diagnostico == 123 ||
                    $id_diagnostico == 124 ||
                    $id_diagnostico == 156 ||
                    $id_diagnostico == 100 ||
                    $id_diagnostico == 101 ||
                    $id_diagnostico == 102 ||
                    $id_diagnostico == 103 ||
                    $id_diagnostico == 153 ||
                    $id_diagnostico == 104 ||
                    $id_diagnostico == 105 ||
                    $id_diagnostico == 106 ||
                    $id_diagnostico == 107 ||
                    $id_diagnostico == 162 ||
                    $id_diagnostico == 108 ||
                    $id_diagnostico == 109 ||
                    $id_diagnostico == 110 ||
                    $id_diagnostico == 111 ||
                    $id_diagnostico == 158 ||
                    $id_diagnostico == 112 ||
                    $id_diagnostico == 1 ||
                    $id_diagnostico == 2 ||
                    $id_diagnostico == 3 ||
                    $id_diagnostico == 93 ||
                    $id_diagnostico == 94 ||
                    $id_diagnostico == 95 ||
                    $id_diagnostico == 96 ||
                    $id_diagnostico == 97 ||
                    $id_diagnostico == 98 ||
                    $id_diagnostico == 59 ||
                    $id_diagnostico == 54 ||
                    $id_diagnostico == 53 ||
                    $id_diagnostico == 127 ||
                    $id_diagnostico == 38 ||
                    $id_diagnostico == 144 ||
                    $id_diagnostico == 11 ||
                    $id_diagnostico == 16 ||
                    $id_diagnostico == 18 ||
                    $id_diagnostico == 50 ||
                    $id_diagnostico == 155 ||
                    $id_diagnostico == 117 ||
                    $id_diagnostico == 118 ||
                    $id_diagnostico == 121 ||
                    $id_diagnostico == 89 ||
                    $id_diagnostico == 90 ||
                    $id_diagnostico == 92 ||
                    $id_diagnostico == 91 ||
                    $id_diagnostico == 4 ||
                    $id_diagnostico == 5 ||
                    $id_diagnostico == 6 ||
                    $id_diagnostico == 7 ||
                    $id_diagnostico == 8 ||
                    $id_diagnostico == 9 ||
                    $id_diagnostico == 10 ||
                    $id_diagnostico == 12 ||
                    $id_diagnostico == 42 ||
                    $id_diagnostico == 32 ||
                    $id_diagnostico == 26 ||
                    $id_diagnostico == 29 ||
                    $id_diagnostico == 161 ||
                    $id_diagnostico == 163 ||
                    $id_diagnostico == 164 ||
                    $id_diagnostico == 165 ||
                    $id_diagnostico == 166 ||
                    $id_diagnostico == 167 ||
                    $id_diagnostico == 168 ||
                    $id_diagnostico == 169 ||
                    $id_diagnostico == 170 ||
                    $id_diagnostico == 171 ||
                    $id_diagnostico == 172 ||
                    $id_diagnostico == 173 ||
                    $id_diagnostico == 175 ||
                    $id_diagnostico == 218)
                <div class="row">
                    <div class="col-lg-12">

                        <div class="panel panel-ink">
                            <div class="panel-heading flex space-x-1 items-center">

                                @if ($id_diagnostico == 4)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('ActaConformación', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 3)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('directricesDireccion', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 1)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('objetivoPevs', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 26)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('encuestaRiesgo', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 32)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('riegoViales', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 38)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('implementacionAcciones', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 71)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('capacitacionSeguridadVial', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 161)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('generalidadesEmpresa', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 155)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('leccionApren', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 158)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('indicadoresAcci', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 156)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('fuenteInfo', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 153)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('infoDocumentada', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 162)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('valorAgregado', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 127)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('rutasInternas', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 120)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('idoneidadManteniPreven', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 144)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('rutasExternas', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 124)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('inspeccionPreoperacional', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 163)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('newFormat1', [0, $id_empresa, $id_diagnostico]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 164)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('newFormat2', [0, $id_empresa, $id_diagnostico]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 165)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('newFormat3', [0, $id_empresa, $id_diagnostico]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 166)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('newFormat4', [0, $id_empresa, $id_diagnostico]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 167)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('newFormat5', [0, $id_empresa, $id_diagnostico]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 168)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('newFormat6', [0, $id_empresa, $id_diagnostico]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 169)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('newFormat7', [0, $id_empresa, $id_diagnostico]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 170)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('newFormat8', [0, $id_empresa, $id_diagnostico]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 171)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('newFormat9', [0, $id_empresa, $id_diagnostico]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 5)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('objetivoComit', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 172)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('planCompetencia', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 173)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('objetivoComit', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 175)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('objetivoComit', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 218)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('ProRiegoViales', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif(
                                    $id_diagnostico == 99 ||
                                        $id_diagnostico == 100 ||
                                        $id_diagnostico == 101 ||
                                        $id_diagnostico == 102 ||
                                        $id_diagnostico == 103 ||
                                        $id_diagnostico == 104 ||
                                        $id_diagnostico == 105 ||
                                        $id_diagnostico == 106 ||
                                        $id_diagnostico == 107 ||
                                        $id_diagnostico == 108 ||
                                        $id_diagnostico == 109 ||
                                        $id_diagnostico == 110 ||
                                        $id_diagnostico == 111 ||
                                        $id_diagnostico == 112)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('politicasdeSeguridad', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 152)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('atencionaVictimas', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 151)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('protocolos', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 55)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('pruebasIngresoConduc', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 157)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('procedimientoInvest', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 6)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('indexDocComite', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 53)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('pruebasyPerfilConduc', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 54)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('procediSeleccionConduc', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 33)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('planesdeAccion', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 59)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('criterioPruebas', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 86)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('controlDocuConductores', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 50)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('auditorias', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 65)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('pruebascontrolConduc', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 113)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('controlDocuVehiculos', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 117)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('recomendacionesTecni', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 118)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('cronogramaVehiculosPro', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 121)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('mantenimientoCorrectivo', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 123)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('idoneidadManteniCorrec', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 9 || $id_diagnostico == 10)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('responsableEstrategico', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 11)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('docuPoliticaComite', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 16)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('divulgacionPolitica', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 18)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('caracteristicasEmpresa', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 89)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('procedicompa', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif(
                                    $id_diagnostico == 93 ||
                                        $id_diagnostico == 94 ||
                                        $id_diagnostico == 95 ||
                                        $id_diagnostico == 96 ||
                                        $id_diagnostico == 97 ||
                                        $id_diagnostico == 98)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('politicaAlcoholyDrogas', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item "><i
                                            class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id == 10 && $id_diagnostico == 42)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('indicadoresPesv', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento"><i class="fa fa-pencil-square-o"></i>Documento</a>
                                @elseif($id_diagnostico == 12)
                                    <a class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        href="{{ route('politicaComit', [$id_empresa, $id_diagnostico, $id]) }}"
                                        title="Documento para cumplimentar item ">
                                        <i class="fa fa-pencil-square-o"></i>Documento</a>
                                @endif


                                @if ($id_diagnostico == 1)
                                    <a href="/archivos/PESV/2020/SUBMODULO 1 GESTION INSTITUCIONAL/1.1._1.2_ OBJETIVOS PESV.doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 3)
                                    <a href="/archivos/PESV/2020/SUBMODULO 1 GESTION INSTITUCIONAL/1.1.3. Directrices _Compromiso Dirección.doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 4)
                                    <a href="/archivos/PESV/2020/SUBMODULO 1 GESTION INSTITUCIONAL/1.2.1.CONSTITUCION COMITE  PESV.doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 5)
                                    <a href="/archivos/PESV/2020/SUBMODULO 1 GESTION INSTITUCIONAL/1.2.2.Objetivos Comité PESV.doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 6)
                                    <a href="/archivos/PESV/2020/SUBMODULO 1 GESTION INSTITUCIONAL/1.2.3_ 1.2.4 _ 1.2.5. Integrantes_ roles _ periodicidad reuniones.docx"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 9)
                                    <a href="/archivos/PESV/2020/SUBMODULO 1 GESTION INSTITUCIONAL/1.3.1.y 1.3.2. Coordinador Comité PESV.docx"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 12)
                                    <a href="/archivos/PESV/2020/SUBMODULO 1 GESTION INSTITUCIONAL/1.4.2- 1.4.5. Política PESV.doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 26)
                                    <a href="/archivos/PESV/2020/SUBMODULO 1 GESTION INSTITUCIONAL/1.7.1-1.7.3. Encuesta.doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 29)
                                    <a href="/archivos/PESV/2020/SUBMODULO 1 GESTION INSTITUCIONAL/1.7.4 _ 1.7.5._1.7.6.  Análisis resultados encuesta .doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 32)
                                    <a href="/archivos/PESV/2020/SUBMODULO 1 GESTION INSTITUCIONAL/1.7.7.valoración del riesgo .xls"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 33)
                                    <a href="/archivos/PESV/2020/SUBMODULO 1 GESTION INSTITUCIONAL/Numeral 1.8.1-1.8.5 Planes de acción.xlsx"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 42)
                                    <a href="/archivos/PESV/2020/SUBMODULO 1 GESTION INSTITUCIONAL/Numerales 1.10.1-1.10.8 Indicadores.xls"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 53 || $id_diagnostico == 54)
                                    <a href="/archivos/PESV/2020/SUBMODULO 2 COMPORTAMI HUMANO/2.1.1.-2.1.2 Pruebas y perfil conductores .docx"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 59)
                                    <a href="/archivos/PESV/2020/SUBMODULO 2 COMPORTAMI HUMANO/2.2.6..doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 89)
                                    <a href="/archivos/PESV/2020/SUBMODULO 2 COMPORTAMI HUMANO/2.5.6-2.5.7  Procedi comparendos .doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 93)
                                    <a href="/archivos/PESV/2020/SUBMODULO 2 COMPORTAMI HUMANO/2.6.1-2.6.6. política alcohol y drogas.doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 99)
                                    <a href="/archivos/PESV/2020/SUBMODULO 2 COMPORTAMI HUMANO/2.6.7.al 2.6.20 Políticas de seguridad.doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 159)
                                    <a href="/archivos/PESV/2020/SUBMODULO 0/Introducción conceptos y def.doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @elseif($id_diagnostico == 160)
                                    <a href="/archivos/PESV/2020/SUBMODULO 0/NORMATIVIDAD PESV.doc"
                                        title="descarga de documento" target="_blank" style="color: #FFFFFE"><img
                                            class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a>
                                @endif
                                <button
                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                    onclick="subirAdjunto('')" title="Subir evidencia"><i
                                        class="fas fa-sign-in-alt text-sm"></i></button>

                                @if ($id_diagnostico == 4)
                                    <button
                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                        onclick="subirAdjunto22('')" title="Documentos viejos"><i class="fa fa-pencil-square-o"></i> Item 2.2.</button>
                                @endif
                                <div>
                                    @foreach ($acordionint as $keyint)
                                        {{ $keyint->itemDiad }}
                                        -{{ $keyint->variable ?? '' }}
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <center>
                                    <h4 style="color:green;">Listado de documentos</h4>
                                </center>
                                <div class="col-lg-12">
                                    <div class="panel-body">
                                        @if ($id_diagnostico == 4)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($conformacion as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>

                                                            <td>

                                                                Cumple
                                                            </td>

                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="/verconformacion/{{ $general->id }}/{{ $general->id_company }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i>
                                                                </a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="conforcar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')">Ver</button>
                                                                <a href="/conformacionpdf/{{ $general->id }}/{{ $general->id_company }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        @elseif ($id_diagnostico == 218)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_proRiegoViales as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>

                                                            <td>

                                                                Cumple
                                                            </td>

                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="/verProRiegoViales/{{ $general->id }}/{{ $general->id_company }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i>
                                                                </a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="conforcar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')">Ver</button>
                                                                <a href="/pdfProRiegoViales/{{ $general->id }}/{{ $general->id_company }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                            <!--Inicio Documento NUMERALES 1.1.1. y 1.1.2.-->
                                        @elseif($id_diagnostico == 1 || $id_diagnostico == 2)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_docuComite_objetivoPevs as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verobjetivoPevs', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('objetivoPevspdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 1.1.1. y 1.1.2.-->
                                            <!--Inicio Documento NUMERALES 1.1.3.-->
                                        @elseif($id_diagnostico == 3)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_docuComite_directricesDireccion as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verdirectricesDireccion', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('directricesDireccionpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 1.1.3.-->
                                            <!--Inicio Documento NUMERALES 1.2.2.-->
                                        @elseif($id_diagnostico == 5)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_docuComite_obje as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>

                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('verObjetivoComite', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('objetivoComitepdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                            <!--Inicio Documento NUMERALES 1.2.2.-->
                                            <!--Inicio Documento NUMERALES 1.2.3. Y 1.2.5 -->
                                        @elseif($id_diagnostico == 6 || $id_diagnostico == 7 || $id_diagnostico == 8)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_docuComite as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verDocComite', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('docComitepdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>

                                            <!--Fin Documento NUMERALES 1.2.3. Y 1.2.5 -->
                                            <!--Inicio Documento NUMERALES 1.3.1. y 1.3.2. -->
                                        @elseif($id_diagnostico == 9 || $id_diagnostico == 10)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_docuComite_respon as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>

                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('verResponComit', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('responComitpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                            <!--Fin Documento NUMERALES 1.3.1. y 1.3.2. -->
                                            <!--Inicio Documento NUMERALES 1.4.1.-->
                                        @elseif($id_diagnostico == 11)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_docuPoliticaComite as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verdocuPoliticaComite', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('docuPoliticaComitepdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 1.4.1. -->
                                            <!--Inicio Documento NUMERALES 1.4.2.-->
                                        @elseif($id_diagnostico == 12)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_docuComite_politi as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>

                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('verpoliticaComite', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('politicaComitepdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                            <!--Fin Documento NUMERALES 1.4.2. -->
                                            <!--Inicio Documento NUMERALES 1.5.1 al 1.5.2-->
                                        @elseif($id_diagnostico == 16)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_divulgacionPolitica as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verdivulgacionPolitica', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('divulgacionPoliticapdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES  1.5.1 al 1.5.2  -->
                                            <!--Inicio Documento NUMERALES 1.6.1 al 1.6.8-->
                                        @elseif($id_diagnostico == 18)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_caracteristicasEmpresa as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('vercaracteristicasEmpresa', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('caracteristicasEmpresapdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES  1.6.1 al 1.6.8  -->
                                            <!--Inicio Documento NUMERALES 1.8.1 al 1.8.5-->
                                        @elseif($id_diagnostico == 33)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_PlanesAccionResultadoAV as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verplanesdeAccion', [$general->id_registro, $id_empresa, $general->id_diagnostico, $general->id_para]) }}"
                                                                    class="btn btn-warning"
                                                                    title="Editar registro documento"><i
                                                                        class="fa fa-pencil"></i></a>
                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id_registro }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id_registro }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('planesdeAccionpdf', [$general->id_registro, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 1.8.1 al 1.8.5-->
                                            <!--Inicio Documento NUMERALES 1.9.1 al 1.9.4 -->
                                        @elseif($id_diagnostico == 38)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_implementacionAcciones as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verimplementacionAcciones', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>
                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('implementacionAccionespdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 1.9.1 al 1.9.4 -->
                                            <!--Inicio Documento NUMERALES 1.10.1 al 1.10.8-->
                                        @elseif($id_diagnostico == 42)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pevs_indicadoresResultadoAct as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td>
                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 1.10.1 al 1.10.8-->
                                            <!--Inicio Documento NUMERALES 1.10.9 al 1.10.11 -->
                                        @elseif($id_diagnostico == 50)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_auditorias as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verauditorias', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>
                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('auditoriaspdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 1.10.9 al 1.10.11 -->
                                            <!--Inicio Documento NUMERALES 2.1.1. -->
                                        @elseif($id_diagnostico == 53)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_docuComite_pruebasyPerfil as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verpruebasyPerfilConduc', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('pruebasyPerfilConducpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 2.1.1.-->
                                            <!--Inicio Documento NUMERALES  2.1.2. -->
                                        @elseif($id_diagnostico == 54)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_procediSeleccionConduc as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verprocediSeleccionConduc', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('procediSeleccionConducpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 2.1.2.-->
                                            <!--Inicio Documento NUMERALES 2.2.6 -->
                                        @elseif($id_diagnostico == 59)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_docuComite_criterioPruebas as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('vercriterioPruebas', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('criterioPruebaspdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 2.2.6 -->
                                            <!--Inicio Documento NUMERALES 2.2.2. al 2.2.5. 2.2.7. al 2.2.11.-->
                                        @elseif($id_diagnostico == 55)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Nombre Conductor</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_pruebasIngresoConduc as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> PRUEBAS DE INGRESO DE CONDUCTORES</td>
                                                            <td> {{ $general->nombreConductor ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verpruebasIngresoConduc', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('pruebasIngresoConducpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 2.2.2. al 2.2.5. 2.2.7. al 2.2.11.-->
                                            <!--Inicio Documento NUMERALES 2.3.1. al 2.3.6.-->
                                        @elseif($id_diagnostico == 65)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_pruebasControlConduc as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verpruebascontrolConduc', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('pruebascontrolConducpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 2.3.1. al 2.3.6.-->
                                            <!--Inicio Documento NUMERALES 2.4.1. al 2.4.15.-->
                                        @elseif($id_diagnostico == 71)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_programaCapacitacion as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('vercapacitacionSeguridadVial', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('capacitacionSeguridadVialpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 2.4.1. al 2.4.15.-->
                                            <!--Inicio Documento NUMERALES 2.5.1. al 2.5.3-->
                                        @elseif($id_diagnostico == 86)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Nombre Conductor</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_informacionCoductores as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->nombreConductor ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('vercontrolDocuConductores', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('controlDocuConductorespdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 2.5.1. al 2.5.3-->
                                            <!--Inicio Documento NUMERALES 2.5.4.- 2.5.5. - 2.5.6. y 2.5.7-->
                                        @elseif($id_diagnostico == 89 || $id_diagnostico == 90 || $id_diagnostico == 91 || $id_diagnostico == 92)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_docuComite_procedicompa as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verprocedicompa', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('procedicompapdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 2.5.4.- 2.5.5. - 2.5.6. y 2.5.7-->
                                            <!--Inicio Documento NUMERALES 2.6.1. al 2.6.6-->
                                        @elseif(
                                            $id_diagnostico == 93 ||
                                                $id_diagnostico == 94 ||
                                                $id_diagnostico == 95 ||
                                                $id_diagnostico == 96 ||
                                                $id_diagnostico == 97 ||
                                                $id_diagnostico == 98)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_docuComite_politicaAlcoholyDrogas as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verpoliticaAlcoholyDrogas', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento">Ver Registro</a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('politicaAlcoholyDrogaspdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 2.6.1. al 2.6.6 -->
                                            <!--Inicio Documento NUMERALES 2.6.7. al 2.6.20-->
                                        @elseif(
                                            $id_diagnostico == 99 ||
                                                $id_diagnostico == 100 ||
                                                $id_diagnostico == 101 ||
                                                $id_diagnostico == 102 ||
                                                $id_diagnostico == 103 ||
                                                $id_diagnostico == 104 ||
                                                $id_diagnostico == 105 ||
                                                $id_diagnostico == 106 ||
                                                $id_diagnostico == 107 ||
                                                $id_diagnostico == 108 ||
                                                $id_diagnostico == 109 ||
                                                $id_diagnostico == 110 ||
                                                $id_diagnostico == 111 ||
                                                $id_diagnostico == 112)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_docuComite_politicasdeSeguridad as $general)
                                                    <tbody id="">
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verpoliticasdeSeguridad', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento">Ver Registro</a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('politicasdeSeguridadpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 2.6.7. al 2.6.20-->
                                            <!--Inicio Documento NUMERALES 3.1.1 al 3.1.4-->
                                        @elseif($id_diagnostico == 113)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_controlVehicuPropios as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('vercontrolDocuVehiculos', [$general->id_master, $id_empresa]) }}"
                                                                    class="btn btn-warning"
                                                                    title="Editar registro documento"><i
                                                                        class="fa fa-pencil"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('controlDocuVehiculospdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 3.1.1 al 3.1.4-->
                                            <!--Inicio Documento NUMERALES 3.1.5-->
                                        @elseif($id_diagnostico == 117)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_recomendacionesTecni as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verrecomendacionesTecni', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('recomendacionesTecnipdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 3.1.5-->
                                            <!--Inicio Documento NUMERALES 3.1.6 y 3.1.7 -->
                                        @elseif($id_diagnostico == 118)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_cronogramaVehiculosProU as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('vercronogramaVehiculosPro', [$general->id, $id_empresa]) }}"
                                                                    class="btn btn-warning"
                                                                    title="Editar registro documento"><i
                                                                        class="fa fa-pencil"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('cronogramaVehiculosPropdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 3.1.6 y 3.1.7-->
                                            <!--Inicio Documento NUMERALES 3.1.8-->
                                        @elseif($id_diagnostico == 120)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_idoneidadManteniPreven as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('veridoneidadManteniPreven', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('idoneidadManteniPrevenpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 3.1.8-->
                                            <!--Inicio Documento NUMERALES 3.2.1 y 3.2.2 -->
                                        @elseif($id_diagnostico == 121)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_mantenimientoRegisProto as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('vermantenimientoCorrectivo', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('mantenimientoCorrectivopdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 3.2.1 y 3.2.2-->
                                            <!--Inicio Documento NUMERALES 3.2.3-->
                                        @elseif($id_diagnostico == 123)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_idoneidadManteniCorrec as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('veridoneidadManteniCorrec', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('idoneidadManteniPrevenpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 3.2.3-->
                                            <!--Inicio Documento NUMERALES 3.3.1 al 3.3.3-->
                                        @elseif($id_diagnostico == 124)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_chequeoPreoperacional as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verinspeccionPreoperacional', [$general->id, $id_empresa]) }}"
                                                                    class="btn btn-warning"
                                                                    title="Editar registro documento"><i
                                                                        class="fa fa-pencil"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('inspeccionPreoperacionalpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Inicio Documento NUMERALES 3.3.1 al 3.3.3-->
                                            <!--Inicio Documento NUMERALES 4.1.1 al 4.1.17-->
                                        @elseif($id_diagnostico == 127)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_rutasInternas as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verrutasInternas', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('rutasInternaspdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 4.1.1 al 4.1.17-->
                                            <!--Inicio Documento NUMERALES 4.2.1 al 4.2.7 -->
                                        @elseif($id_diagnostico == 144)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_rutasExternas as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verrutasExternas', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('rutasExternaspdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 4.2.1 al 4.2.7 -->
                                            <!--Inicio Documento NUMERALES 5.1.1-->
                                        @elseif($id_diagnostico == 151)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_protocolos as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }} </td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verprotocolos', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('protocolospdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 5.1.1-->
                                            <!--Inicio Documento NUMERALES 5.1.2-->
                                        @elseif($id_diagnostico == 152)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_docuComite_atencionaVictimas as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }} </td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('veratencionaVictimas', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('atencionaVictimaspdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 5.1.2-->
                                            <!--Inicio Documento NUMERALES 5.2.1 al 5.2.2-->
                                        @elseif($id_diagnostico == 153)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_infoDocumentada as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verinfoDocumentada', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('infoDocumentadapdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 5.2.1 al 5.2.2   -->
                                            <!--Inicio Documento NUMERALES 5.2.3-->
                                        @elseif($id_diagnostico == 155)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_docuComite_leccion as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verLeccionApren', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('leccionAprenpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 5.2.3 -->
                                            <!--Inicio Documento NUMERALES 5.2.4-->
                                        @elseif($id_diagnostico == 156)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_fuenteInfo as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }} </td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verfuenteInfo', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('fuenteInfopdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 5.2.4-->
                                            <!--Inicio Documento NUMERALES 5.2.3-->
                                        @elseif($id_diagnostico == 157)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_docuComite_procedimientoInvest as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verprocedimientoInvest', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('procedimientoInvestpdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 5.2.3 -->
                                            <!--Inicio Documento NUMERALES 5.2.6-->
                                        @elseif($id_diagnostico == 158)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_indicadoresAcci as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }} </td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('verindicadoresAcci', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('indicadoresAccipdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 5.2.6-->
                                            <!--Inicio Documento NUMERALES 6.1-->
                                        @elseif($id_diagnostico == 162)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($pesv_valorAgregado as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }} </td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="{{ route('vervalorAgregado', [$general->id, $id_empresa]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('valorAgregadopdf', [$general->id, $id_empresa]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>
                                            <!--Fin Documento NUMERALES 6.1-->
                                        @elseif($id_diagnostico == 161)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($generalidades as $general)
                                                    <tbody>
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td> {{ $general->fechaauditoria ?? '' }}</td>
                                                            <td> {{ $general->auditor ?? '' }}</td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td> {{ $general->observaciones ?? '' }}</td>
                                                            <td><a href="/vergeneralidades/{{ $general->id }}/{{ $general->id_company }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i>
                                                                </a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico">Ver</button>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                @endforeach
                                            </table>

                                            <!--Inicio Documento NUMERALES 1.7.1. al 1.7.3.-->
                                        @elseif($id_diagnostico == 26)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <!--<th>Fecha Auditoria</th>-->
                                                        <!--<th>Auditor</th>-->
                                                        <!--<th>Resultado</th>-->
                                                        <!--<th>Observaciones</th>-->
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($verencuesta as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fechainicio ?? '' }}</td>
                                                            <td> {{ $general->fechafinalizacion ?? '' }}</td>
                                                            <!--<td> {{ $general->fechaauditoria ?? '' }}</td>-->
                                                            <!--<td> {{ $general->auditor ?? '' }}</td>-->
                                                            <!--<td> @if ($general->cumple == 1)
    No Cumple
@elseif($general->cumple == 2)
    No
                                                                                                                 Cumple
@else
    No Calificado
    @endif
                                                                                                                     </td>
                                                                                                                     -->
                                                            <!--<td> {{ $general->observaciones ?? '' }}</td>-->
                                                            <td><a href="/verencuesta/{{ $general->id }}/{{ $general->id_company }}"
                                                                    class="btn btn-info"
                                                                    title="Editar registro documento"><i
                                                                        class="fa fa-eye"></i>
                                                                </a>
                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <a type="button"
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        href="/ListForm/{{ $id_empresa }}/29/7"
                                                                        title="Calificar el Item">
                                                                        <i class="fa fa-pencil-square-o"></i>Calificar</a>
                                                                @endif
                                                                <!--<button class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                                                            onclick="vehistorico2('{{ $general->id }}')"
                                                                                                            title="Ver historico">Ver</button>-->
                                                                <a href="/encuestapdf/{{ $general->id }}/{{ $general->id_company }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                    @endforeach
                                                    </tr>

                                            </table>
                                            <!--Fin Documento NUMERALES 1.7.1. al 1.7.3.-->
                                            <!--Inicio Documento NUMERALES 1.7.4 al 1.7.6 .-->
                                        @elseif($id_diagnostico == 29)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th>Consolidación y análisis</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($verencuesta as $general)
                                                        @php $encontrado = false @endphp
                                                        @foreach ($pesv_reporteencuesta as $value)
                                                            @if ($general->id == $value->id_encuesta)
                                                                @php $encontrado = true @endphp
                                                            @endif
                                                        @endforeach
                                                        <tr>
                                                            <td>{{ $keyint->variable ?? '' }}</td>
                                                            <td>{{ $general->fechainicio ?? '' }}</td>
                                                            <td>{{ $general->fechafinalizacion ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>
                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (!$encontrado)
                                                                    <a href="/reporteencuesta/{{ $general->id_company }}"
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        title="Generar análisis de encuesta">
                                                                        <i class="fa fa-pencil-square-o"></i>Consolidación
                                                                    </a>
                                                                @else
                                                                    <a href="/verreporteencuesta/{{ $general->id }}/{{ $general->id_company }}"
                                                                        class="btn btn-info"
                                                                        title="Ver análisis de encuesta">
                                                                        <i class="fa fa-eye"></i>Consolidado
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_paradiagnostico }}','efe')"
                                                                        title="Calificar el Item">Calificar</button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico">Ver</button>
                                                                <a href="/reporteencuestapdf/{{ $general->id }}/{{ $general->id_company }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>



                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <!--Fin Documento NUMERALES 163-->
                                        @elseif($id_diagnostico == 163)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_liderazgo as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>

                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('newFormat1', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_empresa }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('newFormat1PDF', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                            <!--Inicio Documento NUMERALES 163 -->

                                            <!--Fin Documento NUMERALES 164-->
                                        @elseif($id_diagnostico == 164)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_riesgoVial as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }} </td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>
                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('newFormat2', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_empresa }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('newFormat2PDF', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            <!--Inicio Documento NUMERALES 164 -->

                                            <!--Fin Documento NUMERALES 165-->
                                        @elseif($id_diagnostico == 165)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_objetivopesv as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>

                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('newFormat3', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_empresa }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('newFormat3PDF', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            <!--Inicio Documento NUMERALES 165 -->

                                            <!--Fin Documento NUMERALES 166-->
                                        @elseif($id_diagnostico == 166)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_programa as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>

                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('newFormat4', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>


                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_empresa }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('newFormat4PDF', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        @elseif($id_diagnostico == 167)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered text-center dataTable"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_velocidadsegura as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>
                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('newFormat5', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_empresa }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('newFormat5PDF', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        @elseif($id_diagnostico == 168)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_fatiga as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>

                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('newFormat6', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_empresa }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('newFormat6PDF', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        @elseif($id_diagnostico == 169)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_prevencion as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>
                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('newFormat7', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_empresa }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('newFormat7PDF', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        @elseif($id_diagnostico == 170)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_tolerancia as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>
                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('newFormat8', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_empresa }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('newFormat8PDF', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            <!--Inicio Documento NUMERALES 171 -->
                                        @elseif($id_diagnostico == 171)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($pesv_proteccion as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('newFormat9', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_empresa }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="Calificar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico calificación">Ver</button>
                                                                <a href="{{ route('newFormat9PDF', [$general->id, $id_empresa, $id_diagnostico]) }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            <!--fin Documento NUMERALES 171 -->
                                        @elseif($id_diagnostico == 32)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Calificación Virtual</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($verriesgopeligro as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                Cumple
                                                            </td>
                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="/verriegoViales/{{ $general->id }}/{{ $general->id_company }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="valorpeligrovercar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico">Ver</button>
                                                                <a href="/riesgoVialespdf/{{ $general->id }}/{{ $general->id_company }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                    @endforeach

                                                    </tr>
                                            </table>
                                        @elseif($id_diagnostico == 172 || $id_diagnostico == 173 || $id_diagnostico == 175)
                                            <table id=""
                                                class="display table-condensed table-striped table-bordered"
                                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha finalización</th>
                                                        <th>Fecha Auditoria</th>
                                                        <th>Auditor</th>
                                                        <th>Resultado</th>
                                                        <th>Observaciones</th>
                                                        <th style="align-items: center">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaaniosPESV">
                                                    @foreach ($hacerDocs as $general)
                                                        <tr>
                                                            <td> {{ $keyint->variable ?? '' }}</td>
                                                            <td> {{ $general->fecha ?? '' }}</td>
                                                            <td> {{ $general->fechafin ?? '' }}</td>
                                                            <td>
                                                                @if ($general->fechaauditoria == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->fechaauditoria ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->auditor == null)
                                                                    Sin Auditar
                                                                @else
                                                                    {{ $general->auditor ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->cumple == 1)
                                                                    Cumple
                                                                @elseif($general->cumple == 2)
                                                                    No
                                                                    Cumple
                                                                @else
                                                                    No Calificado
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($general->observaciones == null)
                                                                    Sin Observaciones
                                                                @else
                                                                    {{ $general->observaciones ?? '' }}
                                                                @endif
                                                            </td>
                                                            <td><a href="/verriegoViales/{{ $general->id }}/{{ $general->id_company }}"
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    title="Ver registro documento"><i
                                                                        class="fa fa-eye"></i></a>

                                                                @if (Sentinel::getUser()->role_id == 1 || Sentinel::getUser()->role_id == 8 || Sentinel::getUser()->role_id == 13)
                                                                    <button
                                                                        class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        onclick="qualifyItemm('{{ $general->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $general->resultado ?? '' }}','{{ $general->id_company }}','{{ $general->id_diagnostico }}','{{ $general->id_para }}','efe')"
                                                                        title="valorpeligrovercar el Item">Calificar
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    onclick="vehistorico2('{{ $general->id }}')"
                                                                    title="Ver historico">Ver</button>
                                                                <a href="/riesgoVialespdf/{{ $general->id }}/{{ $general->id_company }}"
                                                                    title="descarga de documento en PDF" target="_blank"
                                                                    class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                    style="color: #FFFFFE"><img
                                                                        src="/assets/img/logopdf.png" target="_blank"
                                                                        style="color: #FFFFFE"></a>
                                                            </td>
                                                    @endforeach

                                                    </tr>
                                            </table>
                                            <!--Fin Documento NUMERALES 1.7.7 -->
                                        @endif

                                    </div><!-- .panel-body -->

                                </div> <!-- /.col-lg-12 -->
                            </div>


                        </div><!-- /.panel -->

                    </div> <!-- /.col-lg-12 -->
                </div>
            @else
            @endif

            <div class="row">
                <div class="col-md-12">
                    <center>
                        <h4 style="color:green;">Listado de documentos subidos Externamente</h4>
                    </center>
                    <div class="col-md-3" style="float: right;">
                        <select class="form-control" name="selectAnioFile" id="selectAnioFile"
                            onchange="VerHistoricoanualfile(this.value,'{{ $id_empresa ?? '' }}','{{ $id_diagnostico ?? '' }}','{{ $id ?? '' }}')">
                            <option>Seleccione año</option>
                            <script>
                                var d = new Date();
                                var n = d.getFullYear();
                                for (var i = n; i >= 2019; i--)
                                    document.write('<option>' + i + '</option>');
                            </script>
                        </select>
                    </div> <br><br>
                    <table id="" class="display table-condensed table-striped table-bordered" width="100%"
                        border="0" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th>Evidencia</th>
                                <th>Fecha</th>
                                <th>Fecha finalización</th>
                                <th>Fecha Auditoria</th>
                                <th>Auditor</th>
                                <th>Resultado</th>
                                <th>Observaciones</th>
                                <th style="align-items: center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaaniospesvfile">
                            @foreach ($verupdocs as $updoc)
                                @if ($updoc->id_diagnostico == $id_diagnostico && $updoc->id_para == $id)
                                    <tr>
                                        <td><a href="{{ $updoc->archivo }}" target="_blank">Ver Archivo</a></td>
                                        <td>{{ $updoc->fecha ?? '' }} </td>
                                        <td>{{ $updoc->fechafin ?? '' }}</td>
                                        <td>
                                            @if ($updoc->fechaauditoria == null)
                                                Sin Auditar
                                            @else
                                                {{ $updoc->fechaauditoria ?? '' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($updoc->auditor == null)
                                                Sin Auditar
                                            @else
                                                {{ $updoc->auditor ?? '' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($updoc->resultado == 1)
                                                Cumple
                                            @elseif($updoc->resultado == 2)
                                                No Cumple
                                            @else
                                                No
                                                Calificado
                                            @endif
                                        </td>
                                        <td>
                                            @if ($updoc->observaciones == null)
                                                Sin Observaciones
                                            @else
                                                {{ $updoc->observaciones ?? '' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (Sentinel::getUser()->role_id == 1 ||
                                                    Sentinel::getUser()->role_id == 6 ||
                                                    Sentinel::getUser()->role_id == 8 ||
                                                    Sentinel::getUser()->role_id == 13)
                                                <button
                                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                    onclick="qualifyItemm('{{ $updoc->id }}','{{ $keyint->itemDiad }}','{{ $keyint->variable ?? '' }}','{{ $updoc->resultado }}','{{ $updoc->id_company }}','{{ $updoc->id_diagnostico }}','{{ $updoc->id_para }}',8)"
                                                    title="Calificar el Item">Calificar
                                                </button>
                                            @endif
                                            <button
                                                class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                onclick="vehistorico('{{ $updoc->id }}')"
                                                title="Ver historico calificación">Ver</button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                    <!--Fin Documento NUMERALES 5.2.5.-->

                </div>
            </div>

            <!-- Trigger the modal with a button -->


            <!-- Modal -->
            <div class="modal" id="mymodal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">

                    <div id="borrarmodal2">
                        <div class="modal-content">
                            <div class="modal-header" id="title_modal">
                                <h4>Historico de la calificación</h4>
                            </div>
                            <div class="modal-body">

                                <div class="text-center">

                                    <div class="form-group">
                                        <input type="hidden" id="valorarhivo" value="">
                                        <div id="divverhisto">

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">
                                <div class="text-center">
                                    <div class="form-group">
                                        <button type="button"
                                            class="c-btnS focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                            data-dismiss="modal">Regresar</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /#page-wrapper -->

            {{-- Modal para subir evidencias --}}
            <div class="modal" id="modalAdjunto" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" id="title_modal">
                            <div class="text-center">
                                <div id="contenido"></div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form id="adjuntoEvidencia" name="adjuntoEvidencia" action="/createupdoc" method="POST"
                                enctype="multipart/form-data" novalidate class="form">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id_empresa" id="id_empresa"
                                    value="{{ $id_empresa }}">
                                <input type="hidden" name="id_diagnostico" id="id_diagnostico"
                                    value="{{ $id_diagnostico }}">
                                <input type="hidden" name="id_para" id="id_para" value="{{ $id }}">
                                @foreach ($acordionint as $keyint)
                                    <input type="hidden" name="id_parametro" id="id_parametro"
                                        value="{{ $keyint->id_parametro }}">
                                    <input type="hidden" name="itemDiad" id="itemDiad"
                                        value="{{ $keyint->itemDiad }}">
                                @endforeach

                                @foreach ($acordionPESVint as $keyint2)
                                    <input type="hidden" name="id_itemP" id="id_itemP"
                                        value="{{ $keyint2->id_itemP }}">
                                @endforeach
                                <div class="text-center">
                                    <div class="form-group">
                                        <input type="file" name="archivoup[]" class="form-control">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <select required class="form-control" name="anioEv" id="anioEv">
                                            <option selected disabled>Seleccione año</option>
                                            <script>
                                                var d = new Date();
                                                var n = d.getFullYear();
                                                for (var i = n; i >= 2015; i--)
                                                    document.write('<option>' + i + '</option>');
                                            </script>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <button type="submit"
                                            class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white c-btn">Aceptar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                class="c-btnS focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="modalAdjunto22" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" id="title_modal">
                            <div class="text-center">
                                <div id="contenido22"></div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <center>
                                <h4 style="color:green;">Listado de documentos</h4>
                            </center><br>
                            <select class="form-control" name="selectAnio" id="selectAnio"
                                onchange="selectAnio2(this.value,'5','{{ $id_empresa }}','{{ $keyint->variable ?? '' }}')">
                                <option selected disabled>Seleccione año</option>
                                <script>
                                    var d = new Date();
                                    var n = d.getFullYear();
                                    for (var i = n; i >= 2019; i--)
                                        document.write('<option>' + i + '</option>');
                                </script>
                            </select> <br><br>
                            <table id="" class="display table-condensed table-striped table-bordered"
                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Fecha</th>
                                        <th>Fecha finalización</th>
                                    </tr>
                                </thead>

                                <tbody id="tablaaniosPESV22">

                                </tbody>

                            </table>


                            <center>
                                <h4 style="color:green;">Listado de documentos subidos Externamente</h4>
                            </center><br>
                            <select class="form-control" name="selectAnioFile" id="selectAnioFile"
                                onchange="VerHistoricoanualfile(this.value,'{{ $id_empresa ?? '' }}','5','{{ $id ?? '' }}')">
                                <option>Seleccione año</option>
                                <script>
                                    var d = new Date();
                                    var n = d.getFullYear();
                                    for (var i = n; i >= 2019; i--)
                                        document.write('<option>' + i + '</option>');
                                </script>
                            </select> <br><br>
                            <table id="" class="display table-condensed table-striped table-bordered"
                                width="100%" border="0" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Fecha</th>
                                        <th>Fecha finalización</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaaniospesvfile22">

                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                class="c-btnS focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal de calificaciòn del Item -->
            <div class="modal" id="qualificationItem" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <form id="qualifyItem" name="qualifyItem" action="{{ route('createcalificacion') }}"
                        method="POST">
                        <div class="modal-content">
                            <div class="modal-header" id="title_modal">
                                <div class="text-center">
                                    <div id="contenidoTitlequalif"></div>
                                </div>
                            </div>
                            <div class="modal-body">

                                {!! csrf_field() !!}
                                <div id="borrarmodal1">
                                    <input type="hidden" name="id_archivo" id="id_item">
                                    <input type="hidden" name="valor_item" id="valor_item">
                                    <input type="hidden" name="id_companyItem" id="id_companyItem">
                                    <input type="hidden" name="diagnostico" id="diagnostico">
                                    <input type="hidden" name="numeral_item" id="numeral_item">
                                    <input type="hidden" name="tipo" value="1">
                                    <input type="hidden" name="para" id="para">
                                    <input type="hidden" name="idfile" id="idfile">
                                    <div class="text-center">
                                        <div class="alert alert-info" role="alert">
                                            <div id="contentItem"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>¿Cumple?</label>
                                            <br>
                                            <div id="inpItemCal">
                                                <label class="switch">
                                                    <input type="checkbox" id="togBtn" name="togBtn"
                                                        onchange="calificarItem()">
                                                    <div class="slider round">
                                                        <!--ADDED HTML -->
                                                        <span class="on">Sí</span>
                                                        <span class="off">No</span>
                                                        <!--END-->
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div id="descItem"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <div class="text-center">
                                        <div class="form-group">
                                            <label>Observaciones</label>
                                            <textarea class="form-control" name="observaciones"></textarea>
                                            <p>Si no se van a realizar cambios pulsar regresar</p><br>
                                            <button type="submit"
                                                class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white c-btn">Aceptar</button>
                                            <button type="button"
                                                class="c-btnS focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                data-dismiss="modal">Regresar</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal para auditar  -->

            <div class="modal" id="modalAuditarEvidence" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" id="title_modal">
                            <div class="text-center">
                                <h4> AUDITORIA de ITEM SG-SST </h4>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <div class="alert alert-warning" role="alert">
                                    <i class="fa fa-exclamation-triangle fa-3x"></i><br>
                                    <span>¿Está realizando Auditoria a la empresa ?</span>
                                </div>
                                <span>Recuerde que los cambios realizados no tienen retroceso.</span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="" class="btn btn-warning" id="redirectAuditar">Aceptar</a>
                            <button type="button"
                                class="c-btnS focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="modalAuditarEvidenceD" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" id="title_modal">
                            <div class="text-center">
                                <h4> AUDITORIA de ITEM SG-SSTdd </h4>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <div class="alert alert-warning" role="alert">
                                    <i class="fa fa-exclamation-triangle fa-3x"></i><br>
                                    <span>¿Está realizando Auditoria a la empresa
                                        <!-- ? -->
                                    </span>
                                </div>
                                <span>Recuerde que los cambios realizados no tienen retroceso.</span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="" class="btn btn-warning" id="redirectAuditarD">Aceptar</a>
                            <button type="button"
                                class="c-btnS focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para auditar por segunda vez  -->
            <div class="modal" id="modalAuditarSEvidence" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" id="title_modal">
                            <div class="text-center">
                                <h4> SEGUNDA AUDITORIA DE ARCHIVO ACTUALIZADO </h4>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <div class="alert alert-warning" role="alert">
                                    <i class="fa fa-exclamation-triangle fa-3x"></i><br>
                                    <span>¿Está realizando Auditoria a la empresa
                                        <!-- ? -->
                                    </span>
                                </div>
                                <span>Recuerde que los cambios realizados no tienen retroceso.</span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="" class="btn btn-warning" id="redirectAuditarS">Aceptar</a>
                            <button type="button"
                                class="c-btnS focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

    </body>

    <script type="text/javascript">
        function VerHistoricoanualfile(anio, company_id, id_diagnostico, id) {
            const action = `/VeraniosPesvFile/${anio}/${company_id}/${id_diagnostico}/${id}`;
            const tableDetalle3 = [];
            const rol = {{ Sentinel::getUser()->role_id }};
            const keyint = {!! json_encode($keyint) !!};

            // Definir la variable antes del if
            let $tablaaniospesvfile;

            if (id_diagnostico == 5) {
                $tablaaniospesvfile = $('#tablaaniospesvfile22');
            } else {
                $tablaaniospesvfile = $('#tablaaniospesvfile');
            }

            $tablaaniospesvfile.empty();

            $.ajax({
                url: action,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    response.forEach(function(value) {
                        const resultado = value.resultado === '1' ? 'Cumple' : value.resultado === '2' ?
                            'No Cumple' : 'Sin Auditar';

                        const $calificarBtn = $(
                                `<button class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white" style="margin:4px;" title="Calificar el Item">Calificar</button>`
                            )
                            .click(function() {
                                qualifyItemm(value.id, keyint.itemDiad, keyint.variable, value
                                    .resultado, value.id_company, value.id_diagnostico, value
                                    .id_para, 8);
                            });

                        const $verBtn = $(
                                `<button class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white" title="Ver historico calificación">Ver</button>`
                            )
                            .click(function() {
                                vehistorico(value.id);
                            });

                        const $calificarBtnWrapper = (rol === 1 || rol === 6 || rol === 8 || rol ===
                            13) ? $calificarBtn : '';
                        const $buttonsWrapper = $(`<td></td>`).append($calificarBtnWrapper, $verBtn);

                        // Definir la fila antes del if
                        let $row = $('<tr></tr>');

                        if (id_diagnostico == 5) {
                            $row.append(
                                    `<td><a href="${value.archivo}" target="_blank">Ver Archivo</a></td>`
                                    )
                                .append(`<td>${value.fecha}</td>`)
                                .append(`<td>${value.fechafin}</td>`)
                        } else {
                            $row.append(
                                    `<td><a href="${value.archivo}" target="_blank">Ver Archivo</a></td>`
                                    )
                                .append(`<td>${value.fecha}</td>`)
                                .append(`<td>${value.fechafin}</td>`)
                                .append(`<td>${value.fechaauditoria || 'Sin Auditar'}</td>`)
                                .append(`<td>${value.auditor || 'Sin Auditar'}</td>`)
                                .append(`<td>${resultado}</td>`)
                                .append(`<td>${value.observaciones || 'Sin Observaciones'}</td>`);
                        }

                        $row.append($buttonsWrapper);
                        tableDetalle3.push($row);
                    });

                    $tablaaniospesvfile.append(tableDetalle3);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
    </script>


    <script type="text/javascript">
        function subirAdjunto(id) {
            $('#id').val(id);
            $('#modalAdjunto').modal();
            $('#contenido').empty();


            $('#id').val(id);

            let contenido = "<div class='text-center'>";
            contenido += "<h5>Subir Evidencia</h5>";
            contenido += "</div>";
            $('#contenido').append(contenido);
            $('#modalAdjunto').modal();
        }

        function subirAdjunto22(id) {
            $('#id').val(id);
            $('#modalAdjunto22').modal();
            $('#contenido22').empty();


            $('#id').val(id);

            let contenido = "<div class='text-center'>";
            contenido += "<h4>Documentos Antiguos Item 2.2.</h4>";
            contenido += "</div>";
            $('#contenido22').append(contenido);
            $('#modalAdjunto22').modal();
        }

        function selectAnio2(anio, id_diagnostico, id_empresa, nombreDocumento) {


            if (id_diagnostico == 5) {
                $('#tablaaniosPESV22').empty();
            } else {
                $('#tablaaniosPESV').empty();
            }
            let nombreDoc = nombreDocumento;
            let action = '/VeraniosgPESV/' + anio + '/' + id_diagnostico + '/' + id_empresa;
            let tableDetalle2 = '';

            $.ajax({
                url: action,
                type: 'GET',
                dataType: 'json',

                success: function(response) {
                    let cml = null;
                    let efe = 'efe';

                    let datos = response;
                    let consultaAnio = datos.consultaAnio;
                    let nombreDocumento = datos.nombreDocumento;
                    let pdfUrl = datos.pdfUrl;

                    let verencuestajson = {!! json_encode($verencuesta, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!};
                    let reportencuestajson = {!! json_encode($pesv_reporteencuesta, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!};
                    let keyint = {!! json_encode($keyint) !!};
                    let general = {!! json_encode($general) !!};
                    let verencuesta, reportencuesta;

                    try {
                        verencuesta = JSON.parse(verencuestajson);
                        reportencuesta = JSON.parse(reportencuestajson);
                    } catch (e) {
                        console.error("Error parsing JSON:", e);
                    }

                    console.log('verencuesta:', verencuesta);
                    console.log('reportencuesta:', reportencuesta);

                    if (id_diagnostico == 26) {
                        $.each(consultaAnio, function(index, value) {
                            tableDetalle2 += "<tr>";
                            tableDetalle2 += "<td>" + nombreDoc + "</td>";
                            tableDetalle2 += "<td>" + value.fechainicio + "</td>";
                            tableDetalle2 += "<td>" + value.fechafinalizacion + "</td>";
                            tableDetalle2 +=
                                `<td><a href='/${nombreDocumento}/${value.id}/${id_empresa}
                                "' class='btn btn-info' title='Ver registro documento'><i class='fa fa-eye'></i></a> <a type="button" class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                                                        href="/ListForm/{{ $id_empresa }}/29/7"
                                                                        title="Calificar el Item">
                                                                        <i class="fa fa-pencil-square-o"></i>Calificar</a> <a href='/${pdfUrl}/${value.id}/${id_empresa}
                                "' title='descarga de documento en PDF' target='_blank' style='color: #FFFFFE'><img src='/assets/img/logopdf.png' target='_blank' style='color: #FFFFFE'></a></td> `;
                            tableDetalle2 += "</tr>";
                        });

                    } else if (id_diagnostico == 29) {
                        $.each(consultaAnio, function(index, value) {
                            console.log(value)
                            let encontrado = false;
                            tableDetalle2 += "<tr>";
                            tableDetalle2 += "<td>" + nombreDoc + "</td>";
                            tableDetalle2 += "<td>" + value.fechainicio + "</td>";
                            tableDetalle2 += "<td>" + value.fechafinalizacion + "</td>";
                            tableDetalle2 +=
                                `<td> ${value.fechaauditoria == null? 'Sin Auditar': value.fechaauditoria}</td>`;
                            tableDetalle2 +=
                                `<td> ${value.auditor == null ? 'Sin Auditar' : value.auditor}</td>`;
                            if (value.cumple == 1) {
                                tableDetalle2 += "<td>Cumple</td>";
                            } else {
                                tableDetalle2 += "<td>No Cumple</td>";
                            }
                            if (value.calificacion == 1) {
                                tableDetalle2 += "<td>Cumple</td>";
                            } else {
                                tableDetalle2 += "<td>No Cumple</td>";
                            }
                            if (value.observaciones == null) {
                                tableDetalle2 += "<td>Sin Observaciones</td>";
                            } else {
                                tableDetalle2 += "<td>" + value.observaciones + "</td>";
                            }

                            reportencuesta.forEach((item) => {
                                if (item.id_encuesta == value.id) {
                                    encontrado = true;
                                }
                            })

                            if (encontrado) {
                                tableDetalle2 +=
                                    `
                                <td>
                                    <a href="/verreporteencuesta/${value.id}/${value.id_company}"
                                    class="btn btn-info"
                                    title="Ver análisis de encuesta">
                                        <i class="fa fa-eye"></i>Consolidado
                                    </a>
                                </td>
                            `
                            } else {
                                tableDetalle2 +=
                                    `
                                <td>
                                    <a href="/reporteencuesta/${value.id}/${value.id_company}"
                                    class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white"
                                    title="Generar análisis de encuesta">
                                        <i class="fa fa-pencil-square-o"></i>Consolidación
                                    </a>
                                </td>
                            `
                            }

                            tableDetalle2 += `
                            <td>
                                <button class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white" onclick="qualifyItemm('${value.id}','${keyint.itemDiad}','${keyint.variable}','${cml}','${value.id_company}','26','${general.id_para}','${efe}')"
                                    title='Calificar el Item'>
                                    Calificar
                                </button>
                                <a href='${pdfUrl}/${value.id}/${id_empresa}'
                                    title='descarga de documento en PDF'
                                    target='_blank'
                                    style='color: #FFFFFE'>
                                    <img src='/assets/img/logopdf.png' target='_blank' style='color: #FFFFFE'>
                                </a>
                                <button class='focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white' onclick='vehistorico2(${value.id})'
                                    title='Ver historico calificación'>
                                    Ver
                                </button>
                            </td>
                            `;
                            tableDetalle2 += "</tr>";

                        });
                    } else if (id_diagnostico == 5) {
                        $.each(consultaAnio, function(index, value) {
                            tableDetalle2 += "<tr>";
                            tableDetalle2 += `<td>
                        <a href='/${pdfUrl}/${value.id}/${id_empresa}' 
                           title='Descarga de documento en PDF' 
                           target='_blank' 
                           style='color: #FFFFFE'>
                            <img src='/assets/img/logopdf.png' alt='PDF' />
                        </a>
                      </td>`;
                            tableDetalle2 += "<td>" + value.fecha + "</td>";
                            tableDetalle2 += "<td>" + value.fechafin + "</td>";
                            tableDetalle2 += "</tr>";
                        });
                    } else {
                        $.each(consultaAnio, function(index, value) {


                            tableDetalle2 += "<tr>";
                            tableDetalle2 += "<td>" + nombreDoc + "</td>";
                            tableDetalle2 += "<td>" + value.fecha + "</td>";
                            tableDetalle2 += "<td>" + value.fechafin + "</td>";
                            console.log(value)

                            if (value.auditor == null) {
                                tableDetalle2 += "<td>Sin Auditar</td>";
                            } else {
                                tableDetalle2 += "<td>" + value.fechaauditoria + "</td>";
                            }

                            if (value.auditor == null) {
                                tableDetalle2 += "<td>Sin Auditar</td>";
                            } else {
                                tableDetalle2 += "<td>" + value.auditor + "</td>";
                            }

                            if (value.cumple == 1) {
                                tableDetalle2 += "<td>Cumple</td>";
                            } else {
                                tableDetalle2 += "<td>No Cumple</td>";
                            }

                            if (value.calificacion == 1) {
                                tableDetalle2 += "<td>Cumple</td>";
                            } else {
                                tableDetalle2 += "<td>No Cumple</td>";
                            }
                            if (value.observaciones == null) {
                                tableDetalle2 += "<td>Sin Observaciones</td>";
                            } else {
                                tableDetalle2 += "<td>" + value.observaciones + "</td>";
                            }
                            if (id_diagnostico == 171 || id_diagnostico == 170 || id_diagnostico ==
                                169 ||
                                id_diagnostico == 168 || id_diagnostico == 167 || id_diagnostico ==
                                166 ||
                                id_diagnostico == 165 || id_diagnostico == 164 || id_diagnostico == 163
                            ) {
                                tableDetalle2 +=
                                    `<td><a href="/${nombreDocumento}/${value.id}/${id_empresa}/${id_diagnostico}" class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white" title="Ver registro documento"><i class="fa fa-eye"></i></a> <button class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white" onclick="qualifyItemm('${value.id}','${keyint.itemDiad}','${keyint.variable}','${cml}','${value.id_empresa}','${id_diagnostico}','${general.id_para}','${efe}')" title="Calificar el Item">Calificar</button> <button class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white" onclick="vehistorico2(${value.id})" title="Ver historico calificación">Ver</button> <a href="/${pdfUrl}/${value.id}/${id_empresa}/${id_diagnostico}" title="Descarga de documento en PDF" target="_blank" style="color: #FFFFFE"><img class="focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white" src="/assets/img/logopdf.png" target="_blank" style="color: #FFFFFE"></a></td>`;
                            } else {
                                tableDetalle2 += `<td><a href="/${nombreDocumento}/${value.id}/${id_empresa}" class="btn btn-info" title='Ver registro documento'><i class='fa fa-eye'></i></a> <button
                            class="focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white" onclick="qualifyItemm('${value.id}','${keyint.itemDiad}','${keyint.variable}','${cml}','${value.id_company}','${id_diagnostico}','${general.id_para}','${efe}')" title='Calificar el Item'>Calificar </button> <button class='focus:outline-none bg-corporativoT focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 text-white hover:text-white'
                            onclick='vehistorico2( ${value.id})' title='Ver historico calificación'>Ver</button>
                         <a href='/${pdfUrl}/${value.id}/${id_empresa}'title='descarga de documento en PDF' target='_blank' style='color: #FFFFFE'><img
                                src='/assets/img/logopdf.png' target='_blank' style='color: #FFFFFE'></a> </td>`;
                            }

                            tableDetalle2 += "</tr>";
                        });
                    }
                    if (id_diagnostico == 5) {
                        $('#tablaaniosPESV22').append(tableDetalle2);
                    } else {
                        $('#tablaaniosPESV').append(tableDetalle2);
                    }


                },
                error: function(error) {

                }

            });

            $.get(action, function(response) {

            })


        }

        function qualifyItemm(id, numeral, marcoLegal, resultado, id_company, diagnostico, para, id_file) {

            $('#contenidoTitlequalif').empty();
            let valor = 2;
            // Se pone el titulo del modal
            let titleQualif = "<h4>Calificación del Item " + numeral + "</h4>";
            $('#contenidoTitlequalif').append(titleQualif);

            // Se pone texto en el modal con respecto al item seleccionado
            $('#contentItem').empty();
            let contentItem = "<span>Calificar el Item " + numeral + " " + marcoLegal + "</span>";
            $('#contentItem').append(contentItem);

            // Se pasan parametros a los inputs del formulario
            $('#id_item').val(id);
            // Se pone como valor 0 por defecto
            $('#valor_item').val(2);

            $('#id_companyItem').val(id_company);
            $('#diagnostico').val(diagnostico);
            $('#numeral_item').val(numeral);
            $('#resultado').val(resultado);
            $('#para').val(para);
            $('#idfile').val(id_file);

            // Se limpian los datos a mostrar
            let atrCheck = $('#togBtn').attr('checked');
            if (atrCheck != undefined) {
                $('#togBtn').removeAttr('checked');
            }

            $('#descItem').empty();

            // Matriz para la calificación de items
            items = [
                '@foreach ($verupdocs as $updoc)',
                ['{{ $updoc->resultado }}', '{{ $updoc->id_diagnostico }}', '{{ $updoc->id_para }}'],
                '@endforeach',
            ];

            item = items.filter(Boolean);
            let descItem = '';
            let inpItemCal = '';
            // Se valida la calificación del item
            if (resultado == 1) { //En caso de que el item ya este calificado



                // Elimina y crea el input para darle atributo
                $('#inpItemCal').empty();
                inpItemCal = "<label class='switch'>";
                inpItemCal +=
                    "<input type='checkbox' id='togBtn' name='togBtn' checked='checked' onchange='calificarItem()'>";
                inpItemCal += "<div class='slider round'>";
                inpItemCal += "<span class='on'>Sí</span>";
                inpItemCal += "<span class='off'>No</span>";
                inpItemCal += "</div>";
                inpItemCal += "</label>";
                $('#inpItemCal').append(inpItemCal);
                // Se pone texto despues del checkbox


            } else { //En caso de que el item no este calificado
                // Pone 0 al in put de valor
                $('#valor_item').val(2);
            }

            // Se muestra el modal
            $('#qualificationItem').modal();

            // Se asigna valor a la variable global de calificación de item
            valor_item = valor;

        }

        function vehistorico(id) {

            $('#valorarhivo').val(id);

            //En caso de que el item ya este calificado
            items = [
                '@foreach ($calificacion as $cali)',
                ['{{ $cali->id_archivo }}', '{{ $cali->cumple }}', '{{ $cali->auditor }}',
                    '{{ $cali->observaciones }}',
                    '{{ $cali->fecha }}'
                ],
                '@endforeach',
            ];

            valid = items.filter(Boolean);
            divverhisto = "";
            $('#divverhisto').empty();
            divverhisto +=
                "<table id='' class='display table-condensed table-striped table-bordered' width='100%' border='0' cellspacing='0' cellpadding='0'>";
            divverhisto += "<thead>";
            divverhisto += "<tr> ";
            divverhisto += "<th>Resultado</th>     ";
            divverhisto += "<th>Fecha Auditoria</th>     ";
            divverhisto += "<th>Auditor</th>     ";
            divverhisto += "<th>Observaciones</th> ";
            divverhisto += "</tr>";
            divverhisto += "</thead>";
            divverhisto += "<tbody>";
            for (let i = 0; i < valid.length; i++) {
                if (valid[i][0] == id) {
                    divverhisto += "<tr>";
                    if (valid[i][1] == 1) {
                        divverhisto += "<td>Cumple</td>";
                    } else {
                        divverhisto += "<td>No Cumple</td>";
                    }
                    divverhisto += "<td>" + valid[i][4] + "</td>";
                    divverhisto += "<td>" + valid[i][2] + "</td>";
                    if (valid[i][3] == null || valid[i][3] == '') {
                        divverhisto += "<td>Sin Observaciones</td>";
                    } else {
                        divverhisto += "<td>" + valid[i][3] + "</td>";
                    }
                    divverhisto += "</tr>";
                } else {


                }
            }
            divverhisto += "</tbody>";
            divverhisto += "</table>";
            $('#divverhisto').append(divverhisto);

            $('#mymodal').modal();
        }

        function vehistorico2(id) {
            //alert(id);
            $('#valorarhivo').val(id);

            //En caso de que el item ya este calificado
            items = [
                '@foreach ($calificacion2 as $cali)',
                ['{{ $cali->id_registro }}', '{{ $cali->cumple }}', '{{ $cali->auditor }}',
                    '{{ $cali->observaciones }}',
                    '{{ $cali->fecha }}'
                ],
                '@endforeach',
            ];

            valid = items.filter(Boolean);
            divverhisto = "";
            $('#divverhisto').empty();
            divverhisto +=
                "<table id='' class='display table-condensed table-striped table-bordered' width='100%' border='0' cellspacing='0' cellpadding='0'>";
            divverhisto += "<thead>";
            divverhisto += "<tr> ";
            divverhisto += "<th>Resultado</th>     ";
            divverhisto += "<th>Fecha Auditoria</th>     ";
            divverhisto += "<th>Auditor</th>     ";
            divverhisto += "<th>Observaciones</th> ";
            divverhisto += "</tr>";
            divverhisto += "</thead>";
            divverhisto += "<tbody>";
            for (let i = 0; i < valid.length; i++) {
                if (valid[i][0] == id) {
                    divverhisto += "<tr>";
                    if (valid[i][1] == 1) {
                        divverhisto += "<td>Cumple</td>";
                    } else {
                        divverhisto += "<td>No Cumple</td>";
                    }
                    divverhisto += "<td>" + valid[i][4] + " </td>";
                    divverhisto += "<td>" + valid[i][2] + " </td>";
                    if (valid[i][3] == null || valid[i][3] == '') {
                        divverhisto += "<td>Sin Observaciones</td>";
                    } else {
                        divverhisto += "<td>" + valid[i][3] + "</td>";
                    }
                    divverhisto += "</tr>";
                } else {


                }
            }
            divverhisto += "</tbody>";
            divverhisto += "</table>";
            $('#divverhisto').append(divverhisto);

            $('#mymodal').modal();
        }

        function calificarItem() {
            seleccionItem = $('#togBtn').is(':checked');

            if (seleccionItem) {
                // Cuando sea positiva la respuesta se asigna al input de valor el valor del item

                $('#valor_item').val(1);
            } else {
                // Cuando sea negativa la respuesta se asigna al input de valor 0

                $('#valor_item').val(2);
            }
        }

        $(document).ready(function() {
            $('#data_tabla1').DataTable({

                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                }

            });
        });
    </script>
@endsection
