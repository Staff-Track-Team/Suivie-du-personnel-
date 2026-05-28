# 📋 Module Gestion des Projets - Analyse Détaillée

## Introduction

Le module de gestion des projets constitue le cœur opérationnel de la plateforme Camtel, permettant une planification, un suivi et un contrôle efficaces de tous les projets structurants de l'entreprise.

---

## I. Définition du Module

### 1. Objectif Principal

Centraliser et optimiser la gestion de projets avec suivi en temps réel, allocation des ressources et reporting complet.

### 2. Analyse de l'Estimation

**Complexité** : Moyenne-élevée
- Calculs progression automatiques
- Gestion dépendances tâches
- Exports complexes

**Volume de code** : ~2,800 LOC

### 3. Périmètre

#### Utilisateurs Cibles
- Administrateurs : Gestion complète
- Managers : Pilotage projets
- Employés : Consultation

#### Fonctionnalités
- CRUD projets avec validation
- Calcul progression automatique
- Gestion priorités et budgets
- Rapports PDF/Excel
- Timeline et jalons

---

## II. Estimation des Charges (COCOMO)

### Calcul COCOMO

**Paramètres** :
- Type : Semi-detached
- KLOC : 2.8
- Complexité : Moyenne

**Résultats** :
- Charge brute : 10.2 personnes-mois
- Charge nette : 8.7 personnes-mois
- Durée : 5.1 mois
- Développeurs : 2

---

## III. Planification

### Tâches Principales

**Phase 1** : Conception (1 semaine)
**Phase 2** : Développement (4 semaines)  
**Phase 3** : Fonctionnalités avancées (3 semaines)
**Phase 4** : Tests (2 semaines)

**Durée totale** : 10 semaines

---

## IV. Application du Modèle en V

### 1. Spécifications

#### Besoins Fonctionnels
- BF-PROJ-01 : Gestion complète projets
- BF-PROJ-02 : Calcul progression automatique
- BF-PROJ-03 : Gestion budgets et délais
- BF-PROJ-04 : Exports et rapports

#### Besoins Non-Fonctionnels
- Performance : < 2s chargement
- Sécurité : Contrôle accès par rôle
- Fiabilité : Données cohérentes

### 2. Conception Générale

**Architecture** :
```
Présentation (Blade + Livewire)
↓
Contrôleurs (ProjectController)
↓  
Services (ProjectService)
↓
Modèles (Project + Task)
↓
Base de données (MySQL)
```

### 3. Conception Détaillée

#### Base de données
```sql
CREATE TABLE projects (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    budget DECIMAL(12,2),
    start_date DATE,
    end_date DATE,
    priority ENUM('basse', 'moyenne', 'haute', 'urgente'),
    status ENUM('planification', 'actif', 'en_pause', 'termine', 'annule'),
    progress DECIMAL(5,2) DEFAULT 0.00,
    created_by BIGINT NOT NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

#### Algorithmes
**Calcul progression** :
```php
public function calculateProgress($projectId) {
    $tasks = Task::where('project_id', $projectId)->get();
    $total = $tasks->count();
    
    if ($total === 0) return 0;
    
    $weightedSum = 0;
    foreach ($tasks as $task) {
        $weight = $this->getPriorityWeight($task->priority);
        $statusWeight = $this->getStatusWeight($task->status);
        $weightedSum += $weight * $statusWeight;
    }
    
    return round(($weightedSum / $total) * 100, 2);
}
```

### 4. Programmation

#### Technologies
- Laravel 11 + PHP 8.2
- MySQL 8.0
- Tailwind CSS + Alpine.js
- DomPDF pour exports

#### Code principal
```php
class ProjectController extends Controller {
    public function store(CreateProjectRequest $request) {
        $project = Project::create([
            'code' => $this->generateUniqueCode(),
            'name' => $request->name,
            'budget' => $request->budget,
            'priority' => $request->priority,
            'created_by' => auth()->id()
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }
}
```

---

## V. Conclusion

### Synthèse
Module essentiel avec **8.7 personnes-mois** sur **5.1 mois** pour **2 développeurs**.

### Points Forts
- Calculs automatiques progression
- Interface riche et interactive
- Exports professionnels

### Impact Attendu
- Visibilité 100% projets
- Productivité +30%
- Décisions informées

---

*Document d'analyse - Module Projets - 27 mars 2026*
