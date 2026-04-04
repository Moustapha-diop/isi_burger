<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Gestionnaire;
use App\Models\Categorie;
use App\Models\Burger;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création des Catégories
        $catClassic = Categorie::create(['libelle' => 'Classique']);
        $catSpecial = Categorie::create(['libelle' => 'Spécial Chef']);

        // 2. Création des Burgers
        Burger::create([
            'nom' => 'Cheeseburger',
            'prix' => 2500,
            'description' => 'Pain brioché, steak haché, cheddar, cornichons.',
            'stock' => 50,
            'archive' => false,
            'categorie_id' => $catClassic->id
        ]);

        Burger::create([
            'nom' => 'Le Gourmet',
            'prix' => 4500,
            'description' => 'Bœuf d\'exception, sauce truffe, oignons confits.',
            'stock' => 20,
            'archive' => false,
            'categorie_id' => $catSpecial->id
        ]);

        // 3. Création d'un Gestionnaire    
        $userAdmin = User::create([
            'name' => 'Admin Burger',
            'email' => 'admin@burger.sn',
            'password' => Hash::make('password'),
            'role' => 'gestionnaire',
        ]);

        Gestionnaire::create([
            'user_id' => $userAdmin->id,
            'matricule' => 'MAT-' . now()->year . '-001'
        ]);
        

        // 4. Création d'un Client
        $userClient = User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        Client::create([
            'user_id' => $userClient->id,
            'adresse' => 'Dakar, Plateau',
            'telephone' => '771234567'
        ]);
    }
}