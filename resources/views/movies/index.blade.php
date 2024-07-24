<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Movie DB</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .movie-card {
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .movie-card img {
            width: 100%;
            height: auto;
            border-radius: 0.5rem;
            transition: opacity 0.3s ease;
        }
        .movie-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        .movie-card:hover .description-button {
            opacity: 1;
        }
        .movie-description {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 1rem;
            transform: translateY(100%);
            transition: transform 0.3s ease, opacity 0.3s ease;
            border-radius: 0.5rem;
            overflow: hidden;
            opacity: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .movie-card.active .movie-description {
            transform: translateY(0);
            opacity: 1;
        }
        .rating {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0.5rem 0;
        }
        .rating span {
            color: #f39c12; /* Warna bintang penuh */
            font-size: 1.25rem;
            margin: 0 2px; /* Jarak antar bintang */
        }
        .rating span.empty {
            color: #bdc3c7; /* Warna bintang kosong */
        }
        .search-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .search-form > * {
            flex: 1;
        }
        .description-button {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 0, 0, 0.8);
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: background 0.3s ease, opacity 0.3s ease;
            z-index: 10;
            font-weight: bold;
            opacity: 0;
        }
        .description-button:hover {
            background: rgba(255, 0, 0, 1);
        }
        @media (max-width: 768px) {
            .description-button {
                bottom: 0.5rem; /* Sedikit jarak dari bawah */
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <header class="bg-black py-4 shadow-md">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between">
            <h1 class="text-3xl font-bold text-white mb-4 md:mb-0">The Movie DB</h1>
            <form id="search-form" class="search-form">
                <input 
                    type="text" 
                    name="query" 
                    id="search-input" 
                    value="{{ $query }}" 
                    placeholder="Cari film..." 
                    class="w-full px-4 py-2 rounded-md border border-gray-700 bg-gray-800 text-white placeholder-gray-400"
                >
                <select 
                    name="genre" 
                    id="genre-select" 
                    class="w-full px-4 py-2 rounded-md border border-gray-700 bg-gray-800 text-white"
                >
                    <option value="">Pilih Genre</option>
                    @foreach ($genres as $genre)
                        <option value="{{ $genre['id'] }}" {{ $selectedGenre == $genre['id'] ? 'selected' : '' }}>
                            {{ $genre['name'] }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        @if ($movies)
            @if (count($movies) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($movies as $movie)
                        <div class="movie-card bg-gray-800 rounded-lg overflow-hidden shadow-lg relative">
                            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}">
                            <div class="movie-description p-4">
                                <h2 class="text-xl font-semibold mb-2">{{ $movie['title'] }}</h2>
                                <p><strong>Release Date:</strong> {{ $movie['release_date'] }}</p>
                                <div class="rating">
                                    @php
                                        $rating = $movie['vote_average'];
                                        $fullStars = floor($rating / 2);
                                        $halfStar = ($rating / 2) - $fullStars >= 0.5;
                                    @endphp
                                    @for ($i = 0; $i < 5; $i++)
                                        <span class="{{ $i < $fullStars ? '' : ($i === $fullStars && $halfStar ? 'empty' : 'empty') }}">
                                            {{ $i < $fullStars || ($i === $fullStars && $halfStar) ? '★' : '☆' }}
                                        </span>
                                    @endfor
                                    <span class="ml-2">({{ $rating }}/10)</span>
                                </div>
                                <p>{{ $movie['overview'] }}</p>
                            </div>
                            <button 
                                onclick="toggleDescription(this)"
                                class="description-button"
                            >
                                Deskripsi
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="mt-8 text-center text-red-500">Film tidak ditemukan.</p>
            @endif
        @else
            @if ($movies && count($movies) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8">
                    @foreach ($movies as $movie)
                        <div class="movie-card bg-gray-800 rounded-lg overflow-hidden shadow-lg relative">
                            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}">
                            <div class="movie-description p-4">
                                <h2 class="text-xl font-semibold mb-2">{{ $movie['title'] }}</h2>
                                <p><strong>Release Date:</strong> {{ $movie['release_date'] }}</p>
                                <div class="rating">
                                    @php
                                        $rating = $movie['vote_average'];
                                        $fullStars = floor($rating / 2);
                                        $halfStar = ($rating / 2) - $fullStars >= 0.5;
                                    @endphp
                                    @for ($i = 0; $i < 5; $i++)
                                        <span class="{{ $i < $fullStars ? '' : ($i === $fullStars && $halfStar ? 'empty' : 'empty') }}">
                                            {{ $i < $fullStars || ($i === $fullStars && $halfStar) ? '★' : '☆' }}
                                        </span>
                                    @endfor
                                    <span class="ml-2">({{ $rating }}/10)</span>
                                </div>
                                <p>{{ $movie['overview'] }}</p>
                            </div>
                            <button 
                                onclick="toggleDescription(this)"
                                class="description-button"
                            >
                                Deskripsi
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="mt-8 text-center text-red-500">Film tidak ditemukan.</p>
            @endif
        @endif
    </div>

    <script>
        document.getElementById('search-input').addEventListener('input', function() {
            document.getElementById('search-form').submit();
        });

        document.getElementById('genre-select').addEventListener('change', function() {
            document.getElementById('search-form').submit();
        });

        function toggleDescription(button) {
            const cards = document.querySelectorAll('.movie-card');
            cards.forEach(card => {
                if (card !== button.closest('.movie-card')) {
                    card.classList.remove('active');
                }
            });
            button.closest('.movie-card').classList.toggle('active');
        }
    </script>
</body>
</html>
