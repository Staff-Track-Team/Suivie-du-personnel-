# 📄 Cahier des Charges Complet : Solution de Gestion des Ressources Humaines

## 1. Introduction
Ce document définit les spécifications techniques et fonctionnelles détaillées de la **Plateforme de Gestion des Ressources Humaines** pour les petites entreprises camerounaises. Il sert de référence pour le développement, la maintenance et l'évolution de la solution.

**Contexte économique** : Dans un contexte économique de plus en plus compétitif, les petites entreprises ressentent la nécessité de gérer efficacement leurs ressources humaines. Le suivi du personnel constitue une activité cruciale pour assurer la productivité, la conformité administrative et la satisfaction des employés.

**Objectif final** : Fournir un outil numérique simple, fiable et accessible, permettant à l'entreprise de centraliser et de gérer l'ensemble des informations relatives à son personnel.

---

## 2. Architecture du Système

### 2.1. Stack Technique
-   **Backend** : Laravel 11 (PHP 8.2+)
-   **Frontend** : Blade Templates, Alpine.js (Interactivité), Tailwind CSS (Design)
-   **Base de Données** : MySQL
-   **Services Externes** : 
    -   SMTP pour l'envoi des emails (Notifications & OTP).
    -   CDNs pour Tailwind, Axios et Google Fonts (Optimisation du chargement).

