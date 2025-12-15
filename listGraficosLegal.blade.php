@extends('layouts.dashboard')
@section('page_heading', 'Calificación de Matriz Legal - SGSST')

<head>
    <style>
        #load {
            display: flex;
            justify-content: center;
            align-items: center;
            align-content: center;
            height: 60vh;
        }
    </style>
</head>

@section('section')
    <div class="row">
        <div class="col-lg-12">
            @php
                $user = Sentinel::getUser();
                $ciaAgrupadora = Crypt::encrypt($user->id_ciaAncla);
            @endphp
            <a href="{{ url('/contratistaAncla/' . $ciaAgrupadora) }}" class="btn btn-default">Regresar</a>
            <div class="text-center" style="margin: 30px 0; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <h1 style="color: white; font-size: 28px; font-weight: bold; margin: 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                    MATRIZ LEGAL DE LA EMPRESA: {{ strtoupper($company->razonsocial ?? 'EMPRESA') }}
                </h1>
            </div>
        </div>
        <br><br><br>
        <div class="" id="load">
            @include('layouts.spinner')
        </div>

        <!-- Charts Section -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 text-center">
                    <div id="containerGraf3" style="width: 100%; min-height: 450px;"></div>
                </div>
                <div class="col-lg-6 text-center">
                    <div id="containerGraf4" style="width: 100%; min-height: 450px;"></div>
                </div>
            </div>

            <hr style="border: none; margin: 20px 0;">
            
            <div class="row">
                <div class="col-lg-6 text-center">
                    <div id="containerGraf" style="width: 100%; min-height: 450px;"></div>
                </div>
                <div class="col-lg-6 text-center">
                    <div id="containerGraf2" style="width: 100%; min-height: 450px;"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <!-- Título principal con nombre de la empresa -->
            
            
            <table class="table table-striped table-bordered table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-left" style="width: 40%;">Norma</th>
                        <th class="text-center" style="width: 20%;">Tipo de Peligro</th>
                        <th class="text-center" style="width: 20%;">Calificación</th>
                        <th class="text-center" style="width: 20%;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($normas as $norma)
                        <!-- Norma Principal -->
                        <tr class="norma-row" style="cursor: pointer;" data-norma-id="{{ $norma['id'] }}">
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <span class="chevron-icon" style="margin-right: 10px; transition: transform 0.3s;">▶</span>
                                    <strong>{{ $norma['nombre'] }}</strong>
                                </div>
                            </td>
                            <td class="text-center">
                                
                                {{ $norma['peligro'] ?? '' }}
                            </td>
                            <td class="text-center">
                                @php
                                    $puntaje = $norma['puntaje'];
                                    $colorClass = '';
                                    switch($puntaje) {
                                        case 5: $colorClass = 'label-success'; break;
                                        case 4: $colorClass = 'label-warning'; break;
                                        case 3: $colorClass = 'label-warning'; break;
                                        case 2: $colorClass = 'label-danger'; break;
                                        case 1: $colorClass = 'label-danger'; break;
                                        case 0: $colorClass = 'label-danger'; break;
                                        default: $colorClass = 'label-default';
                                    }
                                @endphp
                                <span class="label {{ $colorClass }}">{{ $puntaje }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('MedPreventivaForm', ['id' => $norma['id'], 'id_empresa' => $id_empresa, 2]) }}" 
                                   class="btn btn-primary" 
                                   title="Ver Resolución y Criterios de Cumplimiento">Evaluación</a>
                            </td>
                        </tr>
                        
                        <!-- Criterios (inicialmente ocultos) -->
                        @foreach($norma['criterios'] as $criterio)
                            <tr class="criterios-{{ $norma['id'] }}" style="display: none; background-color: #f9f9f9;">
                                <td style="padding-left: 40px;" colspan="2">
                                    {{ $criterio['nombre'] }}
                                </td>                                
                                <td class="text-center" colspan="2">
                                    @if($criterio['puntaje'] > 0)
                                        <span style="color: #5cb85c; font-weight: bold;">Cumple</span>
                                    @else
                                        <span style="color: #d9534f; font-weight: bold;">No cumple</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>

