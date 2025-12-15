@extends('layouts.dashboard')
@section('page_heading', 'Requisitos Legales')
@section('section')

<div class="container-fluid mt-10">
    <div class="row mb-3">
        <div class="col-md-4">
            <form method="GET" action="{{ route('requisitosLegales', $id_empresa) }}">
                <label for="yearFilter" class="form-label" style="font-weight:600;">Filtro anual para documentos en línea</label>
                <select name="year" id="yearFilter" class="form-control" onchange="this.form.submit()">
                    <option value="">Todos los años</option>
                    @php 
                        $currentYear = date('Y');
                    @endphp
                    @for ($y = $currentYear; $y >= 2015; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <table id="data_tabla" class="table table-condensed table-striped table-bordered">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="text-align: center; vertical-align: middle; border: 1px solid #000; background-color: #90EE90;">NORMA</th>
                <th style="text-align: center; vertical-align: middle; border: 1px solid #000;">ASUNTO</th>
                <th style="text-align: center; vertical-align: middle; border: 1px solid #000;">ARTÍCULO</th>
                <th style="text-align: center; vertical-align: middle; border: 1px solid #000;">REQUISITO LEGAL</th>
                <th style="text-align: center; vertical-align: middle; border: 1px solid #000;">EVIDENCIA DE CUMPLIMIENTO</th>
                <th style="text-align: center; vertical-align: middle; border: 1px solid #000;">CUMPLIMIENTO SEGMENTO DEL REQUISITO</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requisitos as $requisito)
                <tr>
                    <td style="border: 1px solid #000;">{{ $requisito->norma ?? '' }}</td>
                    <td style="border: 1px solid #000;">
                        <div class="toggle-text">
                            <div class="text-content">{{ $requisito->asunto ?? '' }}</div>
                            <button type="button" class="btn btn-link btn-xs toggle-btn">Ver más</button>
                        </div>
                    </td>
                    <td style="border: 1px solid #000;">{{ $requisito->articulo ?? '' }}</td>
                    <td style="border: 1px solid #000;">
                        <div class="toggle-text">
                            <div class="text-content">{{ $requisito->requisito_legal ?? '' }}</div>
                            <button type="button" class="btn btn-link btn-xs toggle-btn">Ver más</button>
                        </div>
                    </td>
                    <td style="border: 1px solid #000;">{{ $requisito->evidencia_de_cumplimiento ?? '' }}</td>
                    <td style="border: 1px solid #000;">{{ $requisito->cumplimiento_segmento_del_requisito ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">No hay registros para el año seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
.toggle-text .text-content { display: inline; }
.toggle-btn { padding: 0; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const MAX = 100;
    document.querySelectorAll('.toggle-text').forEach(function (container) {
        const content = container.querySelector('.text-content');
        const btn = container.querySelector('.toggle-btn');
        if (!content || !btn) return;

        const full = (content.textContent || '').trim();
        if (full.length <= MAX) {
            btn.style.display = 'none';
            return;
        }

        container.dataset.full = full;
        container.dataset.truncated = full.slice(0, MAX) + '…';
        content.textContent = container.dataset.truncated;
        btn.textContent = 'Ver más';

        btn.addEventListener('click', function () {
            const expanded = container.dataset.expanded === 'true';
            if (!expanded) {
                content.textContent = container.dataset.full;
                btn.textContent = 'Ver menos';
                container.dataset.expanded = 'true';
            } else {
                content.textContent = container.dataset.truncated;
                btn.textContent = 'Ver más';
                container.dataset.expanded = 'false';
            }
        });
    });
});
</script>

@endsection
