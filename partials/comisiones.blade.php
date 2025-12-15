<div>
    <div class="d-flex justify-content-center align-items-center gap-3 mb-4 w-100">

        <div class="flex-1 min-w-150px">
            <select id="yearSelect4" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>

        <div class="flex-1 min-w-150px mt-2">
            <select id="monthSelect4" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>
        <div class="flex-1 min-w-150px mt-2">
            <select id="asesorSelect4" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>

    
    <hr style="border: none; margin: 20px 0;">
        
        <div class="row">
            <div class="col-lg-12 text-center">
                <div id="comisionesContainer" style="width: 100%; min-height: 300px;"></div>
            </div>
        </div>
    <hr style="border: none; margin: 20px 0;">
        
        <div class="row">
            <div class="col-lg-12 text-center">
                <div id="tablaContainer" style="width: 100%; min-height: 300px;"></div>
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
    const monthNames4 = [
        "Enero","Febrero","Marzo","Abril","Mayo","Junio",
        "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
    ];

    const today4 = new Date();
    let selectedYear4 = today4.getFullYear();
    let selectedMonth4 = monthNames4[today4.getMonth()];
    let selectedAsesor4 = 'MARIA ALEJANDRA GIRALDO YEPES';
    function loadDropdowns4() {
        $.when(
            $.get('/api/ventas/years'),
            $.get('/api/ventas/months'),
            $.get('/api/ventas/vendedores'),
        ).done((yearsRes, monthsRes, vendedoresRes) => {
            console.log("Asesores",vendedoresRes[0]);
            
            let years = yearsRes[0];
            let months = monthsRes[0];
            let asesores =vendedoresRes[0];

            $('#yearSelect4').html(
                years.map(y => `<option value="${y}">${y}</option>`).join('')
            ).val(selectedYear4);

            $('#monthSelect4').html(
                months.map(m => `<option value="${m}">${m}</option>`).join('')
            ).val(selectedMonth4);
            $('#asesorSelect4').html(
                asesores.map(m => `<option value="${m}">${m}</option>`).join('')
            ).val(selectedAsesor4);


        });
    }
    


    $("#yearSelect4").on("change", function () {
        selectedYear4 = $(this).val();
        comisiones();
        tabla_ventas();


    });

    $("#monthSelect4").on("change", function () {
        selectedMonth4 = $(this).val();
        comisiones();
        tabla_ventas();



    });
    $("#asesorSelect4").on("change", function () {
        selectedAsesor4 = $(this).val();
        comisiones();
        tabla_ventas();
      

    });


    
    function comisiones(){
        $.ajax({
        url: `/api/ventas/comisiones/asesor?year=${selectedYear4}&month=${selectedMonth4}&asesor=${selectedAsesor4}`,
        method: 'GET',
        dataType: 'json',
        success: function(chartData) {
            const html = `
            <div class="card" style="
                        border: 2px solid #574998ff;
                        box-shadow: 31px 33px 10px -11px rgba(25,0,255,0.1);
                        width: 100%;
                        max-width: 450px;
                        margin: 0 auto;
                        overflow: hidden;">

                <div class="card-header text-center h3">${"Comisiones"}</div>

                <div class="card-body d-flex flex-column">
                    <div class="row flex-grow-1">

                        <div class="col-md-6 border-end border-bottom d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData["comisiones 1-25"]}</div>
                                <div>Comisiones 1-25</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-bottom d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData["comisiones 26-fin"]}</div>
                                <div>Comisiones 26-fin de mes</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-end d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData["comisiones pendientes"]}</div>
                                <div>Pendientes recurrencia</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-end d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData["comisiones mes"]}</div>
                                <div>Mes actual</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            `;

            $("#comisionesContainer").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#comisionesContainer').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
   
    function tabla_ventas() {

        $.ajax({
            url: `/api/ventas/comisiones/tabla?year=${selectedYear4}&month=${selectedMonth4}&asesor=${selectedAsesor4}`,
            method: 'GET',
            dataType: 'json',

            success: function(chartData) {

                if (!Array.isArray(chartData)) {
                    console.error("API did not return an array:", chartData);
                    $("#tablaContainer").html("<div style='color:red;'>Invalid data format</div>");
                    return;
                }


                let rowsHtml = chartData.map((row, rowIndex) => `
                    <tr>
                        <td>${row.VENDEDOR ?? ""}</td>
                        <td>${row.FECHA ?? ""}</td>
                        <td>${row.CLIENTE ?? ""}</td>
                        <td>${row["VALOR VENTA"] ?? ""}</td>
                        <td>${row["PRODUCTO COMPLETO"] ?? ""}</td>
                        <td>${row.PRODUCTO ?? ""}</td>
                        <td>${row["VALOR COMISIONABLE"] ?? ""}</td>
                        <td>${row["COMISION MES 1"] ?? ""}</td>
                        <td>${row["COMISION MES 2"] ?? ""}</td>
                        <td>${row["COMISION MES 3"] ?? ""}</td>
                    </tr>
                `).join("");

                const html = `
                    <table class="table table-condensed table-striped table-bordered text-center"
                        style="overflow:hidden; word-wrap:break-word; hyphens:auto;">
                        
                        <thead style="background-color:#574998; color:white;">
                            <tr>
                                <th>Vendedor</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Valor</th>
                                <th>Producto completo</th>
                                <th>Producto</th>
                                <th>Valor comisionable</th>
                                <th>Comisión mes 1</th>
                                <th>Comisión mes 2</th>
                                <th>Comisión mes 3</th>
                            </tr>
                        </thead>

                        <tbody>
                            ${rowsHtml}
                        </tbody>

                    </table>
                `;

                $("#tablaContainer").html(html);
            },

            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#tablaContainer')
                    .html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }
    $(document).ready(function() {
        $("#load").hide();
        loadDropdowns4();
        comisiones();
        tabla_ventas();


    });
</script>