### 2.2. Modèle de Données (Entités RH Principales)
1.  **User** : Gère les comptes avec 3 rôles (`admin`, `manager_rh`, `employe`). Inclut les informations personnelles (CNI, CNPS, situation familiale, etc.).
2.  **EmployeeInfo** : Détails professionnels (Département, Poste, Date d'embauche, Adresse).
3.  **Contract** : Gestion des contrats (CDI, CDD, Stage, etc.) avec dates, salaire et statut.
4.  **Attendance** : Suivi des pointages et présences avec calcul des heures travaillées.
5.  **Leave** : Demandes de congés avec workflow d'approbation.
6.  **LeaveBalance** : Gestion des soldes de congés par type et par année.
7.  **Payroll** : Calcul et génération des bulletins de paie.
8.  **PerformanceEvaluation** : Évaluations annuelles des employés.
9.  **Document** : Gestion des documents RH (CV, diplômes, contrats, etc.).
10. **Otp** : Gestion des codes temporaires pour la double authentification.

---

## 3. Spécifications Fonctionnelles Détaillées

### 3.1. Gestion de la Sécurité & Accès
-   **Multi-Facteurs (OTP)** : Envoi d'un code de 6 chiffres par email lors de la connexion. Validité limitée à 10 minutes.
-   **Throttling (Limitation)** : Middleware personnalisé limitant les tentatives sur les routes sensibles (`login`, `password-reset`) à 10 requêtes par minute pour contrer les attaques brute-force.
-   **Gestion des Statuts** :
    -   Le système vérifie dynamiquement si un utilisateur est `Inactif` ou `Suspendu` avant d'autoriser la génération d'OTP ou la connexion.

### 3.2. Interface Administrateur
-   **Vision Dashboard** : Statistiques globales (employés, contrats, congés, présences, évaluations).
-   **Gestion du Personnel** : 
    -   CRUD complet des employés avec informations RH complètes.
    -   Génération automatique de matricules uniques.
    -   Système d'activation/désactivation de comptes.
-   **Gestion des Contrats** : Suivi des contrats avec alertes d'échéance.
-   **Pointage et Présences** : Enregistrement et suivi des heures de travail.
-   **Validation des Congés** : Approbation/refus des demandes avec commentaires.
-   **Rapports** : Export PDF/Excel des fiches employés et listes de personnel.

### 3.3. Interface Manager RH
-   **Dashboard Spécialisé** : Vue sur les congés en attente, présences et évaluations.
-   **Gestion Courante** : Validation des demandes, pointages quotidiens.
-   **Suivi des Équipes** : Statistiques de présence et performance.
-   **Rapports RH** : Génération de rapports d'activité et statistiques.

### 3.4. Interface Employé
-   **Espace Personnel** : Accès à ses informations et solde de congés.
-   **Demandes de Congés** : Soumission et suivi de ses demandes.
-   **Consultation** : Accès à ses bulletins de paie et évaluations.
-   **Profil** : Mise à jour de ses informations personnelles.

### 3.5. Besoins Fonctionnels (BF)
-   **BF01** : Création, modification, consultation et suppression des dossiers du personnel ✅
-   **BF02** : Gestion des présences et absences avec possibilité de pointage ✅
-   **BF03** : Traitement des demandes de congés avec workflow d'approbation ✅
-   **BF04** : Calcul automatique des éléments de paie de base 🔄
-   **BF05** : Génération de bulletins de paie téléchargeables (PDF) 🔄
-   **BF06** : Production de rapports statistiques sur les effectifs ✅
-   **BF07** : Gestion des contrats et alertes sur les échéances ✅
-   **BF08** : Authentification sécurisée et gestion des rôles ✅

---

## 4. Expérience Utilisateur (UX/UI)

### 4.1. Design System
-   **Glassmorphism** : Utilisation intensive de fonds translucides (`backdrop-blur-2xl`) avec des bordures subtiles pour un aspect moderne et épuré.
-   **Palette de Couleurs** : 
    -   Primaires : Indigo, Bleu (RH Personnel), Vert (Présences), Jaune (Congés).
    -   Alertes : Rouge (Urgent), Orange (Attention), Vert (Validé).
-   **Animations** : Transitions fluides sur les boutons et indicateurs d'activité.
-   **Responsive Design** : Adaptation parfaite mobile, tablette et desktop.

### 4.2. Fonctionnalités de Confort
-   **Horloge Live** : Mise à jour à la seconde de l'heure système sur les dashboards.
-   **Indicateurs Visuels** : Pastilles de couleur pour les priorités et les statuts permettant une lecture rapide.

---

## 5. Flux de Communication (Emails)

| Déclencheur | Type d'Email | Contenu Principal |
| :--- | :--- | :--- |
| Connexion | OTP | Code de vérification unique |
| Création de compte employé | Bienvenue | Identifiants et informations d'accès |
| Mot de passe oublié | Récupération | Lien sécurisé / OTP de réinitialisation |
| Demande de congé soumise | Notification | Confirmation de soumission au manager RH |
| Congé approuvé/refusé | Décision | Résultat de la demande avec motif si refusé |
| Contrat expirant bientôt | Alerte | Notification d'échéance de contrat |
| Évaluation de performance | Rapport | Notification de nouvelle évaluation disponible |

---

## 6. Contexte et Contraintes

### 6.1. Contexte d'Utilisation
- **Entreprise camerounaise** de 20 à 100 employés
- **Service RH minimal** avec niveau de compétence informatique modéré
- **Budget limité** nécessitant une solution peu coûteuse à maintenir
- **Interface en français** obligatoire
- **Disponibilité serveur** limitée

### 6.2. Acteurs Principaux
- **Administrateur Système** : Configuration technique, gestion des comptes, sauvegardes
- **Responsable RH / Manager** : Gestion complète du personnel, approbations, reporting
- **Employé** : Consultation dossier personnel, demandes de congés, bulletins de paie

## 7. Maintenance et Évolutions Futures
-   **Module Paie** : Finalisation des calculs et génération des bulletins PDF 🔄
-   **Gestion Documents** : Upload et gestion des documents RH (CV, diplômes, etc.) 🔄
-   **Archivage** : Archivage automatique des données employés après départ
-   **Messagerie** : Chat interne entre manager RH et employés
-   **Notification Push** : Intégration WebSockets pour notifications temps réel
-   **Mobilité** : Application mobile pour pointages et demandes rapides

---

## 8. Statut de Développement
**Version Actuelle** : v1.0 - Système RH Opérationnel  
**Date** : 10 Mai 2026  
**Développé par** : Équipe de développement RH  

### Fonctionnalités Implémentées ✅
- Gestion complète des dossiers du personnel
- Système de pointage et suivi des présences  
- Workflow complet de gestion des congés
- Tableaux de bord et rapports RH
- Authentification sécurisée avec 3 rôles
- Interface responsive et moderne

### En Développement 🔄
- Module de paie et bulletins de salaire
- Gestion des documents RH

---
*Document mis à jour le 10 Mai 2026 - Transformation en plateforme RH complétée*
