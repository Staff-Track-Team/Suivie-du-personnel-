# 📋 Cahier des Charges Officiel : Plateforme de Gestion Centralisée Camtel

---

## Introduction

La présente plateforme de gestion centralisée constitue une réponse stratégique aux besoins de modernisation des processus internes de Camtel. Conçue comme une solution numérique intégrée, elle vise à transformer radicalement la manière dont les projets et les tâches sont pilotés au sein de l'entreprise, en offrant un environnement de travail collaboratif, sécurisé et performant.

Ce cahier des charges définit l'ensemble des spécifications fonctionnelles, techniques et opérationnelles nécessaires à la réalisation de cette plateforme stratégique pour la transformation numérique de Camtel.

---

## I. Spécifications Générales

### 1.1 Contexte et Objectifs

**Contexte organisationnel** :
Camtel, en tant qu'opérateur télécoms majeur, fait face à une croissance continue de ses projets structurants et nécessite une optimisation de ses processus de gestion pour maintenir sa compétitivité.

**Objectifs stratégiques** :
- Centraliser et standardiser la gestion de tous les projets d'entreprise
- Améliorer la visibilité et la traçabilité des activités
- Optimiser l'allocation des ressources humaines
- Renforcer la sécurité des informations sensibles
- Faciliter la prise de décision par des indicateurs pertinents

### 1.2 Périmètre Fonctionnel

**Modules principaux** :
1. **Module d'Authentification Sécurisée**
2. **Module de Gestion des Utilisateurs**
3. **Module de Gestion des Projets**
4. **Module de Gestion des Tâches**
5. **Module de Reporting et Analytics**
6. **Module de Notifications**

**Utilisateurs concernés** :
- Administrateurs système (2-3 personnes)
- Managers de projets (5-10 personnes)
- Employés opérationnels (50-200 personnes)

### 1.3 Contraintes Techniques

**Obligations technologiques** :
- Framework Laravel 11 minimum
- Base de données MySQL 8.0
- Compatible PHP 8.2+
- Interface responsive mobile-first

**Contraintes réglementaires** :
- Conformité RGPD complète
- Audit trail obligatoire
- Chiffrement des données sensibles

---

## II. Architecture Technique

### 2.1 Stack Technologique

**Backend** :
- **Framework** : Laravel 11
- **Langage** : PHP 8.2+
- **Base de données** : MySQL 8.0
- **ORM** : Eloquent
- **Queue** : Redis/Database

**Frontend** :
- **Templates** : Blade
- **CSS** : Tailwind CSS 4.0
- **JavaScript** : Alpine.js
- **Composants** : Livewire 3.6

**Infrastructure** :
- **Web Server** : Apache/Nginx
- **Cache** : Redis/Memcached
- **CDN** : Pour assets statiques
- **Email** : SMTP sécurisé

### 2.2 Architecture Logicielle

**Pattern architectural** : MVC (Model-View-Controller)
**Pattern de données** : Active Record (Eloquent)
**Pattern de présentation** : Component-based (Livewire)

**Structure des couches** :
```
┌─────────────────────────────────────┐
│           Présentation              │
│    (Blade + Tailwind + Alpine)     │
├─────────────────────────────────────┤
│           Contrôleurs               │
│      (Controllers + Middleware)     │
├─────────────────────────────────────┤
│            Services                 │
│   (Business Logic + Validation)     │
├─────────────────────────────────────┤
│             Modèles                 │
│      (Eloquent Models + Rules)      │
├─────────────────────────────────────┤
│           Base de données           │
│         (MySQL 8.0)                 │
└─────────────────────────────────────┘
```

### 2.3 Sécurité

**Mesures de sécurité** :
- Double authentification OTP obligatoire
- Protection anti-brute force (10 tentatives/minute)
- Chiffrement bcrypt pour mots de passe
- Validation CSRF sur toutes les requêtes
- Audit trail complet des actions

**Gestion des permissions** :
- Rôles définis : Administrateur, Employé
- Contrôle d'accès granulaire
- Validation des statuts de compte

