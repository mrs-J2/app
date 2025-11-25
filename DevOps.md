# School Portal – Professional DevOps Documentation
**Laravel API + Angular Frontend + MySQL – 100 % Dockerized & Persistent**  
**Status**: Fully working · Data survives normal restarts forever  
**Date**: 25 November 2025  

## One-Command Start & Stop
```bash
# First time (or after git clone)
docker-compose up --build -d

# Daily use
docker-compose up -d          # start everything
docker-compose down           # stop everything → ALL DATA SURVIVES
URLs

URLs
Service,URL
Angular Frontend , http://localhost:4200
Laravel API, http://localhost:8000/api
Adminer (DB GUI), http://localhost:8080


Ready to use admin account:
Email    : admin@school.com
Password : admin1234
Role     : admin

First-Time Setup (run only once)
docker-compose up --build -d
docker-compose exec laravel php artisan migrate --force
docker-compose exec laravel php artisan storage:link

Daily Development Commands
docker-compose up -d                # start
docker-compose down                 # stop (everything stays)
docker-compose logs -f laravel      # view backend logs
docker-compose exec laravel sh      # enter backend container

Data Persistence – What Survives Forever
Data => Survives normal restart? => Thanks to
Users, courses, quizzes, grades => Yes => mysql-data volume
Uploaded files (PDFs, images) => Yes => laravel-storage volume
Code changes => Yes (instant) => ./school-portal mount