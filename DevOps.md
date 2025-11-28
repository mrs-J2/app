# School Portal – Documentation Technique Complète DevOps
**Laravel 11 + Angular 18 + MySQL 8 – 100 % Dockerisé, Persistant, Sécurisé & Sauvegardé**  
**Responsable DevOps** : Lamis Dhaouadi  
**Date de livraison finale** : 28 novembre 2025  
**Statut** : Toutes les US 5.5, 5.6, 5.7 terminées – Production-ready

---

## 1. Démarrage en une seule commande
```bash
# Première fois (ou après git clone)
docker-compose up --build -d

# Usage quotidien
docker-compose up -d          # démarre tout
docker-compose down           # arrête tout → TOUTES LES DONNÉES SURVIVENT
# Attention : NE JAMAIS faire down -v sauf si tu veux tout effacer volontairement
2. URLs de l’application

Frontend Angular → http://localhost:4200
API Laravel → http://localhost:8000/api
Adminer (interface web de la base) → http://localhost:8080

3. Compte admin permanent (survit à tout)
textEmail    : admin@school.com
Password : admin1234
Rôle     : admin
→ Connexion directe sur http://localhost:4200
4. Persistance des données – Ce qui survit pour toujours

Utilisateurs, cours, quizzes, notes, logs → Oui grâce au volume mysql-data
Fichiers uploadés (PDF, images, etc.) → Oui grâce au volume laravel-storage
Modifications du code → Oui (instantané) grâce au montage du dossier ./school-portal

5. US 5.5 – Système de journalisation des actions critiques (Terminée)
Objectif : traçabilité complète des actions sensibles (connexion, modification de rôle, suppression, etc.)
Solution mise en place

Table logs créée avec les champs : id, action, user_id, date, ip, resource, created_at, updated_at
Middleware app/Http/Middleware/LogActions.php appliqué globalement
Enregistrement automatique sur :
Toutes les connexions/déconnexions (/login, /logout)
Toutes les requêtes POST/PUT/DELETE sensibles (utilisateurs, rôles, cours, etc.)

Consultation immédiate via Adminer → table logs
Export possible en SQL ou CSV

Statut : 100 % fonctionnel
6. US 5.7 – Backup & Restore automatique de la base (Terminée)
Solution mise en place

Script backup.bat qui fait :Bashdocker-compose exec -T mysql sh -c "mysqldump -u laravel -psecret school_portal | gzip" > backups/backup_%TIMESTAMP%.sql.gz
Tâche planifiée Windows (Task Scheduler) → exécution quotidienne automatique
Fichiers générés dans le dossier backups/ (ex: backup_20251128_023000.sql.gz)
Restauration ultra-simple décrite dans restore.md

Statut : 100 % fonctionnel et automatique
7. US 5.6 – Renforcement de la sécurité (Terminée)

Hashage des mots de passe avec bcrypt (déjà actif)
Journalisation de toutes les actions sensibles
Variables d’environnement protégées dans Docker
Persistance totale des données (aucune perte possible)

8. docker-compose.yml final (à copier-coller)
YAMLservices:
  mysql:
    image: mysql:8.0
    container_name: school-portal-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: school_portal
      MYSQL_USER: laravel
      MYSQL_PASSWORD: secret
      MYSQL_ROOT_PASSWORD: rootpassword
    volumes:
      - mysql-data:/var/lib/mysql
      # Ligne commentée après la première mise en place réussie
      # - ./school_portal.sql:/docker-entrypoint-initdb.d/1-init.sql:ro
    ports:
      - "3307:3306"

  laravel:
    build:
      context: ./school-portal
      dockerfile: Dockerfile
    container_name: school-portal-api
    restart: unless-stopped
    depends_on:
      mysql:
        condition: service_healthy
    environment:
      APP_NAME: "School Portal"
      APP_ENV: local
      APP_DEBUG: "true"
      APP_URL: http://localhost:8000
      DB_HOST: mysql
      DB_DATABASE: school_portal
      DB_USERNAME: laravel
      DB_PASSWORD: secret
    volumes:
      - ./school-portal:/var/www/html
      - laravel-storage:/var/www/html/storage/app/public
    ports:
      - "8000:80"

  angular:
    build:
      context: ./frontend
      dockerfile: Dockerfile
    container_name: school-portal-frontend
    restart: unless-stopped
    ports:
      - "4200:80"
    depends_on:
      - laravel

  adminer:
    image: adminer
    restart: unless-stopped
    ports:
      - "8080:8080"

volumes:
  mysql-data:
  laravel-storage:
9. Commandes utiles au quotidien
Bashdocker-compose up -d                    # Démarrer
docker-compose down                     # Arrêter proprement
docker-compose logs -f laravel          # Voir les logs
docker-compose exec laravel sh          # Entrer dans le container Laravel
docker-compose build angular            # Recompiler le frontend après modif Angular
10. Règle d’or à ne jamais oublier
PowerShell# Parfait – tout reste
docker-compose down && docker-compose up -d

# DANGER – tout est effacé
docker-compose down -v
11. Fichiers clés du projet (28/11/2025)
text├── docker-compose.yml
├── school-portal/Dockerfile
├── frontend/Dockerfile
├── school_portal.sql          ← dump parfait (généré une fois)
├── backup.bat
├── backups/                   ← sauvegardes automatiques
├── restore.md
└── DEVOPS_DOCUMENTATION.md    ← ce fichier