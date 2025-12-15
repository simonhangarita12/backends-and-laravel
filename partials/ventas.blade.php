
<div>
    <div>
        <hr style="border: none; margin: 5px 0;">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div id="containerGraph" style="width: 100%; min-height: 450px;"></div>
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
    function ventas(){
        $.ajax({
                url: `/api/ventas/grupo/ventas`,
                method: 'GET',
                dataType: 'json',
                success: function(chartData) {
                    const categories = chartData.map(item => {
                        const date = new Date(item.MES);
                        return date.toLocaleString('es-ES', { month: 'long', year: 'numeric' });
                    });

                    const data = chartData.map(item => item.VENTA);
                    console.log(categories);
                    console.log(data);
                    Highcharts.chart('containerGraph', {
                        chart: {
                            type: 'areaspline'
                        },
                        title: {
                            text: 'Ventas totales por mes'
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
                            categories: categories,
                            title: {
                                text: 'Meses'
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Total Ventas'
                            }
                        },
                        tooltip: {
                            shared: true,
                            headerFormat: '<b>Mes: {point.x}</b><br>'
                        },
                        credits: {
                            enabled: false
                        },
                        plotOptions: {
                            areaspline: {
                                fillOpacity: 0.5
                            }
                        },
                        series: [{
                            name: "Ventas",
                            data: data
                        }]
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#containerGraph').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
                }
            });
    }
    $(document).ready(function() {
        $("#load").hide();
    
        ventas();
        
        

    });
</script>