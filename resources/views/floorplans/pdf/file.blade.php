<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reporte de Dispositivos</title>

    <style>
        * {
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif
        }

        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            margin-bottom: 20px;
            caption-side: top;
        }

        .pdf-table caption {
            text-align: left;
            font-weight: bold;
            font-size: 14px;
            padding: 8px 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .pdf-table th {
            border: 1px solid #dee2e6;
            padding: 4px;
            text-align: left;
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .pdf-table td {
            border: 1px solid #dee2e6;
            padding: 4px;
        }

        .pdf-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .color-box {
            width: 15px;
            height: 15px;
            border-radius: 3px;
        }

        .text-right {
            text-align: right;
        }

        .row {
            width: 100%;
            padding: 0;
            margin: 0;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .row div {
            margin-bottom: 1px;
            margin: 0px;
        }

        .row span {
            margin: 0px;
        }

        .row::after {
            content: "";
            display: block;
            clear: both;
        }

        .title {
            width: 49%;
            float: left;
            text-align: left;
            margin: 0;

        }

        .logo {
            width: 49%;
            float: right;
            text-align: center;
            margin: 0;

        }

        .watermark {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            text-align: center;
            pointer-events: none;
            z-index: -1;
            opacity: 0.1;
        }

        .watermark img {
            display: inline-block;
            margin: auto;
            position: relative;
            top: 40%;
            transform: translateY(-50%);
        }
    </style>
</head>

<body>
    @php
        function arrayToRangeString($array)
        {
            if (empty($array)) {
                return '';
            }

            // Ordenar el array
            sort($array);

            $result = [];
            $start = $array[0];
            $end = $start;

            for ($i = 1; $i <= count($array); $i++) {
                if (isset($array[$i]) && $array[$i] == $end + 1) {
                    $end = $array[$i];
                } else {
                    if ($start == $end) {
                        $result[] = "$start";
                    } else {
                        $result[] = "$start-$end";
                    }
                    if (isset($array[$i])) {
                        $start = $array[$i];
                        $end = $start;
                    }
                }
            }

            return implode(', ', $result);
        }
    @endphp

    <div class="watermark">
        <img src="file://{{ public_path('images/watermark.png') }}">
    </div>

    <div class="row">
        <div class="title">
            <h1 style="font-size: 22px; margin: 0;">{{ $floorplan['name'] }}</h1>
        </div>
        <div class="logo">
            <img src="file://{{ public_path('images/logo.png') }}" style="width: 300px; margin: 0;">
        </div>
    </div>

    <div class="row">
        <div class="middle-row">
            <div><span style="font-weight: bold;">Sede</span>: {{ $floorplan['sede'] }}
            </div>
            <div><span style="font-weight: bold;">Cantidad de dispositivos</span>: {{ $floorplan['count'] }}
            </div>
            <div><span style="font-weight: bold;">Fecha de actualización</span>: {{ $floorplan['updated_date'] }}
            </div>
        </div>
    </div>

    <table class="pdf-table">
        <caption>Simbologia</caption> <!-- Añade el caption aquí -->
        <thead>
            <tr>
                <th>Color</th>
                <th>Tipo</th>
                <th>Código</th>
                <th>Cantidad</th>
                <th>Rangos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($legend as $leg)
                <tr>
                    <td>
                        <div class="color-box" style="background-color: {{ $leg['color'] }};"></div>
                    </td>
                    <td>{{ $leg['label'] }}</td>
                    <td>{{ $leg['code'] }}</td>
                    <td>{{ $leg['count'] }}</td>
                    <td>{{ arrayToRangeString($leg['numbers']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align: center;">
        <img src="{{ $floorplan['image'] }}"
            style="display: block; margin: 0 auto; max-width: 100$; width: {{ $img_sizes[0] }}; height: {{ $img_sizes[1] }};, max-height: 100%;">
    </div>
</body>

</html>
