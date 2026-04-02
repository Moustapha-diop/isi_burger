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
                echo 'Installation des dépendances avec Laragon...'
                bat 'C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\php.exe C:\\laragon\\bin\\composer\\composer.phar install --no-interaction --prefer-dist'
                
                echo 'Configuration de l environnement...'
                // On s'assure qu'un .env existe pour les commandes artisan locales
                bat 'if not exist .env copy .env.example .env'
                
                echo 'Génération de la clé...'
                bat 'C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\php.exe artisan key:generate'
                bat "C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\php.exe artisan config:clear"
            }
        }

        stage('Création image Docker') {
            steps {
                echo 'Construction de l image Docker locale...'
                // On utilise le Dockerfile que je t'ai donné précédemment
                bat "docker build -t ${DOCKER_USER}/${IMAGE_NAME}:latest ."
            }
        }

        stage('Push vers Docker Hub') {
            steps {
                script {
                    echo "Connexion et envoi vers Docker Hub..."
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
                echo 'Lancement du conteneur avec redirection vers la base Windows...'
                bat "docker stop ${IMAGE_NAME}-container || ver > nul"
                bat "docker rm ${IMAGE_NAME}-container || ver > nul"
                
                // Note le -e DB_HOST : on force l'image à regarder vers ton Windows
                bat "docker run -d -p 8085:80 --name ${IMAGE_NAME}-container -e DB_HOST=host.docker.internal ${DOCKER_USER}/${IMAGE_NAME}:latest"
                
                echo "Application déployée sur http://localhost:8085"
            }
        }
    }

    post {
        success { 
            echo "Félicitations Moustapha ! L'image est sur Docker Hub et l'app tourne sur le port 8085." 
        }
        failure { 
            echo 'Erreur dans le pipeline. Vérifie le Dockerfile ou les credentials.' 
        }
    }
}