<script>
function cumplimiento_segmentado(){
        $.ajax({
        url: `/api/holcim/puntaje/segmentacion`,
        method: 'GET',
        dataType: 'json',
        success: function(chartData) {
            

        
            Highcharts.chart('containerGraf', {
            chart: {
                type: 'column'
            },
            width: 150,
            height: 150,
            title: {
                text: 'Porcentaje de normas cumplidas total y parcialmente'
            },
            xAxis: {
                categories: chartData.categories,
                crosshair: true,
                accessibility: {
                    description: 'Porcentaje de normas cumplidas total y parcialmente'
                },
                title: {
                    text: 'Cumplimiento'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Porcentaje'
                }
            },
            plotOptions: {
                column: {

                    dataLabels: {
                        enabled: true,
                        format: '{y:.1f}',
                    }
                }
            },
            tooltip: {
                    headerFormat: '',
                    pointFormatter: function() {
                        return `
                            ${this.series.name}: <b>${this.y}</b>%<br/>
                            Total normas: <b>${this.total_normas}</b><br/>
                            Numero de normas en este cumplimiento: <b>${this.normas_cumplidas}</b>
                        `;
                    }
                },
            series: chartData.series
        });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#containerGraf').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
}

function cargarIdEmpresasegundo(id_empresa) {
    $.ajax({
        url: `/api/holcim/puntaje/total?id_empresa=${id_empresa}`,
        method: 'GET',
                dataType: 'json',
                success: function(chartData) {
                    Highcharts.chart('containerGraf2', {

                        chart: {
                            type: 'gauge',
                            plotBackgroundColor: null,
                            plotBackgroundImage: null,
                            plotBorderWidth: 0,
                            plotShadow: false,
                            height: '50%'
                        },
                        width: 150,
                        height: 150,

                        title: {
                            text: 'Medidor de avance en cumplimiento de requisitos legales'
                        },

                        pane: {
                            startAngle: -90,
                            endAngle: 89.9,
                            background: null,
                            center: ['50%', '75%'],
                            size: '110%'
                        },


                        yAxis: {
                            min: 0,
                            max: 100,
                            tickPixelInterval: 72,
                            tickPosition: 'inside',
                            tickColor: 'var(--highcharts-background-color, #FFFFFF)',
                            tickLength: 20,
                            tickWidth: 2,
                            minorTickInterval: null,
                            labels: {
                                distance: 20,
                                style: {
                                    fontSize: '14px'
                                }
                            },
                            lineWidth: 0,
                            plotBands: [{
                                from: 0,
                                to: 50,
                                color: '#ff6767',
                                thickness: 20,
                                borderRadius: '50%'
                            }, {
                                from: 50,
                                to: 80,
                                color: '#DDDF0D',
                                thickness: 20,
                                borderRadius: '50%'
                            }, {
                                from: 80,
                                to: 100,
                                color: '#4fbe88',
                                thickness: 20,
                                borderRadius: '50%'
                            }]
                        },
                        tooltip: {
                            pointFormatter: function() {
                                return `
                                    <b>${this.company_name}</b><br/>
                                    ${this.series.name}: <b>${this.y}</b>%<br/>
                                    Total requisitos: <b>${this.total_requisitos}</b><br/>
                                    Requisitos completados: <b>${this.requisitos_completados}</b>
                                `;
                            }
                        },
                        series: [{
                            name: 'Avance',
                            data: [{
                                y: chartData.value,
                                company_name: chartData.company_name,
                                total_requisitos: chartData.total_requisitos,
                                requisitos_completados: chartData.requisitos_completados
                            }],
                            dataLabels: {
                                format: '{y} %',
                                borderWidth: 0,
                                color: (
                                    Highcharts.defaultOptions.title &&
                                    Highcharts.defaultOptions.title.style &&
                                    Highcharts.defaultOptions.title.style.color
                                ) || '#333333',
                                style: {
                                    fontSize: '16px'
                                }
                            },
                            dial: {
                                radius: '80%',
                                backgroundColor: 'gray',
                                baseWidth: 12,
                                baseLength: '0%',
                                rearLength: '0%'
                            },
                            pivot: {
                                backgroundColor: 'gray',
                                radius: 6
                            }

                        }]

                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#containerGraf2').html('<div style="color: red; padding: 20px;">Error loading data: ' +
                        error + '</div>');
                }
            });
        }

function cargarAcumuladoEmpresas() {

        $.ajax({
            url: `/api/holcim/puntaje/union`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraf3', {
                    chart: {
                        type: 'pie',
                        zooming: {
                            type: 'xy'
                        },
                        panning: {
                            enabled: true,
                            type: 'xy'
                        },
                        panKey: 'shift'
                    },
                    width: 150,
                    height: 150,
                    title: {
                        text: 'Porcentaje de normas completadas en todas las empresas'
                    },
                    tooltip: {
                        formatter: function() {
                            return `<b>${this.point.name}</b><br/>
                                    Porcentaje: <b>${this.point.y.toFixed(2)}%</b><br/>
                                    Total: <b>${this.point.options.total}</b>`;
                    }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                distance: 20,
                                format: '{point.name}: {point.percentage:.1f}%',
                                style: {
                                    fontSize: '1em'
                                }
                            },
                            showInLegend: true
                        }
                    },
                    colors: ['#4fbe88','#ff6767'],
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#containerGraf3').html('<div style="color: red; padding: 20px;">Error loading data: ' +
                        error + '</div>');
                }
        });

    }
