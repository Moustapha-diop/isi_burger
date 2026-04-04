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
                // Récupération de ta branche spécifique
                git branch: 'diop_moustapha_burger', 
                    url: 'https://github.com/Moustapha-diop/isi_burger.git'
            }
        }

        stage('Build & Push Docker') {
            steps {
                script {
                    // Construction de l'image (le .dockerignore doit exclure public/storage)
                    bat "docker build -t ${DOCKER_USER}/${IMAGE_NAME}:latest ."
                    
                    // Connexion et Push sur Docker Hub
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
                    bat "docker-compose down"
                    bat "docker-compose up -d"
                    sleep 10
                    
                    // 1. On force la création du lien avec l'option --force de Laravel
                    // Cela règle l'erreur "The link already exists"
                    bat "docker exec examenlaravel3-app-1 php artisan storage:link --force"
                    
                    // 2. On change les droits en ignorant les erreurs sur les fichiers verrouillés (.gitignore)
                    // Le "|| ver > nul" permet de dire à Jenkins : "Même s'il y a une erreur de permission, continue !"
                    bat "docker exec -u root examenlaravel3-app-1 chown -R www-data:www-data storage public/storage || ver > nul"
                    bat "docker exec -u root examenlaravel3-app-1 chmod -R 775 storage public/storage || ver > nul"
                }
            }
        }
    }

    post {
        success {
            echo "Déploiement réussie ! Vérifie tes burgers sur ton lien ngrok."
        }
        failure {
            echo "Le build a échoué. Vérifie les logs de la console Jenkins."
        }
    }
}