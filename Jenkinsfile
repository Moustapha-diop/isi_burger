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
                script {
                    // Force la suppression des conteneurs spécifiques par leur nom pour éviter les conflits
                    bat "docker rm -f examenlaravel3-app-1 examenlaravel3-mailpit-1 || ver > nul"
                    
                    // Relance proprement avec docker-compose
                    bat "docker-compose up -d"
                    
                    // Attendre quelques secondes que le conteneur soit prêt
                    sleep 5
                    
                    // Correction des permissions et liens
                    bat "docker exec examenlaravel3-app-1 chown -R www-data:www-data /var/www/html/storage"
                    bat "docker exec examenlaravel3-app-1 php artisan storage:link --force"
                }
            }
        }
    }
}