---

## III. Spécifications Fonctionnelles Détaillées

### 3.1 Module d'Authentification

**Fonctionnalités** :
- Connexion sécurisée avec email/mot de passe
- Génération OTP 6 chiffres par email
- Validation OTP avec durée de validité 10 minutes
- Réinitialisation mot de passe via OTP
- Limitation tentatives de connexion

**Flux d'authentification** :
```
1. Utilisateur saisit email + mot de passe
2. Système vérifie identifiants
3. Si valides, génération OTP et envoi email
4. Utilisateur saisit OTP reçu
5. Si OTP valide, création session et redirection dashboard
```

**Messages d'erreur** :
- Identifiants incorrects
- Compte inactif/suspendu
- OTP invalide/expiré
- Trop de tentatives

### 3.2 Module de Gestion des Utilisateurs

**CRUD Utilisateurs** :
- **Création** : Formulaire avec nom, email, département, poste
- **Lecture** : Liste paginée avec filtres (statut, département)
- **Modification** : Mise à jour informations personnelles
- **Suppression** : Soft delete avec conservation historique

**Gestion des statuts** :
- **Actif** : Accès complet autorisé
- **Inactif** : Connexion bloquée temporairement
- **Suspendu** : Accès révoqué administrativement

**Fonctionnalités avancées** :
- Génération automatique matricule unique
- Upload photo de profil
- Export PDF/Excel des listes
- Historique des modifications

**Validation des données** :
- Email unique et format valide
- Matricule unique généré automatiquement
- Champs obligatoires selon rôle

### 3.3 Module de Gestion des Projets

**Création de projet** :
- Code unique généré automatiquement
- Nom, description, budget
- Dates de début/fin
- Priorité (Basse/Moyenne/Haute/Urgente)
- Statut (Planification/Actif/En pause/Terminé/Annulé)

**Suivi des projets** :
- Calcul automatique progression basé sur tâches
- Affichage jours restants ou retard
- Visualisation graphique de l'avancement

**Gestion des tâches associées** :
- Liste des tâches du projet
- Assignation aux employés
- Suivi des statuts individuels

**Rapports et exports** :
- PDF détaillé du projet
- Liste des tâches avec statuts
- Graphiques de progression

### 3.4 Module de Gestion des Tâches

**Création et assignation** :
- Titre et description détaillée
- Association à un projet
- Assignation à un employé
- Priorité et date d'échéance
- Statut initial (À faire)

**Cycle de vie des tâches** :
```
À faire → En cours → En révision → Terminé
```

**Suivi et audit** :
- Timeline complète des modifications
- Qui a fait quoi et quand
- Historique des changements de statut
- Journal des assignations/réassignations

**Notifications automatiques** :
- Email lors de l'assignation
- Email lors du changement de statut
- Rappels pour tâches en retard

### 3.5 Module de Reporting

**Tableaux de bord** :
- Statistiques en temps réel
- Graphiques de progression
- Indicateurs de performance clés

**Rapports disponibles** :
- Liste des employés avec statuts
- Détails des projets avec progression
- Tâches par employé/projet
- Performance individuelle

**Exports multi-formats** :
- PDF avec mise en forme professionnelle
- Excel pour analyses complémentaires
- Filtrage personnalisable

### 3.6 Module de Notifications

**Types de notifications** :
- Bienvenue nouvel employé
- Assignation de tâche
- Changement de statut de tâche
- Réinitialisation mot de passe
- Alertes sécurité

**Canaux de distribution** :
- Email principal (obligatoire)
- Notifications dans l'interface (future)

**Templates d'emails** :
- Design responsive
- Branding Camtel
- Informations pertinentes
- Actions directes possibles

---

## IV. Spécifications Techniques

### 4.1 Base de Données

**Schéma relationnel** :

