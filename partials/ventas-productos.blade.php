<div>
    <div class="d-flex justify-content-center align-items-center gap-3 mb-4 w-100">

        <div class="flex-1 min-w-150px">
            <select id="yearSelect5" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>

    
    <hr style="border: none; margin: 20px 0;">
        
        <div class="row">
            <div class="col-lg-12 text-center">
                <div id="productoContainer" style="width: 100%; height: 600px;"></div>
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
    
    const today5 = new Date();
    let selectedYear5 = today5.getFullYear();
    function loadDropdowns5() {
        $.get('/api/ventas/years',function(years){
            $('#yearSelect5').html(
                years.map(y => `<option value="${y}">${y}</option>`).join('')
            ).val(selectedYear5);
        });
    }
    


    $("#yearSelect5").on("change", function () {
        selectedYear5 = $(this).val();
        ventas_productos();


    });

   

    
    function ventas_productos(){
        $.ajax({
        url: `/api/ventas/ventas/productos?year=${selectedYear5}`,
        method: 'GET',
        dataType: 'json',
        success: function(chartData) {
            console.log("Ventas productos data:", chartData);
            const series = [{
                name: "Ventas",
                data: chartData.map(item => item.VENTA)
            }];
            const categories = chartData.map(item => item["PRODUCTO COMPLETO"]);
            Highcharts.chart('productoContainer', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Ventas por producto en el año ' + selectedYear5
            },
            xAxis: {
                categories: categories,
                crosshair: true,
                accessibility: {
                    description: 'Ventas por producto'
                },
                title: {
                    text: 'Vendedor'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Ventas en $'
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
            series: series
        });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#productoContainer').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
        });
    
    }
   
    
    $(document).ready(function() {
        $("#load").hide();
        loadDropdowns5();
        ventas_productos();



    });
</script>