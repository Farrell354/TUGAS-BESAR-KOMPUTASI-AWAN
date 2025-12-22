pipeline {
    agent any

    environment {
        // Mengarahkan Laravel untuk menggunakan SQLite di dalam memori/file sementara
        // agar tidak terjadi error "Connection Refused" dari MySQL
        DB_CONNECTION = 'sqlite'
        DB_DATABASE = 'database/database.sqlite'
    }

    stages {
        stage('1. Checkout Code') {
            steps {
                git branch: 'main', url: 'https://github.com/Farrell354/TUGAS-BESAR-KOMPUTASI-AWAN.git'
            }
        }

        stage('2. Install Dependencies') {
            steps {
                bat 'composer install --no-interaction --prefer-dist'
                bat 'npm install'
            }
        }

        stage('3. Setup Environment') {
            steps {
                bat 'copy .env.example .env'
                bat 'php artisan key:generate'
                // Membuat file database kosong untuk SQLite
                bat 'if not exist "database\\database.sqlite" type nul > database\\database.sqlite'
                bat 'php artisan migrate --force'
            }
        }

        stage('4. Build Assets') {
            steps {
                bat 'npm run build'
            }
        }

        stage('5. Run Tests') {
            steps {
                // Menjalankan test dengan database bersih
                bat 'php artisan test'
            }
        }
    }
}