```sql
-- Table des utilisateurs
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT 0,
    status ENUM('actif', 'inactif', 'suspendu') DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- Informations employés
CREATE TABLE employee_infos (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNIQUE NOT NULL,
    matricule VARCHAR(20) UNIQUE NOT NULL,
    department VARCHAR(100),
    position VARCHAR(100),
    city VARCHAR(100),
    address TEXT,
    hire_date DATE,
    phone VARCHAR(20),
    photo_path VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Projets
CREATE TABLE projects (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    budget DECIMAL(12,2),
    start_date DATE,
    end_date DATE,
    priority ENUM('basse', 'moyenne', 'haute', 'urgente') DEFAULT 'moyenne',
    status ENUM('planification', 'actif', 'en_pause', 'termine', 'annule') DEFAULT 'planification',
    progress DECIMAL(5,2) DEFAULT 0.00,
    created_by BIGINT NOT NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Tâches
CREATE TABLE tasks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    project_id BIGINT NOT NULL,
    assigned_to BIGINT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    priority ENUM('basse', 'moyenne', 'haute', 'urgente') DEFAULT 'moyenne',
    status ENUM('a_faire', 'en_cours', 'en_revision', 'termine') DEFAULT 'a_faire',
    due_date DATE,
    completed_at TIMESTAMP NULL,
    created_by BIGINT NOT NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Audit des tâches
CREATE TABLE task_audits (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    task_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    action VARCHAR(50) NOT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Codes OTP
CREATE TABLE otps (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    code VARCHAR(255) NOT NULL, -- Hashé
    type ENUM('login', 'password_reset') NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

**Indexation optimisée** :
- Index sur clés étrangères
- Index composites pour recherches fréquentes
- Index unique sur contraintes métier

### 4.2 API REST

**Endpoints principaux** :

```
# Authentification
POST /api/login                    # Connexion
POST /api/verify-otp              # Validation OTP
POST /api/password-reset          # Réinitialisation

# Utilisateurs (Admin)
GET    /api/users                 # Liste utilisateurs
POST   /api/users                 # Créer utilisateur
GET    /api/users/{id}            # Détail utilisateur
PUT    /api/users/{id}            # Modifier utilisateur
DELETE /api/users/{id}            # Supprimer utilisateur

# Projets
GET    /api/projects              # Liste projets
POST   /api/projects              # Créer projet
GET    /api/projects/{id}         # Détail projet
PUT    /api/projects/{id}         # Modifier projet
DELETE /api/projects/{id}         # Supprimer projet

# Tâches
GET    /api/tasks                 # Liste tâches
POST   /api/tasks                 # Créer tâche
GET    /api/tasks/{id}            # Détail tâche
PUT    /api/tasks/{id}            # Modifier tâche
PATCH  /api/tasks/{id}/status     # Changer statut
DELETE /api/tasks/{id}            # Supprimer tâche

