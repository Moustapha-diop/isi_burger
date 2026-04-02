pipeline {
    agent any

    stages {

        stage('Pull du code') {
            steps {
                echo 'Récupération du code depuis GitHub...'
                git branch: 'diop_moustapha_burger',
                    url: 'https://github.com/Moustapha-diop/isi_burger.git'
            }
        }

        stage('Installation des dépendances Laravel') {
            steps {
                echo 'Installation des dépendances...'
                bat 'C:\\laragon\\bin\\composer\\composer.bat install --no-interaction --prefer-dist'
                bat 'cp .env.example .env'
                bat 'php artisan key:generate'
            }
        }

        stage('Création image Docker') {
            steps {
                echo 'Construction de l image Docker...'
                bat 'docker build -t examen-burger:latest .'
            }
        }

    }

    post {
        success { echo 'Pipeline terminé avec succès !' }
        failure { echo 'Erreur dans le pipeline.' }
    }
}