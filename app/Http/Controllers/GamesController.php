<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GamesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Sample games data - ready to be replaced with database queries
        $games = [
            [
                'id' => 1,
                'name' => 'Code Quest',
                'description' => 'Solve coding challenges to progress through quests',
                'category' => 'Coding',
                'difficulty' => 'Intermediate',
                'players' => 2450,
                'rating' => 4.8,
                'thumbnail' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=400&h=300&fit=crop',
                'status' => 'playing',
                'progress' => 65,
                'timeSpent' => '4h 32m',
                'badge' => '🎮',
            ],
            [
                'id' => 2,
                'name' => 'Algorithm Arena',
                'description' => 'Battle with algorithms in real-time competitions',
                'category' => 'Algorithms',
                'difficulty' => 'Advanced',
                'players' => 1820,
                'rating' => 4.6,
                'thumbnail' => 'https://images.unsplash.com/photo-1516534775068-bb57e39c146f?w=400&h=300&fit=crop',
                'status' => 'completed',
                'progress' => 100,
                'timeSpent' => '12h 15m',
                'badge' => '⚡',
            ],
            [
                'id' => 3,
                'name' => 'Web Dev Maze',
                'description' => 'Navigate through web development challenges',
                'category' => 'Web Development',
                'difficulty' => 'Beginner',
                'players' => 3200,
                'rating' => 4.9,
                'thumbnail' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=400&h=300&fit=crop',
                'status' => 'not_started',
                'progress' => 0,
                'timeSpent' => '0h 0m',
                'badge' => '🌐',
            ],
            [
                'id' => 4,
                'name' => 'Database Dungeons',
                'description' => 'Explore the depths of database optimization',
                'category' => 'Databases',
                'difficulty' => 'Advanced',
                'players' => 1450,
                'rating' => 4.5,
                'thumbnail' => 'https://images.unsplash.com/photo-1516534775068-bb57e39c146f?w=400&h=300&fit=crop',
                'status' => 'not_started',
                'progress' => 0,
                'timeSpent' => '0h 0m',
                'badge' => '🗄️',
            ],
            [
                'id' => 5,
                'name' => 'Design Dungeon',
                'description' => 'Master UI/UX design principles through interactive games',
                'category' => 'Design',
                'difficulty' => 'Intermediate',
                'players' => 2100,
                'rating' => 4.7,
                'thumbnail' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=400&h=300&fit=crop',
                'status' => 'playing',
                'progress' => 42,
                'timeSpent' => '3h 20m',
                'badge' => '🎨',
            ],
            [
                'id' => 6,
                'name' => 'Cloud Climber',
                'description' => 'Scale the cloud computing mountains',
                'category' => 'Cloud',
                'difficulty' => 'Advanced',
                'players' => 980,
                'rating' => 4.4,
                'thumbnail' => 'https://images.unsplash.com/photo-1516534775068-bb57e39c146f?w=400&h=300&fit=crop',
                'status' => 'not_started',
                'progress' => 0,
                'timeSpent' => '0h 0m',
                'badge' => '☁️',
            ],
        ];

        // Calculate stats
        $stats = [
            'totalGames' => count($games),
            'gamesPlaying' => count(array_filter($games, fn ($g) => $g['status'] === 'playing')),
            'gamesCompleted' => count(array_filter($games, fn ($g) => $g['status'] === 'completed')),
            'averageRating' => array_sum(array_map(fn ($g) => $g['rating'], $games)) / count($games),
            'totalTimePlayed' => '19h 47m',
            'averageProgress' => (int) (array_sum(array_map(fn ($g) => $g['progress'], $games)) / count($games)),
        ];

        return Inertia::render('Games', [
            'games' => $games,
            'stats' => $stats,
        ]);
    }
}