# Exports
GET    /api/export/employees      # Export employés
GET    /api/export/projects/{id}  # Export projet
```

**Format des réponses** :
```json
{
    "success": true,
    "data": {...},
    "message": "Opération réussie",
    "timestamp": "2026-03-27T10:30:00Z"
}
```

### 4.3 Sécurité Technique

**Validation des entrées** :
- Validation Laravel Form Request
- Sanitisation automatique
- Protection XSS et CSRF

**Gestion des permissions** :
- Middleware IsAdmin pour routes admin
- Vérification statut utilisateur
- Contrôle d'accès par ressource

**Audit et logging** :
- Observers Eloquent pour modifications
- Logs système complets
- Traçabilité des actions sensibles

### 4.4 Performance

**Optimisations prévues** :
- Eager loading pour éviter N+1
- Cache Redis pour données fréquentes
- Pagination pour listes volumineuses
- Indexation base de données optimisée

**Objectifs de performance** :
- Temps de réponse < 2 secondes
- Support 100 utilisateurs simultanés
- Uptime 99.5%

---

## V. Interface Utilisateur

### 5.1 Design System

**Palette de couleurs** :
- **Primaire** : Indigo (#6366F1)
- **Secondaire** : Bleu Camtel (#0066CC)
- **Succès** : Émeraude (#10B981)
- **Attention** : Ambre (#F59E0B)
- **Danger** : Rouge (#EF4444)

**Typographie** :
- **Titres** : Inter, poids 600-700
- **Texte** : Inter, poids 400
- **Code** : JetBrains Mono

**Design Glassmorphism** :
- Fond translucide (backdrop-blur-xl)
- Bordures subtiles (border-white/20)
- Ombres douces (shadow-lg)
- Transitions fluides

### 5.2 Interfaces Principales

**Dashboard Administrateur** :
- Statistiques en temps réel
- Graphiques de progression
- Actions rapides
- Notifications système

**Dashboard Employé** :
- Tâches assignées
- Performance personnelle
- Projets actifs
- Actions rapides

**Formulaires** :
- Validation en temps réel
- Aide contextuelle
- Sauvegarde automatique
- Feedback utilisateur

### 5.3 Responsive Design

**Breakpoints** :
- Mobile : < 768px
- Tablette : 768px - 1024px
- Desktop : > 1024px

**Adaptations** :
- Navigation mobile hamburger
- Grilles flexibles
- Touch-friendly
- Performance optimisée

### 5.4 Accessibilité

**Normes WCAG 2.1 AA** :
- Contrastes respectés
- Navigation clavier
- Screen readers compatibles
- Zoom jusqu'à 200%

---

## VI. Tests et Qualité

### 6.1 Stratégie de Tests

**Tests Unitaires** :
- Couverture visée : 85%
- Tests des modèles et services
- Tests des validators
- Tests des utilitaires

**Tests d'Intégration** :
- Tests des endpoints API
- Tests des workflows
- Tests des permissions
- Tests des notifications

**Tests End-to-End** :
- Scénarios utilisateur complets
- Tests multi-navigateurs
- Tests responsive
- Tests performance

### 6.2 Qualité du Code

**Standards de codage** :
- PSR-12 pour style
- PHP 8.2 typage strict
- Documentation complète
- Revues de code obligatoires

**Outils qualité** :
- Laravel Pint pour formattage
- PHPStan pour analyse statique
- PHPUnit pour tests
- Laravel Telescope pour debug

### 6.3 Sécurité

**Tests de sécurité** :
- Tests de pénétration
- Tests d'injection
- Tests XSS
- Tests CSRF

**Audit de sécurité** :
- Revue dépendances
- Scan vulnérabilités
- Tests configuration
- Validation permissions

---

## VII. Déploiement et Maintenance

### 7.1 Environnements

**Développement** :
- Local avec Docker
- Base de données SQLite
- MailHog pour emails
- Debug activé

**Staging** :
- Réplique production
- Données anonymisées
- Tests complets
- Validation finale

**Production** :
- Infrastructure optimisée
- Sauvegardes quotidiennes
- Monitoring actif
- Logs centralisés

### 7.2 Processus de Déploiement

**CI/CD Pipeline** :
1. Code push → Tests automatiques
2. Build → Validation qualité
3. Déploiement staging → Tests UAT
4. Déploiement production → Monitoring

**Rollback Strategy** :
- Sauvegarde pré-déploiement
- Script rollback automatisé
- Communication utilisateurs
- Monitoring post-déploiement

### 7.3 Maintenance

**Monitoring** :
- Performance applicative
- Disponibilité services
- Erreurs et exceptions
- Utilisation ressources

**Sauvegardes** :
- Base de données quotidienne
- Fichiers hebdomadaires
- Configuration mensuelle
- Tests de restauration

**Mises à jour** :
- Patchs sécurité mensuels
- Mises à jour dépendances
- Évolutions trimestrielles
- Audit annuel complet

---

## VIII. Livrables

### 8.1 Livrables Techniques

**Code source** :
- Application Laravel complète
- Documentation technique
- Tests automatisés
- Scripts déploiement

**Infrastructure** :
- Configuration serveur
- Base de données
- Services externes
- Monitoring

### 8.2 Livrables Fonctionnels

**Documentation** :
- Manuel utilisateur
- Guide administrateur
- Procédures opérationnelles
- Support technique

**Formation** :
- Modules e-learning
- Sessions pratiques
- Support post-formation
- Documentation mise à jour

### 8.3 Livrables Projet

**Gestion de projet** :
- Planning détaillé
- Rapports d'avancement
- Gestion des risques
- Documentation projet

**Assurance qualité** :
- Plans de tests
- Rapports de tests
- Validation utilisateur
- Recettage officiel

---

## IX. Planning et Ressources

### 9.1 Planning Prévisionnel

**Phase 1 : Analyse et Conception (2 mois)**
- Analyse besoins détaillée
- Conception architecture
- Modélisation base de données
- Maquettage UI/UX

**Phase 2 : Développement (6 mois)**
- Backend Laravel
- Frontend responsive
- Intégration API
- Tests unitaires

**Phase 3 : Tests et Intégration (1.5 mois)**
- Tests intégration
- Tests utilisateur
- Performance
- Sécurité

**Phase 4 : Déploiement et Formation (0.5 mois)**
- Mise en production
- Formation équipes
- Documentation
- Support initial

### 9.2 Ressources Humaines

**Équipe de développement (6 personnes)** :
- 1 Chef de projet / Architecte
- 2 Développeurs Backend Laravel
- 2 Développeurs Frontend
- 1 Spécialiste QA/Tests

**Compétences requises** :
- Laravel 11 avancé
- PHP 8.2+ expert
- MySQL optimisation
- Tailwind CSS moderne
- Tests automatisés

### 9.3 Budget Prévisionnel

**Coûts de développement** :
- Salaires équipe : 51.9 personnes-mois
- Infrastructure : 5% du total
- Formation : 3% du total
- Contingence : 10% du total

---

## X. Risques et Mitigation

### 10.1 Risques Techniques

**Complexité architecture** :
- **Impact** : Retard développement
- **Mitigation** : PoC préliminaire, expertise externe si besoin

**Performance sous charge** :
- **Impact** : Expérience utilisateur dégradée
- **Mitigation** : Tests charge, optimisation progressive

**Sécurité** :
- **Impact** : Vulnérabilités critiques
- **Mitigation** : Audit sécurité régulier, bonnes pratiques

### 10.2 Risques Projet

**Délais optimistes** :
- **Impact** : Retard livraison
- **Mitigation** : Buffer temporel, suivi hebdomadaire

**Ressources insuffisantes** :
- **Impact** : Qualité compromise
- **Mitigation** : Plan de recrutement, externalisation partielle

**Changements périmètre** :
- **Impact** : Dérapage budget/délais
- **Mitigation** : Gestion formelle changements, validation comité

### 10.3 Risques Métier

**Adoption utilisateurs** :
- **Impact** : Plateforme sous-utilisée
- **Mitigation** : Formation continue, support dédié

**Résistance au changement** :
- **Impact** : Productivité réduite
- **Mitigation** : Communication proactive, accompagnement

**Conformité réglementaire** :
- **Impact** : Sanctions, blocage
- **Mitigation** : Expertise RGPD, audit conformité

---

## Conclusion

La plateforme de gestion centralisée de Camtel représente un investissement stratégique majeur dans la transformation numérique de l'entreprise. Avec une architecture technique robuste, des fonctionnalités complètes et une approche sécurité renforcée, cette solution positionnera Camtel comme une entreprise moderne, agile et performante.

Le succès de ce projet repose sur une exécution rigoureuse du planning, une gestion proactive des risques et une collaboration étroite entre les équipes techniques et les utilisateurs métier. L'impact attendu sur la productivité, la visibilité et la prise de décision justifie pleinement l'investissement requis.

La plateforme évoluera continuellement pour s'adapter aux besoins changeants de l'entreprise et intégrer les innovations technologiques futures, assurant ainsi sa pertinence et sa valeur sur le long terme.

---

*Document validé le 27 mars 2026*
*Direction de la Transition Numérique - Camtel*
*Équipe de Développement et Architecture*
