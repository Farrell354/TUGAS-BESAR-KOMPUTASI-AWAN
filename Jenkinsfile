pipeline {
    agent any

    stages {
        // TAHAP 1: Ambil Kodingan dari GitHub
        stage('1. Checkout Code') {
            steps {
                // Ganti URL ini dengan URL GitHub kamu
                git branch: 'main', url: 'https://github.com/Farrell354/TUGAS-BESAR-KOMPUTASI-AWAN.git'
            }
        }

        // TAHAP 2: Siapkan Dapur (Install Library)
        stage('2. Install Dependencies') {
            steps {
                // Install Library PHP (Laravel)
                // PENTING: Pastikan 'composer' sudah bisa jalan di terminal Jenkins/Laptop kamu
                bat 'composer install --no-interaction --prefer-dist'
                
                // Install Library CSS/JS (Node.js)
                // PENTING: Pastikan 'npm' sudah terinstall
                bat 'npm install'
            }
        }

        stage('3. Setup Environment') {
        steps {
            bat 'copy .env.example .env'
            bat 'php artisan key:generate'
            bat 'php artisan migrate --force'
            }
        }

        // TAHAP 4: Masak Aset (Build CSS/JS)
        stage('4. Build Assets') {
            steps {
                // Cek apakah ada error di file CSS/JS kamu
                bat 'npm run build'
            }
        }

        // TAHAP 5: Ujian (Unit Testing)
        stage('5. Run Tests') {
            steps {
                // Jalankan test bawaan Laravel
                // Kalau ada fitur yang error, dia bakal lapor di sini
                bat 'php artisan test'
            }
        }
    }
}
