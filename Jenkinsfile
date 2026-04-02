pipeline {
    agent any

    environment {
        DOCKER_USER = 'taphadiopdev'
        IMAGE_NAME  = 'isi_burger'
        DOCKER_HUB_ID = 'docker-hub-creds'
    }

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
                bat 'C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\php.exe C:\\laragon\\bin\\composer\\composer.phar install --no-interaction --prefer-dist'
                bat 'if not exist .env copy .env.example .env'
                bat 'C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\php.exe artisan key:generate'
            }
        }

        stage('Création image Docker') {
            steps {
                echo 'Construction de l image...'
                bat "docker build -t ${DOCKER_USER}/${IMAGE_NAME}:latest ."
            }
        }

        stage('Push vers Docker Hub') {
            steps {
                script {
                    withCredentials([usernamePassword(credentialsId: "${DOCKER_HUB_ID}", 
                                     passwordVariable: 'DOCKER_PASSWORD', 
                                     usernameVariable: 'DOCKER_USERNAME')]) {
                        bat "docker login -u %DOCKER_USERNAME% -p %DOCKER_PASSWORD%"
                        bat "docker push ${DOCKER_USER}/${IMAGE_NAME}:latest"
                    }
                }
            }
        }
        
        stage('Déploiement Local') {
            steps {
                echo 'Lancement avec montage du volume pour les images...'
                bat "docker stop ${IMAGE_NAME}-container || ver > nul"
                bat "docker rm ${IMAGE_NAME}-container || ver > nul"
                
                // AJOUT : -v permet de lier tes images locales au conteneur
                // Remplace C:/Chemin/Vers/isi_burger par ton vrai chemin local
                bat """
                docker run -d -p 8085:80 --name ${IMAGE_NAME}-container ^
                -v C:/laragon/www/isi_burger/storage/app/public:/var/www/html/storage/app/public ^
                -e DB_CONNECTION=pgsql ^
                -e DB_HOST=host.docker.internal ^
                -e DB_PORT=5432 ^
                -e DB_DATABASE=ExamenLaravel2 ^
                -e DB_USERNAME=postgres ^
                -e DB_PASSWORD=passer ^
                ${DOCKER_USER}/${IMAGE_NAME}:latest
                """
                
                echo "Lien symbolique pour le stockage..."
                bat "docker exec ${IMAGE_NAME}-container php artisan storage:link"
            }
        }
    }

    post {
        success { echo "Succès ! App disponible sur http://localhost:8085" }
        failure { echo "Échec du build. Vérifie les logs." }
    }
}