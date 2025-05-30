<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Constructor para asegurar que sólo usuarios autenticados y artistas puedan acceder
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('artist');
    }
    
    /**
     * Generar reporte PDF de publicaciones del artista
     */
    public function generatePublicationsReport()
    {
        $artist = Auth::user();
        
        // Cargar todas las obras del artista con sus relaciones
        $artworks = $artist->artworks()
            ->with(['category', 'likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Datos para la vista
        $data = [
            'artist' => $artist,
            'artworks' => $artworks,
            'total_likes' => $artworks->sum(function($artwork) {
                return $artwork->likes->count();
            }),
            'total_comments' => $artworks->sum(function($artwork) {
                return $artwork->comments->count();
            }),
            'date' => now()->format('d/m/Y H:i'),
        ];
        
        // Generar el PDF
        $pdf = PDF::loadView('reports.publications', $data);
        
        // Configurar el PDF
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);
        
        // Descargar el PDF
        return $pdf->download('reporte-publicaciones-' . now()->format('Y-m-d') . '.pdf');
    }
}
