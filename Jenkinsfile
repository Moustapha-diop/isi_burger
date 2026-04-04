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
                    // 1. On nettoie les anciens conteneurs
                    bat "docker-compose down"
                    
                    // 2. On relance avec la nouvelle image buildée
                    bat "docker-compose up -d"
                    
                    // 3. On attend que le conteneur soit bien démarré
                    sleep 5
                    
                    // 4. L'AUTOMATISATION : On crée le lien ET on donne les droits
                    // C'est cette ligne qui remplit "virtuellement" ton dossier public
                    bat "docker exec examenlaravel3-app-1 php artisan storage:link --force"
                    bat "docker exec examenlaravel3-app-1 chmod -R 775 storage public/storage"
                }
            }
        }
    }
}