function cargarEmpresas(){
    $.ajax({
        url: `/api/holcim/puntaje/empresa`,
        method: 'GET',
        dataType: 'json',
        success: function(chartData) {
            Highcharts.chart('containerGraf4', {
            chart: {
                type: 'column'
            },
            width: 150,
            height: 150,
            title: {
                text: 'Porcentaje de normas cumplidas por empresa'
            },
            xAxis: {
                categories: chartData.categories,
                crosshair: true,
                accessibility: {
                    description: 'Porcentaje de normas cumplidas al 100% por empresa'
                },
                title: {
                    text: 'Empresa'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Normas cumplidas (%)'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        format: '{y:.1f}%',
                    }
                }
            },tooltip: {
                formatter: function() {
                    const { total_normas, normas_cumplidas, y } = this.point;

                    const label = this.series.name === "Cumplido"
                        ? "Porcentaje de cumplimiento normativo"
                        : "Porcentaje no cumplido";

                    const normasLabel = this.series.name === "Cumplido"
                        ? "Normas cumplidas"
                        : "Normas no cumplidas";

                    return `
                        <b>${this.x}</b><br>
                        <span style="color:${this.color}">\u25CF</span> <b>${this.series.name}</b><br>
                        ${label}: <b>${y.toFixed(1)}%</b><br>
                        ${normasLabel}: <b>${normas_cumplidas}</b><br>
                        Total de normas: <b>${total_normas}</b>
                    `;
                }
            },
            series: chartData.series
        });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#containerGraf4').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
}

$(document).ready(function() {
    $("#load").hide();
    
    // Load charts with default company ID or get from somewhere
    const id_empresa = {{ $id_empresa ?? 1 }}; // You can modify this to get the actual company ID
    cumplimiento_segmentado();
    cargarIdEmpresasegundo(id_empresa);
    cargarAcumuladoEmpresas();
    cargarEmpresas();
    
    $('.norma-row').click(function() {
        const normaId = $(this).data('norma-id');
        const criterios = $(`.criterios-${normaId}`);
        const chevron = $(this).find('.chevron-icon');
        
        if (criterios.is(':visible')) {
            criterios.hide();
            chevron.text('▶');
        } else {
            criterios.show();
            chevron.text('▼');
        }
    });
    
    $(".activo").click(function() {
        $("#load").show();
        $("#content").hide();
        setTimeout(function() {
            $("#load").hide();
            $("#content").show();
            // Aquí puede agregar su lógica para descargar el archivo
        }, 75000); // Tiempo de espera para simular la descarga del archivo
    });
});
</script>

@endsection
