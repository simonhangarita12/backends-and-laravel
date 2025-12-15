<!DOCTYPE html>
<html>
<head>
    <title>File Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <input id="fileInput" type="file" accept="image/*,video/*">
    <button onclick="uploadFile()">Cargar archivo</button>

    <br><br>

    <button onclick="downloadPDF()">Descargar PDF</button>
    <button onclick="downloadMultimedia()">Descargar multimedia</button>

    <div id="loading" style="display:none;">
        <p>Loading...</p>
    </div>

<script>
function uploadFile() {
    const file = document.getElementById("fileInput").files[0];
    if (!file) return alert("Seleccione un archivo");

    const formData = new FormData();
    const inputType = file.type.startsWith("video") ? "video" : "image";

    formData.append("file", file);
    formData.append("input_type", inputType);
    formData.append("output_format", "both");
    formData.append("save_annotated", "true");

    document.getElementById("loading").style.display = "block";

    fetch("/api/multimedia/analyze", {
        method: "POST",
        body: formData
    })
    .then(res => {
        if (!res.ok) {
            // If the server returns a non-200 status, throw an error
            return res.text().then(text => {
                throw new Error(`Analysis failed with status ${res.status}: ${text}`);
            });
        }
        return res.json(); 
    })
    .then(data => {
        console.log("Analysis completed. Job ID:", data.job_id);
        alert("Análisis completado. Ya puede descargar el PDF y el multimedia.");
        document.getElementById("loading").style.display = "none";
    })
    .catch(err => {
        console.error("Analysis Error:", err);
        document.getElementById("loading").style.display = "none";
        alert("Error durante el análisis. Consulte la consola.");
    });
}

function downloadPDF() {
    fetch("/api/multimedia/send_pdf")
    .then(res => res.blob())
    .then(blob => {
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "analysis.pdf";
        link.click();
    });
}

function downloadMultimedia() {
    fetch("/api/multimedia/send_multimedia")
    .then(res => {
        const filename = res.headers.get("Content-Disposition")?.split("filename=")[1] || "output_file";
        return res.blob().then(blob => ({ blob, filename }));
    })
    .then(({ blob, filename }) => {
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    });
}
</script>

</body>
</html>
