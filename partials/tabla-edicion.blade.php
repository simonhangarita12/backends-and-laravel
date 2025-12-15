<div>
    <div class="d-flex justify-content-center align-items-center gap-3 mb-4 w-100">

        <button id="updateButton" class="btn btn-primary" 
                style="background-color: #574998; color: white;"
                data-toggle="modal" 
                data-target="#modalBoton">
            Actualizar
        </button>
    <hr style="border: none; margin: 20px 0;">
        
        <div class="row">
            <div class="col-lg-12 text-center">
                <div id="tablaUpdate" style="width: 100%; min-height: 300px;"></div>
            </div>

        </div>
        
        <div class="modal fade" id="modalBoton" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Actualización exitosa</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Se han guardado los cambios correctamente.
                        En unos momentos se reflejarán los cambios en el cuadro de ventas.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    let originalData = [];
    $("#updateButton").on("click", function () {

        let editedRows = [];
        
        $("#tablaUpdate tbody tr").each(function (index) {

            const original = originalData[index];


            const current = {
                ID: $(this).find("td:eq(0)").text().trim(),
                FECHA: $(this).find("td:eq(1)").text().trim(),
                VENDEDOR: $(this).find("td:eq(2)").text().trim(),
                CLIENTE: $(this).find("td:eq(3)").text().trim(),
                VENTA: $(this).find("td:eq(4)").text().trim(),
                PRODUCTO_COMPLETO: $(this).find("td:eq(5)").text().trim(),
                SERVICIO: $(this).find("td:eq(6)").text().trim(),
                VALOR_PROVEEDOR: $(this).find("td:eq(7)").text().trim(),
                VALOR_COMISIONABLE: $(this).find("td:eq(8)").text().trim(),
                FACTURA: $(this).find("td:eq(9)").text().trim()
            };

            const changed = Object.keys(current).some(key => current[key] != original[key]);

            if (changed) {
                editedRows.push(current);
            }
        });

        console.log("Edited rows:", editedRows);
        $.ajax({
            url: "/api/ventas/ventas/database/refresh",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(editedRows),
            success: function (response) {
                console.log("Updated successfully:", response);
                $('#modalBoton').modal("show");
            },
            error: function (xhr) {
                console.error("Error updating:", xhr.responseText);
            }
        });
        
    });



   
    function ventas_update() {

        $.ajax({
            url: `/api/ventas/ventas/database`,
            method: 'GET',
            dataType: 'json',

            success: function(chartData) {
                originalData = chartData.map(row => ({
                    ID: row.ID?.toString().trim() ?? "",
                    FECHA: row.FECHA?.toString().trim() ?? "",
                    VENDEDOR: row.VENDEDOR?.toString().trim() ?? "",
                    CLIENTE: row.CLIENTE?.toString().trim() ?? "",
                    VENTA: row.VENTA?.toString().trim() ?? "",
                    PRODUCTO_COMPLETO: row["PRODUCTO COMPLETO"]?.toString().trim() ?? "",
                    SERVICIO: row.SERVICIO?.toString().trim() ?? "",
                    VALOR_PROVEEDOR: row["VALOR PROVEEDOR"]?.toString().trim() ?? "",
                    VALOR_COMISIONABLE: row["VALOR COMISIONABLE"]?.toString().trim() ?? "",
                    FACTURA: row.FACTURA?.toString().trim() ?? ""
                }));

                if (!Array.isArray(chartData)) {
                    console.error("API did not return an array:", chartData);
                    $("#tablaContainer").html("<div style='color:red;'>Invalid data format</div>");
                    return;
                }


                let rowsHtml = chartData.map((row, rowIndex) => `
                    <tr key="${rowIndex}">
                        <td >${row.ID ?? ""}</td>
                        <td contenteditable="true" >${row.FECHA ?? ""}</td>
                        <td>${row.VENDEDOR ?? ""}</td>
                        <td>${row.CLIENTE ?? ""}</td>
                        <td contenteditable="true">${row.VENTA ?? ""}</td>
                        <td>${row["PRODUCTO COMPLETO"] ?? ""}</td>
                        <td>${row.SERVICIO ?? ""}</td>
                        <td contenteditable="true">${row["VALOR PROVEEDOR"] ?? ""}</td>
                        <td>${row["VALOR COMISIONABLE"] ?? ""}</td>
                        <td contenteditable="true">${row.FACTURA ?? ""}</td>
                    </tr>
                `).join("");

                const html = `
                    <table class="table table-condensed table-striped table-bordered text-center"
                        style="overflow:hidden; word-wrap:break-word; hyphens:auto;">
                        
                        <thead style="background-color:#574998; color:white;">
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Vendedor</th>
                                <th>Cliente</th>
                                <th>Valor</th>
                                <th>Producto completo</th>
                                <th>Servicio</th>
                                <th>Valor proveedor</th>
                                <th>Valor comisionable</th>
                                <th>Factura</th>
                            </tr>
                        </thead>

                        <tbody>
                            ${rowsHtml}
                        </tbody>

                    </table>
                `;

                $("#tablaUpdate").html(html);
            },

            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#tablaUpdate')
                    .html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
            }
        });
    }

    $(document).ready(function() {
        $("#load").hide();
        ventas_update();


    });
</script>