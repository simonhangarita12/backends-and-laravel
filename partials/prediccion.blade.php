<div>
    <div class="d-flex justify-content-center align-items-center gap-3 mb-4 w-100">

        <div class="flex-1 min-w-150px">
            <select id="productSelect" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>
    </div>
    <div class="row">
            <div class="col-lg-12 text-center">
                <div id="distributionContainer" style="width: 100%; min-height: 450px;"></div>
            </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    let selectedPlan = 'TOTAL';
    function loadDropdownsPred() {
        $.get('/api/ventas/planes/total', function (planes) {

            console.log("Planes:", planes);

            if (!Array.isArray(planes) || planes.length === 0) return;

            $('#productSelect')
                .html(planes.map(y => `<option value="${y}">${y}</option>`).join(''))
                .val(selectedPlan);

            distribucion_normal();
        });
    }
    $("#productSelect").on("change", function () {
        selectedPlan = $(this).val();
        distribucion_normal();
    });

    function distribucion_normal(){
        $.ajax({
                url: `/api/ventas/estadisticas?&plan=${selectedPlan}`,
                method: 'GET',
                dataType: 'json',
                success: function(chartData) {
                    const mean = chartData.mean;
                    const stdDev = chartData.std;
                    const numPoints = 500;

                    const minX = mean - 4 * stdDev;
                    const maxX = mean + 4 * stdDev;

                    const zScore = 1.2816; 
                    const lowerBound = mean - zScore * stdDev;
                    const upperBound = mean + zScore * stdDev;

                    const xValues = [];
                    const yValues = [];
                    const yFill = [];

                    for (let i = 0; i < numPoints; i++) {
                    const x = minX + (maxX - minX) * i / (numPoints - 1);
                    const y = (1 / (stdDev * Math.sqrt(2 * Math.PI))) *
                                Math.exp(-0.5 * Math.pow((x - mean) / stdDev, 2));
                    
                    xValues.push(x);
                    yValues.push(y);

                    yFill.push(x >= lowerBound && x <= upperBound ? y : null);
                    }

                    Highcharts.chart('distributionContainer', {
                    title: {
                        text: 'Distribución Normal de Ventas para el Plan ' + selectedPlan
                    },

                    xAxis: {
                        title: { text: 'Valor de ventas' },
                        min: minX,
                        max: maxX
                    },

                    yAxis: {
                        title: { text: 'Probabilidad' }
                    },

                    tooltip: {
                        shared: true,
                        formatter: function () {

           
                            const formatSales = num =>
                                Highcharts.numberFormat(num, 3, ',', '.');  

   
                            const formatProb = num =>{
                                const nano = num * 1e9;
                                return Highcharts.numberFormat(nano, 3, ',', '.') + ' n';
                            };
                            let s = `<b>Valor: ${formatSales(this.x)}</b><br/>`;

                            this.points.forEach(p => {
                                s += `<span style="color:${p.color}">●</span> Probabilidad: <b>${formatProb(p.y)}</b><br/>`;
                            });

                            return s;
                        }
                    },

                    series: [
                        {
                        type: 'line',
                        name: 'Distribución normal',
                        data: xValues.map((x, i) => [x, yValues[i]]),
                        color: '#4688DA',
                        lineWidth: 3
                        },
                        {
                        type: 'area',
                        name: '80% más probable',
                        data: xValues.map((x, i) => [x, yFill[i]]),
                        color: 'rgba(75, 141, 221, 0.4)',
                        fillOpacity: 0.5,
                        lineWidth: 0,
                        enableMouseTracking: false
                        }
                    ]
                    });

                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#distributionContainer').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
                }
            });
    }
    $(document).ready(function() {
        $("#load").hide();
        loadDropdownsPred();
    });
</script>