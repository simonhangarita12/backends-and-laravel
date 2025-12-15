<div>
    <div class="d-flex justify-content-center align-items-center gap-3 mb-4 w-100">

        <div class="flex-1 min-w-150px">
            <select id="yearSelect3" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>

        

    </div>
    <div id="botonContainer3">
    </div>
    
    <hr style="border: none; margin: 20px 0;">
        
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="anualidadContainer3" style="width: 100%; min-height: 300px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="recurrenciaContainer3" style="width: 100%; min-height: 300px;"></div>
            </div>
        </div>
    <hr style="border: none; margin: 20px 0;">
        
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="otrosContainer3" style="width: 100%; min-height: 300px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="anualidadrcteContainer3" style="width: 100%; min-height: 3000px;"></div>
            </div>
        </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    

    const today3 = new Date();
    let selectedYear3 = today3.getFullYear();
    function loadDropdowns3() {
     
            $.get('/api/ventas/years',function(years){
    
                if (!Array.isArray(years) || years.length === 0) return;
    
                $('#yearSelect3')
                    .html(years.map(y => `<option value="${y}">${y}</option>`).join(''))
                    .val(selectedYear3);
            });
        
    }
    function loadButtons3(selectedYear3) {

    $.when(
        $.get(`/api/ventas/ventas/totales/year?year=${selectedYear3}`),
        $.get(`/api/ventas/cumplimiento/presupuesto/year?year=${selectedYear3}`),
    )
    .done((ventasRes, cumplimientoRes) => {
        const ventas = ventasRes[0].ventas;
        const cumplimiento = cumplimientoRes[0].por_centaje;
        const color = cumplimientoRes[0].color;
        
        const html = `
            <div class="d-flex justify-content-between align-items-center w-100 flex-wrap">

                <div class="mb-2">
                    <button type="button" class="btn btn-primary position-relative text-dark"
                        style="background-color:white; border:1px solid #ccc; font-weight:bold; padding-right: 25px;">
                        ${ventas}
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-dark"
                            style="background-color:#e0e0e0; font-weight:bold; white-space: nowrap;">
                            Ventas  ${selectedYear3}
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
        $("#botonContainer3").html(html);
    })
    .fail(err => console.error(" Error loading buttons data: ", err));
    }




    $("#yearSelect3").on("change", function () {
        selectedYear3 = $(this).val();
        anualidad_year();
        recurrencia_year();
        otros_year();
        anualidad_rcte_year();
        loadButtons3(selectedYear3);
    });



    
    function anualidad_year(){
        $.ajax({
        url: `/api/ventas/cumplimiento/anualidad/year?year=${selectedYear3}`,
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

            $("#anualidadContainer3").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#anualidadContainer3').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    function recurrencia_year(){
        $.ajax({
        url: `/api/ventas/cumplimiento/recurrencia/year?year=${selectedYear3}`,
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

            $("#recurrenciaContainer3").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#recurrenciaContainer3').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    function otros_year(){
        $.ajax({
        url: `/api/ventas/cumplimiento/otros/year?year=${selectedYear3}`,
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

            $("#otrosContainer3").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#otrosContainer3').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    function anualidad_rcte_year(){
        $.ajax({
        url: `/api/ventas/cumplimiento/anualidadrcte/year?year=${selectedYear3}`,
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

            $("#anualidadrcteContainer3").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#anualidadrcteContainer3').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    $(document).ready(function() {
        $("#load").hide();
        loadDropdowns3();
        loadButtons3(selectedYear3);
        anualidad_year();
        recurrencia_year();
        otros_year();
        anualidad_rcte_year();

    });
</script>