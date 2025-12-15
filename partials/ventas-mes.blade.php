
<div>
    <div class="d-flex justify-content-center align-items-center gap-3 mb-4 w-100">

        <div class="flex-1 min-w-150px">
            <select id="yearSelect" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>

        <div class="flex-1 min-w-150px mt-2">
            <select id="monthSelect" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>

    </div>
    <div>
        <hr style="border: none; margin: 5px 0;">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div id="containerGraph1" style="width: 100%; min-height: 450px;"></div>
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
    const monthNames = [
        "Enero","Febrero","Marzo","Abril","Mayo","Junio",
        "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
    ];

    const today = new Date();
    let selectedYear = today.getFullYear();
    let selectedMonth = monthNames[today.getMonth()];
    function loadDropdowns() {
        $.when(
            $.get('/api/ventas/years'),
            $.get('/api/ventas/months')
        ).done((yearsRes, monthsRes) => {
            
            let years = yearsRes[0];
            let months = monthsRes[0];

            $('#yearSelect').html(
                years.map(y => `<option value="${y}">${y}</option>`).join('')
            ).val(selectedYear);

            $('#monthSelect').html(
                months.map(m => `<option value="${m}">${m}</option>`).join('')
            ).val(selectedMonth);


        });
    }




    $("#yearSelect").on("change", function () {
        selectedYear = $(this).val();
        ventas_mes();
    });

    $("#monthSelect").on("change", function () {
        selectedMonth = $(this).val();
        ventas_mes();

    });

    // INITIAL LOAD
    loadDropdowns();
    function ventas_mes(){
        $.ajax({
        url: `/api/ventas/ventas/mes?year=${selectedYear}&month=${selectedMonth}`,
        method: 'GET',
        dataType: 'json',
        success: function(chartData) {
            const servicioColors={ "Anualidad": "#f1d0d5",  
                                    "Otros productos": "#cce5ec",  
                                    "Recurrencia": "#f9eee6", 
                                    "Anualidad RTE": "#d9d9d9",
                                    "SG SST Presencial": "#a3eaed",
                                    "": "#eed2fd"}
            const categories = [...new Set(chartData.map(item => item.VENDEDOR))];
            const serviciosMap = {};

            chartData.forEach(item => {
                if (!serviciosMap[item.SERVICIO]) {
                    serviciosMap[item.SERVICIO] = {
                        name: item.SERVICIO,
                        color: servicioColors[item.SERVICIO] || "#999999",
                        data: Array(categories.length).fill(0)
                    };
                }

                const idx = categories.indexOf(item.VENDEDOR);
                serviciosMap[item.SERVICIO].data[idx] = item.VENTA;
            });

            const series = Object.values(serviciosMap);

            Highcharts.chart('containerGraph1', {
            chart: {
                type: 'column'
            },
            width: 150,
            height: 150,
            title: {
                text: 'Ventas por producto - ' + selectedMonth + ' ' + selectedYear
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
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        format: '{y:.1f}',
                    }
                }
            },
            tooltip: {
                shared: true, 
                formatter: function () {
                    let total = 0;

                    this.points.forEach(p => {
                        total += p.y;
                    });

                    let html = `<b>${this.x}</b><br/>`;

                    this.points.forEach(p => {
                        html += `
                            <span style="color:${p.color}">\u25CF</span>
                            ${p.series.name}: <b>${p.y.toLocaleString()}</b><br/>
                        `;
                    });

                    html += `<br><b>Total: ${total.toLocaleString()}</b>`;

                    return html;
                }
            },
            series: series
        });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#containerGraph1').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    $(document).ready(function() {
        $("#load").hide();
    
        ventas_mes();
        
        

    });
</script>