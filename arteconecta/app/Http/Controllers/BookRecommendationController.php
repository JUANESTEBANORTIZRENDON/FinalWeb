<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookRecommendationController extends Controller
{
    /**
     * El punto de entrada para la página de recomendaciones de libros
     * Muestra libros relacionados con arte por defecto
     */
    public function index(Request $request)
    {
        // Término de búsqueda predeterminado si no se proporciona uno
        $searchTerm = $request->query('q', 'arte pintura');
        
        // Obtener libros de la API
        $books = $this->searchBooks($searchTerm);
        
        // Categorías populares para búsqueda rápida
        $categories = [
            'Arte Contemporáneo',
            'Técnicas de Pintura',
            'Historia del Arte',
            'Fotografía Artística',
            'Escultura',
            'Arte Digital',
            'Arte Latinoamericano',
            'Diseño Gráfico'
        ];
        
        return view('books.recommendations', [
            'books' => $books,
            'searchTerm' => $searchTerm,
            'categories' => $categories
        ]);
    }
    
    /**
     * Buscar libros usando la API de Google Books
     */
    private function searchBooks($query)
    {
        // Preparar la consulta para la API
        $formattedQuery = str_replace(' ', '+', $query);
        
        // Crear la URL de la API - sin autenticación
        $apiUrl = "https://www.googleapis.com/books/v1/volumes?q={$formattedQuery}+subject:art&maxResults=12&langRestrict=es";
        
        try {
            // Realizar la solicitud a la API
            $response = Http::get($apiUrl);
            
            // Verificar si la solicitud fue exitosa
            if ($response->successful()) {
                $data = $response->json();
                
                // Verificar si hay resultados
                if (isset($data['items']) && !empty($data['items'])) {
                    return $this->formatBookResults($data['items']);
                }
            }
            
            // Si no hay resultados o la solicitud falló
            return [];
            
        } catch (\Exception $e) {
            // Registrar el error pero no mostrarlo al usuario
            \Log::error('Error al obtener datos de Google Books API: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Formatear los resultados de la API para tener una estructura consistente
     */
    private function formatBookResults($items)
    {
        $formattedBooks = [];
        
        foreach ($items as $item) {
            $volumeInfo = $item['volumeInfo'] ?? [];
            
            // Preparar datos del libro con manejo seguro para claves que pueden no existir
            $book = [
                'id' => $item['id'] ?? '',
                'title' => $volumeInfo['title'] ?? 'Título desconocido',
                'authors' => $volumeInfo['authors'] ?? ['Autor desconocido'],
                'description' => $volumeInfo['description'] ?? 'No hay descripción disponible',
                'thumbnail' => isset($volumeInfo['imageLinks']['thumbnail']) 
                    ? $volumeInfo['imageLinks']['thumbnail'] 
                    : 'https://via.placeholder.com/128x192?text=Sin+Imagen',
                'publishedDate' => $volumeInfo['publishedDate'] ?? 'Fecha desconocida',
                'publisher' => $volumeInfo['publisher'] ?? 'Editorial desconocida',
                'pageCount' => $volumeInfo['pageCount'] ?? 0,
                'categories' => $volumeInfo['categories'] ?? [],
                'previewLink' => $volumeInfo['previewLink'] ?? '',
                'infoLink' => $volumeInfo['infoLink'] ?? '',
            ];
            
            // Truncar descripción si es muy larga
            if (strlen($book['description']) > 300) {
                $book['description'] = substr($book['description'], 0, 300) . '...';
            }
            
            $formattedBooks[] = $book;
        }
        
        return $formattedBooks;
    }
}
