pipeline {
    agent any

    environment {
        // Remplace 'ton_pseudo' par ton vrai nom d'utilisateur Docker Hub
        DOCKER_USER = 'taphadiopdev'
        IMAGE_NAME  = 'isi_burger'
        DOCKER_HUB_ID = 'docker-hub-creds' // L'ID des identifiants créés dans Jenkins
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
                echo 'Installation des dépendances avec Laragon...'
                bat 'C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\php.exe C:\\laragon\\bin\\composer\\composer.phar install --no-interaction --prefer-dist'
                
                echo 'Configuration de l environnement...'
                bat 'copy .env.example .env'
                
                echo 'Génération de la clé...'
                bat 'C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\php.exe artisan key:generate'
            }
        }

        stage('Création image Docker') {
            steps {
                echo 'Construction de l image Docker locale...'
                // On build l'image avec le tag Docker Hub directement
                bat "docker build -t ${DOCKER_USER}/${IMAGE_NAME}:latest ."
            }
        }

        stage('Push vers Docker Hub') {
            steps {
                script {
                    echo 'Connexion et envoi vers Docker Hub...'
                    // Cette commande gère le docker login/push de manière sécurisée
                    withDockerRegistry([credentialsId: "${DOCKER_HUB_ID}", url: '']) {
                        bat "docker push ${DOCKER_USER}/${IMAGE_NAME}:latest"
                    }
                }
            }
        }
        
        stage('Déploiement Local') {
            steps {
                echo 'Lancement du conteneur pour test final...'
                // Arrête l'ancien s'il existe, puis lance le nouveau
                bat "docker stop ${IMAGE_NAME}-container || ver > nul"
                bat "docker rm ${IMAGE_NAME}-container || ver > nul"
                bat "docker run -d -p 8085:80 --name ${IMAGE_NAME}-container ${DOCKER_USER}/${IMAGE_NAME}:latest"
                echo "Application déployée sur http://localhost:8085"
            }
        }
    }

    post {
        success { 
            echo "Félicitations Moustapha ! L'image est sur Docker Hub et l'app tourne localement." 
        }
        failure { 
            echo 'Erreur dans le pipeline. Vérifie les logs de la console.' 
        }
    }
}