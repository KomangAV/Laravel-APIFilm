<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $apiKey = env('TMDB_API_KEY'); // Pastikan Anda menambahkan kunci API ke .env
        $query = $request->input('query', '');
        $genreId = $request->input('genre', '');
        $movies = [];
        $genres = [];

        // Mendapatkan daftar genre
        $genreUrl = "https://api.themoviedb.org/3/genre/movie/list?api_key={$apiKey}&language=en-US";
        $genreResponse = file_get_contents($genreUrl);
        $genreData = json_decode($genreResponse, true);
        $genres = $genreData['genres'] ?? [];

        // Jika ada query pencarian
        if ($query) {
            $apiUrl = "https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&query=" . urlencode($query);
            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);
            $movies = $data['results'] ?? [];
        } elseif ($genreId) {
            $apiUrl = "https://api.themoviedb.org/3/discover/movie?api_key={$apiKey}&with_genres=" . urlencode($genreId);
            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);
            $movies = $data['results'] ?? [];
        } else {
            // Film populer jika tidak ada query atau genre
            $apiUrl = "https://api.themoviedb.org/3/movie/popular?api_key={$apiKey}";
            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);
            $movies = $data['results'] ?? [];
        }

        return view('movies.index', ['movies' => $movies, 'query' => $query, 'genres' => $genres, 'selectedGenre' => $genreId]);
    }
}
