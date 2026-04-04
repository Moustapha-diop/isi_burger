stage('Déploiement') {
            steps {
                script {
                    // 1. On nettoie les anciens conteneurs
                    bat "docker-compose down"
                    
                    // 2. On relance avec la nouvelle image buildée
                    bat "docker-compose up -d"
                    
                    // 3. On attend que le conteneur soit bien démarré
                    sleep 5
                    
                    // 4. NETTOYAGE : On supprime le dossier public/storage s'il existe
                    // C'est ce qui évite l'erreur "File exists"
                    bat "docker exec examenlaravel3-app-1 rm -rf public/storage"
                    
                    // 5. AUTOMATISATION : On recrée le lien propre
                    bat "docker exec examenlaravel3-app-1 php artisan storage:link"
                    
                    // 6. PERMISSIONS : On donne les droits pour afficher les images
                    bat "docker exec examenlaravel3-app-1 chmod -R 775 storage public/storage"
                }
            }
        }