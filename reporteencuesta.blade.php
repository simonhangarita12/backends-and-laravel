@extends('layouts.dashboard')


    
@section('section')

<div>
    <a href="/ListForm/{{ $id_empresa }}/29/7" type="button" class="btn btn-default" id="dashboardBio">Regresar</a>
     
    <div class="container-fluid mt-5">
        <button id="exportButton" class="btn btn-primary" 
                style="background-color: #574998; color: white;"
                data-toggle="modal" 
                data-target="#modalBoton">
            Exportar reporte
        </button>
        <hr style="border: none; margin: 5px 0;">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div id="containerGraph" style="width: 100%; min-height: 450px;"></div>
            </div>
        </div>
        <hr style="border: none; margin: 20px 0;">
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="containerGraph2" style="width: 100%; min-height: 450px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="containerGraph3" style="width: 100%; min-height: 450px;"></div>
            </div>
        </div>

        <hr style="border: none; margin: 20px 0;">
        
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="containerGraph4" style="width: 100%; min-height: 450px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="containerGraph5" style="width: 100%; min-height: 450px;"></div>
            </div>
        </div>
        <hr style="border: none; margin: 20px 0;">
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="containerGraph6" style="width: 100%; min-height: 450px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="containerGraph7" style="width: 100%; min-height: 450px;"></div>
            </div>
        </div>
        <hr style="border: none; margin: 20px 0;">
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="containerGraph8" style="width: 100%; min-height: 450px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="containerGraph9" style="width: 100%; min-height: 450px;"></div>
            </div>
        </div>
        <hr style="border: none; margin: 20px 0;">
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="containerGraph10" style="width: 100%; min-height: 450px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="containerGraph11" style="width: 100%; min-height: 450px;"></div>
            </div>
        </div>
        <hr style="border: none; margin: 20px 0;">
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="containerGraph12" style="width: 100%; min-height: 450px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="containerGraph13" style="width: 100%; min-height: 450px;"></div>
            </div>
        </div>
        <hr style="border: none; margin: 20px 0;">
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="containerGraph14" style="width: 100%; min-height: 450px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="containerGraph15" style="width: 100%; min-height: 450px;"></div>
            </div>
        </div>
        <hr style="border: none; margin: 20px 0;">
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="containerGraph16" style="width: 100%; min-height: 450px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="containerGraph17" style="width: 100%; min-height: 450px;"></div>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    let data_excel = [];
    // CSRF token made available to JS (used for AJAX requests)
    const CSRF_TOKEN = "{{ csrf_token() }}";
    function loadExcel(id_empresa) {
        $.get('/api/pesv/informe/excel', function (planes) {

            console.log("Planes:", planes);

            data_excel = planes;
        });
    }
    $("#exportButton").on("click", function () {
        console.log("Generando reporte...");

        // Debug: token and payload
        console.log('CSRF_TOKEN:', CSRF_TOKEN);
        console.log('data_excel (preview):', data_excel && data_excel.length ? data_excel.slice(0,3) : data_excel);

        if (!data_excel || (Array.isArray(data_excel) && data_excel.length === 0)) {
            alert('No hay datos para exportar todavía. Intenta cargar los datos primero.');
            return;
        }

        $.ajax({
            url: "/exportMulti",
            type: "POST",
            // send proper JSON string
            data: JSON.stringify(data_excel),
            contentType: "application/json; charset=utf-8",
            // let jQuery process the data (default) so that JSON is sent correctly
            headers: {
                // Laravel expects X-CSRF-TOKEN header for AJAX
                "X-CSRF-TOKEN": CSRF_TOKEN
            },
            // For file download responses we want a blob; withCredentials will send cookies (session) too
            xhrFields: {
                responseType: 'blob',
                withCredentials: true
            },
            success: function (data, status, xhr) {
                // If server returned an error JSON inside a blob, try to decode and show
                try {
                    let contentType = xhr.getResponseHeader('Content-Type') || '';
                    if (contentType.indexOf('application/json') !== -1) {
                        // blob contains JSON (probably an error) -> read and show
                        let reader = new FileReader();
                        reader.onload = function() {
                            try {
                                let txt = reader.result;
                                let json = JSON.parse(txt);
                                console.error('Server JSON response (error):', json);
                                alert('Error del servidor: ' + (json.message || JSON.stringify(json))); 
                            } catch(e) {
                                console.error('Could not parse JSON blob:', e);
                            }
                        };
                        reader.readAsText(data);
                        return;
                    }

                    let blob = new Blob([data], { type: contentType });
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Reporte_Multisheet.xls";
                    document.body.appendChild(link);
                    link.click();
                    setTimeout(function(){
                        window.URL.revokeObjectURL(link.href);
                        document.body.removeChild(link);
                    }, 100);
                } catch (e) {
                    console.error('Error processing download:', e);
                }
            },
            error: function (xhr, status, error) {
                console.error("Export error:", error, xhr);
                // Try to extract JSON error if returned as blob
                try {
                    if (xhr && xhr.response) {
                        let reader = new FileReader();
                        reader.onload = function() {
                            try { console.error('Server response:', JSON.parse(reader.result)); } catch(e){ console.error('Non-JSON response'); }
                        };
                        reader.readAsText(xhr.response);
                    }
                } catch(e) { /* ignore */ }
            }
        });
    });
    
    function comparativo(id_empresa){
        $.ajax({
                url: `/api/pesv/comparativo?id_empresa=${id_empresa}`,
                method: 'GET',
                dataType: 'json',
                success: function(chartData) {
                    Highcharts.chart('containerGraph', {
                        chart: {
                            type: 'areaspline'
                        },
                        title: {
                            text: 'Porcentaje de infracciones y siniestros por rango de edad'
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'left',
                            verticalAlign: 'top',
                            x: 120,
                            y: 70,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: 'var(--highcharts-background-color, #ffffff)'
                        },
                        xAxis: {
                            categories: chartData.categories,
                            title: {
                                text: 'Rango de edad'
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Porcentaje'
                            }
                        },
                        tooltip: {
                            shared: true,
                            headerFormat: '<b>Rango de edad {point.x}</b><br>'
                        },
                        credits: {
                            enabled: false
                        },
                        plotOptions: {
                            areaspline: {
                                fillOpacity: 0.5
                            }
                        },
                        series: chartData.data
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#containerGraph').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
                }
            });
    }
    function tipo_documento(id_empresa){
        $.ajax({
            url: `/api/pesv/tipo/documento?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph2', {
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
                    title: {
                        text: 'Distribución de los tipos de documentos'
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph2').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
            });
    }
    function genero(id_empresa){
        $.ajax({
            url:  `/api/pesv/genero?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph3', {
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
                    title: {
                        text: 'Distribución por género'
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph3').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function edad(id_empresa){
        $.ajax({
            url: `/api/pesv/edad?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(chartData) {
                Highcharts.chart('containerGraph4', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Distribucion de edad'
                },
                xAxis: {
                    categories: chartData.categories,
                    crosshair: true,
                    accessibility: {
                        description: 'Rangos de edad'
                    },
                    title: {
                        text: 'Edad'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Cantidad de personas'
                    }
                },
                plotOptions: {
                    column: {
                        colorByPoint: true,
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Personas',
                    data: chartData.data 
                }]
            });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph4').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function escolaridad(id_empresa){
        $.ajax({
            url: `/api/pesv/escolaridad?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph5', {
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
                    title: {
                        text: 'Nivel de escolaridad (el más alto alcanzado)'
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph5').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function estado_civil(id_empresa){
        $.ajax({
            url: `/api/pesv/estado/civil?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph6', {
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
                    title: {
                        text: 'Estado civil'
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph6').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function licencia_conducir(id_empresa){
        $.ajax({
            url: `/api/pesv/categoria/licencia/auto?id_empresa=${id_empresa}`,
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph7', {
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
                    title: {
                        text: 'Categoria de la licencia de conducción (vehículo-carro)'
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph7').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function licencia_moto(id_empresa){
        $.ajax({
            url: `/api/pesv/categoria/licencia/moto?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph8', {
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
                    title: {
                        text: 'Categoria de la licencia de conducción (moto)'
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph8').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function capacitacion(id_empresa){
        $.ajax({
            url: `/api/pesv/capacitacion?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph9', {
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
                    title: {
                        text: '¿Ha recibido capacitación en seguridad vial?'
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph9').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function siniestros(id_empresa){
        $.ajax({
            url: `/api/pesv/siniestros?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph10', {
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
                    title: {
                        text: '¿Ha sufrido siniestros víales en los últimos años?'
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph10').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function rol_siniestros(id_empresa){
        $.ajax({
            url: `/api/pesv/rol/siniestros?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph11', {
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
                    title: {
                        text: 'Rol como actor vial en el accidente'
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph11').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function cantidadInfracciones(id_empresa){
        $.ajax({
            url: `/api/pesv/cantidad/infracciones?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(chartData) {
                Highcharts.chart('containerGraph12', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '¿Cuántas infracciones de tránsito ha cometido?'
                },
                xAxis: {
                    categories: chartData.categories,
                    crosshair: true,
                    accessibility: {
                        description: 'Número de infracciones cometidas'
                    },
                    title: {
                        text: 'Numero de infracciones'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Cantidad de personas'
                    }
                },
                plotOptions: {
                    column: {
                        colorByPoint: true,
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Personas',
                    data: chartData.data 
                }]
            });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph12').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function tipo_infracciones(id_empresa){
        $.ajax({
            url: `/api/pesv/tipo/infracciones?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph13', {
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
                    title: {
                        text: 'Tipos de infracciones'
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph13').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function estado_pago(id_empresa){
        $.ajax({
            url: `/api/pesv/estado/pago?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph14', {
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
                    title: {
                        text: 'Estado de pago de las infracciones'
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph14').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function medio_transporte(id_empresa){
        $.ajax({
            url: `/api/pesv/medio/transporte?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph15', {
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
                    title: {
                        text: 'Medio de transporte que utiliza con frecuencia para el desplazamiento (casa-trabajo-casa)'
                    },
                    tooltip: {
                        formatter: function() {
                            return `<b>${this.point.name}</b><br/>
                                    Percentage: <b>${this.point.y.toFixed(2)}%</b><br/>
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph15').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function conductor_laboral(id_empresa){
        $.ajax({
            url: `/api/pesv/conductor/laboral?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph16', {
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
                    title: {
                        text: '¿Cumple rol como conductor laboral para desplazamientos laborales?'
                    },
                    tooltip: {
                        formatter: function() {
                            return `<b>${this.point.name}</b><br/>
                                    Percentage: <b>${this.point.y.toFixed(2)}%</b><br/>
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph16').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    function tipo_vehiculo(id_empresa){
        $.ajax({
            url: `/api/pesv/tipo/vehiculo?id_empresa=${id_empresa}`,
            method: 'GET',
            dataType: 'json',
            success: function(seriesData) {
                Highcharts.chart('containerGraph17', {
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
                    title: {
                        text: 'Tipo de vehículo automotor o no motor que conduce más frecuente (laboralmente)'
                    },
                    tooltip: {
                        formatter: function() {
                            return `<b>${this.point.name}</b><br/>
                                    Percentage: <b>${this.point.y.toFixed(2)}%</b><br/>
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
                    series: [seriesData]
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#containerGraph17').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    $(document).ready(function() {
        $("#load").hide();
    
        // Load charts with default company ID or get from somewhere
        //const id_empresa = {{ $id_empresa ?? 1 }}; // You can modify this to get the actual company ID

        const id_empresa = {{ $id_empresa ?? 170 }};
        loadExcel(id_empresa);
        comparativo(id_empresa);
        tipo_documento(id_empresa);
        genero(id_empresa);
        edad(id_empresa);
        escolaridad(id_empresa);
        estado_civil(id_empresa);
        licencia_conducir(id_empresa);
        licencia_moto(id_empresa);
        capacitacion(id_empresa);
        siniestros(id_empresa);
        rol_siniestros(id_empresa);
        cantidadInfracciones(id_empresa);
        tipo_infracciones(id_empresa);
        estado_pago(id_empresa);
        medio_transporte(id_empresa);
        conductor_laboral(id_empresa);
        tipo_vehiculo(id_empresa);
        

    });
</script>



@endsection
