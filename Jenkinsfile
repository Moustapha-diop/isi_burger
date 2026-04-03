pipeline {
    agent any

    environment {
        DOCKER_USER     = 'taphadiopdev'
        IMAGE_NAME      = 'isi_burger'
        DOCKER_HUB_ID   = 'docker-hub-creds'
    }

    stages {
        stage('Pull du code') {
            steps {
                git branch: 'diop_moustapha_burger', 
                    url: 'https://github.com/Moustapha-diop/isi_burger.git'
            }
        }

        stage('Build & Push Docker') {
            steps {
                script {
                    bat "docker build -t ${DOCKER_USER}/${IMAGE_NAME}:latest ."
                    withCredentials([usernamePassword(credentialsId: "${DOCKER_HUB_ID}", passwordVariable: 'PASS', usernameVariable: 'USER')]) {
                        bat "docker login -u %USER% -p %PASS%"
                        bat "docker push ${DOCKER_USER}/${IMAGE_NAME}:latest"
                    }
                }
            }
        }

        stage('Déploiement') {
            steps {
                bat "docker-compose down || ver > nul"
                bat "docker-compose up -d"
                // Correction finale des permissions post-déploiement
                bat "docker exec examenlaravel3-app-1 chown -R www-data:www-data /var/www/html/storage"
                bat "docker exec examenlaravel3-app-1 php artisan storage:link --force"
            }
        }
    }
}