<div>
    <div class="d-flex justify-content-center align-items-center gap-3 mb-4 w-100">

        <div class="flex-1 min-w-150px">
            <select id="yearSelect2" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>

        <div class="flex-1 min-w-150px mt-2">
            <select id="monthSelect2" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>
        <div class="flex-1 min-w-150px mt-2">
            <select id="asesorSelect2" class="form-select" style="background-color: #574998; color: white;">
            </select>
        </div>

    </div>
    <div id="botonContainer2">
    </div>
    
    <hr style="border: none; margin: 20px 0;">
        
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="anualidadContainer2" style="width: 100%; min-height: 300px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="recurrenciaContainer2" style="width: 100%; min-height: 300px;"></div>
            </div>
        </div>
    <hr style="border: none; margin: 20px 0;">
        
        <div class="row">
            <div class="col-lg-6 text-center">
                <div id="otrosContainer2" style="width: 100%; min-height: 300px;"></div>
            </div>
            <div class="col-lg-6 text-center">
                <div id="anualidadrcteContainer2" style="width: 100%; min-height: 3000px;"></div>
            </div>
        </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    const monthNames2 = [
        "Enero","Febrero","Marzo","Abril","Mayo","Junio",
        "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
    ];

    const today2 = new Date();
    let selectedYear2 = today2.getFullYear();
    let selectedMonth2 = monthNames2[today2.getMonth()];
    let selectedAsesor2 = 'MARIA ALEJANDRA GIRALDO YEPES';
    function loadDropdowns2() {
        $.when(
            $.get('/api/ventas/years'),
            $.get('/api/ventas/months'),
            $.get('/api/ventas/vendedores'),
        ).done((yearsRes, monthsRes, vendedoresRes) => {
            console.log("Asesores",vendedoresRes[0]);
            
            let years = yearsRes[0];
            let months = monthsRes[0];
            let asesores =vendedoresRes[0];

            $('#yearSelect2').html(
                years.map(y => `<option value="${y}">${y}</option>`).join('')
            ).val(selectedYear2);

            $('#monthSelect2').html(
                months.map(m => `<option value="${m}">${m}</option>`).join('')
            ).val(selectedMonth2);
            $('#asesorSelect2').html(
                asesores.map(m => `<option value="${m}">${m}</option>`).join('')
            ).val(selectedAsesor2);


        });
    }
    function loadButtons2(selectedYear2, selectedMonth2,selectedAsesor2) {

    $.when(
        $.get(`/api/ventas/ventas/totales/asesor?year=${selectedYear2}&month=${selectedMonth2}&asesor=${selectedAsesor2}`),
        $.get(`/api/ventas/cumplimiento/presupuesto/asesor?year=${selectedYear2}&month=${selectedMonth2}&asesor=${selectedAsesor2}`),
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
                            Ventas ${selectedMonth2} ${selectedYear2}
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
        $("#botonContainer2").html(html);
    })
    .fail(err => console.error(" Error loading buttons data: ", err));
    }




    $("#yearSelect2").on("change", function () {
        selectedYear2 = $(this).val();
        anualidad_asesor();
        recurrencia_asesor();
        otros_asesor();
        anualidad_rcte_asesor();
        loadButtons2(selectedYear2, selectedMonth2,selectedAsesor2);
    });

    $("#monthSelect2").on("change", function () {
        selectedMonth2 = $(this).val();
        anualidad_asesor();
        recurrencia_asesor();
        otros_asesor();
        anualidad_rcte_asesor();
        loadButtons2(selectedYear2, selectedMonth2,selectedAsesor2);

    });
    $("#asesorSelect2").on("change", function () {
        selectedAsesor2 = $(this).val();
        anualidad_asesor();
        recurrencia_asesor();
        otros_asesor();
        anualidad_rcte_asesor();
        loadButtons2(selectedYear2, selectedMonth2,selectedAsesor2);

    });


    
    function anualidad_asesor(){
        $.ajax({
        url: `/api/ventas/cumplimiento/asesor/anualidad?year=${selectedYear2}&month=${selectedMonth2}&asesor=${selectedAsesor2}`,
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

            $("#anualidadContainer2").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#anualidadContainer2').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    function recurrencia_asesor(){
        $.ajax({
        url: `/api/ventas/cumplimiento/asesor/recurrencia?year=${selectedYear2}&month=${selectedMonth2}&asesor=${selectedAsesor2}`,
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

            $("#recurrenciaContainer2").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#recurrenciaContainer2').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    function otros_asesor(){
        $.ajax({
        url: `/api/ventas/cumplimiento/asesor/otros?year=${selectedYear2}&month=${selectedMonth2}&asesor=${selectedAsesor2}`,
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

            $("#otrosContainer2").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#otrosContainer2').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    function anualidad_rcte_asesor(){
        $.ajax({
        url: `/api/ventas/cumplimiento/asesor/anualidadrcte?year=${selectedYear2}&month=${selectedMonth2}&asesor=${selectedAsesor2}`,
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

            $("#anualidadrcteContainer2").html(html);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#anualidadrcteContainer2').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
        }
    });
    }
    $(document).ready(function() {
        $("#load").hide();
        loadDropdowns2();
        loadButtons2(selectedYear2, selectedMonth2,selectedAsesor2);
        anualidad_asesor();
        recurrencia_asesor();
        otros_asesor();
        anualidad_rcte_asesor();

    });
</script>