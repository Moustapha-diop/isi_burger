pipeline {
    agent any

    stages {

        stage('Pull du code') {
            steps {
                echo 'Récupération du code depuis GitHub...'
                git branch: 'diallo_mamadou_burger',
                    url: 'https://github.com/VOTRE_USERNAME/ExamenLaravel.git'
            }
        }

        stage('Installation des dépendances Laravel') {
            steps {
                echo 'Installation des dépendances...'
                sh 'composer install --no-interaction --prefer-dist'
                sh 'cp .env.example .env'
                sh 'php artisan key:generate'
            }
        }

        stage('Création image Docker') {
            steps {
                echo 'Construction de l image Docker...'
                sh 'docker build -t examen-burger:latest .'
            }
        }

    }

    post {
        success { echo 'Pipeline terminé avec succès !' }
        failure { echo 'Erreur dans le pipeline.' }
    }
}