pipeline {
    agent any

    stages {
        stage('Pull du code') {
            steps {
                echo 'Récupération du code depuis GitHub...'
                // Utilise */ pour être sûr que Jenkins trouve la branche distante
                git branch: 'diop_moustapha_burger', 
                    url: 'https://github.com/Moustapha-diop/isi_burger.git'
            }
        }

        stage('Installation des dépendances Laravel') {
            steps {
                echo 'Installation des dépendances avec Laragon...'
                // On lance composer via PHP directement
                bat 'C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\php.exe C:\\laragon\\bin\\composer\\composer.phar install --no-interaction --prefer-dist'
                
                echo 'Configuration de l environnement...'
                // "copy" au lieu de "cp" pour Windows
                bat 'copy .env.example .env'
                
                echo 'Génération de la clé...'
                // Espace ajouté entre php.exe et artisan
                bat 'C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\php.exe artisan key:generate'
            }
        }

        stage('Création image Docker') {
            steps {
                echo 'Construction de l image Docker...'
                // Assure-toi que Docker Desktop est lancé sur ton PC !
                bat 'docker build -t examen-burger:latest .'
            }
        }
    }

    post {
        success { echo 'Pipeline terminé avec succès !' }
        failure { echo 'Erreur dans le pipeline.' }
    }
}