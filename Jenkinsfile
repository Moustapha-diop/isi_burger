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
                    // 1. Arrêt des anciens conteneurs pour repartir sur du propre
                    bat "docker-compose down"
                    
                    // 2. Lancement des nouveaux conteneurs avec la nouvelle image
                    bat "docker-compose up -d"
                    
                    // 3. Pause de sécurité pour laisser les services démarrer
                    sleep 10
                    
                    // 4. NETTOYAGE CRITIQUE : Supprime le faux dossier public/storage s'il existe
                    // C'est cette ligne qui évite l'erreur "File exists" que tu as eue
                    bat "docker exec examenlaravel3-app-1 rm -rf public/storage"
                    
                    // 5. CRÉATION DU LIEN : On recrée le lien symbolique proprement
                    bat "docker exec examenlaravel3-app-1 php artisan storage:link"
                    
                    // 6. PERMISSIONS : On donne les droits d'accès aux images (burgers et factures)
                    bat "docker exec examenlaravel3-app-1 chown -R www-data:www-data storage public/storage"
                    bat "docker exec examenlaravel3-app-1 chmod -R 775 storage public/storage"
                    
                    // 7. OPTIONNEL : Si tu as besoin de rafraîchir la base de données
                    // bat "docker exec examenlaravel3-app-1 php artisan migrate --force"
                }
            }
        }
    }

    post {
        success {
            echo "Déploiement réussi ! Vérifie tes burgers sur ton lien ngrok."
        }
        failure {
            echo "Le build a échoué. Vérifie les logs de la console Jenkins."
        }
    }
}