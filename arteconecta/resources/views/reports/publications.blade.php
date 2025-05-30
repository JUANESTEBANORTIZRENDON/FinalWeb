<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Publicaciones - {{ $artist->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #8e24aa;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #7b1fa2;
            margin-bottom: 5px;
        }
        .artist-info {
            background-color: #f3e5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: #7b1fa2;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 10px;
        }
        tr:nth-child(even) {
            background-color: #f3e5f5;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        .summary-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin-bottom: 25px;
        }
        .summary-title {
            font-weight: bold;
            color: #7b1fa2;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ArteConecta</h1>
        <h2>Reporte de Publicaciones</h2>
        <p>Generado el {{ $date }}</p>
    </div>

    <div class="artist-info">
        <h3>Información del Artista</h3>
        <p><strong>Nombre:</strong> {{ $artist->name }}</p>
        <p><strong>Email:</strong> {{ $artist->email }}</p>
        <p><strong>Fecha de registro:</strong> {{ $artist->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="summary-box">
        <div class="summary-title">Resumen de Actividad</div>
        <p><strong>Total de publicaciones:</strong> {{ $artworks->count() }}</p>
        <p><strong>Total de "Me gusta" recibidos:</strong> {{ $total_likes }}</p>
        <p><strong>Total de comentarios recibidos:</strong> {{ $total_comments }}</p>
    </div>

    <h3>Listado de Publicaciones</h3>
    
    @if($artworks->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Me gusta</th>
                    <th>Comentarios</th>
                    <th>Fecha de creación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($artworks as $artwork)
                    <tr>
                        <td>{{ $artwork->title }}</td>
                        <td>{{ $artwork->category ? $artwork->category->name : 'Sin categoría' }}</td>
                        <td>{{ $artwork->likes->count() }}</td>
                        <td>{{ $artwork->comments->count() }}</td>
                        <td>{{ $artwork->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No se han encontrado publicaciones.</p>
    @endif

    <div class="footer">
        <p>Este reporte contiene información confidencial y es para uso exclusivo de {{ $artist->name }}.</p>
        <p>© {{ date('Y') }} ArteConecta - Todos los derechos reservados</p>
    </div>
</body>
</html>
