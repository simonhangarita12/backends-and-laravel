
<div>
    <div class="d-flex justify-content-center align-items-center gap-3 mb-4 w-100">

        <div class="flex-1 min-w-150px">
            <select id="yearSelect1" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>

        <div class="flex-1 min-w-150px mt-2">
            <select id="monthSelect1" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>

    </div>
    <div id="botonContainer">
    </div>
    
    <hr style="border: none; margin: 20px 0;">
        
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="anualidadContainer" style="width: 100%; min-height: 300px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="recurrenciaContainer" style="width: 100%; min-height: 300px;"></div>
            </div>
        </div>
    <hr style="border: none; margin: 20px 0;">
        
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="otrosContainer" style="width: 100%; min-height: 300px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="anualidadrcteContainer" style="width: 100%; min-height: 3000px;"></div>
            </div>
        </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    const monthNames1 = [
        "Enero","Febrero","Marzo","Abril","Mayo","Junio",
        "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
    ];

    const today1 = new Date();
    let selectedYear1 = today1.getFullYear();
    let selectedMonth1 = monthNames1[today1.getMonth()];
    function loadDropdowns() {
        $.when(
            $.get('/api/ventas/years'),
            $.get('/api/ventas/months')
        ).done((yearsRes, monthsRes) => {
            console.log(yearsRes);
            console.log(monthsRes);
            
            let years = yearsRes[0];
            let months = monthsRes[0];

            $('#yearSelect1').html(
                years.map(y => `<option value="${y}">${y}</option>`).join('')
            ).val(selectedYear1);

            $('#monthSelect1').html(
                months.map(m => `<option value="${m}">${m}</option>`).join('')
            ).val(selectedMonth1);


        });
    }
    function loadButtons(selectedYear1, selectedMonth1) {

    $.when(
        $.get(`/api/ventas/ventas/totales/mes?year=${selectedYear1}&month=${selectedMonth1}`),
        $.get(`/api/ventas/cumplimiento/presupuesto/mes?year=${selectedYear1}&month=${selectedMonth1}`)
    )
    .done((ventasRes, cumplimientoRes) => {
        console.log('Ventas: ', ventasRes[0]);
        console.log('Cumplimiento: ', cumplimientoRes[0]);
        const ventas = ventasRes[0].ventas;
        const cumplimiento = cumplimientoRes[0].por_centaje;
        const color = cumplimientoRes[0].color;
        console.log('Color: ', color);
        const html = `
            <div class="d-flex justify-content-between align-items-center w-100 flex-wrap">

                <div class="mb-2">
                    <button type="button" class="btn btn-primary position-relative text-dark"
                        style="background-color:white; border:1px solid #ccc; font-weight:bold; padding-right: 25px;">
                        ${ventas}
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-dark"
                            style="background-color:#e0e0e0; font-weight:bold; white-space: nowrap;">
                            Ventas ${selectedMonth1} ${selectedYear1}
                        </span>
                    </button>
                </div>

                <div class="mb-2">
                    <button type="button" class="btn position-relative text-dark"
                        style="background-color:${color}; border:1px solid #ccc; font-weight:bold; padding-right: 25px;">
                        ${cumplimiento}
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-dark"
                            style="background-color:#e0e0e0; font-weight:bold; white-space: nowrap; color:#444;">
                            % Cumplimiento meta
                        </span>
                    </button>
                </div>

            </div>
            `;
        $("#botonContainer").html(html);
    })
    .fail(err => console.error(" Error loading buttons data: ", err));
    }




    $("#yearSelect1").on("change", function () {
        selectedYear1 = $(this).val();
        anualidad_mes();
        recurrencia_mes();
        otros_mes();
        anualidad_rcte_mes();
        loadButtons(selectedYear1, selectedMonth1);
    });

    $("#monthSelect1").on("change", function () {
        selectedMonth1 = $(this).val();
        anualidad_mes();
        recurrencia_mes();
        otros_mes();
        anualidad_rcte_mes();
        loadButtons(selectedYear1, selectedMonth1);

    });


    //loadDropdowns();
    //loadButtons(selectedYear1, selectedMonth1);
    function anualidad_mes(){
        $.ajax({
        url: `/api/ventas/cumplimiento/anualidad/mes?year=${selectedYear1}&month=${selectedMonth1}`,
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

                <div class="card-header text-center h3">${"Anualidad"}</div>

                <div class="card-body d-flex flex-column">
                    <div class="row flex-grow-1">

                        <div class="col-md-6 border-end border-bottom d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.por_centaje}</div>
                                <div>Porcentaje</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-bottom d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.ventas}</div>
                                <div>Ventas</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-end d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.valor}</div>
                                <div>Presupuesto</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-end border-bottom position-relative"
                             style="background-color: ${chartData.color};">
                            <div class="position-absolute top-50 start-50 translate-middle text-center w-100">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.restante}</div>
                                <div>Restante</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            `;

            $("#anualidadContainer").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#anualidadContainer').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    function recurrencia_mes(){
        $.ajax({
        url: `/api/ventas/cumplimiento/recurrencia/mes?year=${selectedYear1}&month=${selectedMonth1}`,
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

                <div class="card-header text-center h3">${"Recurrencia"}</div>

                <div class="card-body d-flex flex-column">
                    <div class="row flex-grow-1">

                        <div class="col-md-6 border-end border-bottom d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.por_centaje}</div>
                                <div>Porcentaje</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-bottom d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.ventas}</div>
                                <div>Ventas</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-end d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.valor}</div>
                                <div>Presupuesto</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-end border-bottom position-relative"
                             style="background-color: ${chartData.color};">
                            <div class="position-absolute top-50 start-50 translate-middle text-center w-100">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.restante}</div>
                                <div>Restante</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            `;

            $("#recurrenciaContainer").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#recurrenciaContainer').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    function otros_mes(){
        $.ajax({
        url: `/api/ventas/cumplimiento/otros/mes?year=${selectedYear1}&month=${selectedMonth1}`,
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

                <div class="card-header text-center h3">${"Otros productos"}</div>

                <div class="card-body d-flex flex-column">
                    <div class="row flex-grow-1">

                        <div class="col-md-6 border-end border-bottom d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.por_centaje}</div>
                                <div>Porcentaje</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-bottom d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.ventas}</div>
                                <div>Ventas</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-end d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.valor}</div>
                                <div>Presupuesto</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-end border-bottom position-relative"
                             style="background-color: ${chartData.color};">
                            <div class="position-absolute top-50 start-50 translate-middle text-center w-100">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.restante}</div>
                                <div>Restante</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            `;

            $("#otrosContainer").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#otrosContainer').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    function anualidad_rcte_mes(){
        $.ajax({
        url: `/api/ventas/cumplimiento/anualidadrcte/mes?year=${selectedYear1}&month=${selectedMonth1}`,
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

                <div class="card-header text-center h3">${"Anualidad recurrente"}</div>

                <div class="card-body d-flex flex-column">
                    <div class="row flex-grow-1">

                        <div class="col-md-6 border-end border-bottom d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.por_centaje}</div>
                                <div>Porcentaje</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-bottom d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.ventas}</div>
                                <div>Ventas</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-end d-flex flex-column justify-content-center">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.valor}</div>
                                <div>Presupuesto</div>
                            </div>
                        </div>

                        <div class="col-md-6 border-end border-bottom position-relative"
                             style="background-color: ${chartData.color};">
                            <div class="position-absolute top-50 start-50 translate-middle text-center w-100">
                                <div style="font-size: 1.5rem; font-weight: bold;">${chartData.restante}</div>
                                <div>Restante</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            `;

            $("#anualidadrcteContainer").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#anualidadrcteContainer').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    $(document).ready(function() {
        $("#load").hide();
        loadDropdowns();
        loadButtons(selectedYear1, selectedMonth1);
        anualidad_mes();
        recurrencia_mes();
        otros_mes();
        anualidad_rcte_mes();
        
        

    });
</script>