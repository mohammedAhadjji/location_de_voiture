pipeline {
    agent any
    stages {
        stage('Checkout') {
            steps {
                // Récupérer le code du dépôt
                git 'https://github.com/mohammedAhadjji/location_de_voiture.git'
            }
        }
        stage('Install Dependencies') {
            steps {
                // Installer les dépendances avec Composer
                sh 'composer install'
            }
        }
        stage('Run Tests') {
            steps {
                // Exécuter les tests PHPUnit
                sh 'php bin/phpunit'
            }
        }
    }
}

