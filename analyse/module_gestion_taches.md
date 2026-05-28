# ✅ Module Gestion des Tâches - Analyse Détaillée

## Introduction

Le module de gestion des tâches représente l'unité opérationnelle de la plateforme, permettant l'assignation, le suivi et la gestion des tâches individuelles au sein des projets Camtel.

---

## I. Définition du Module

### 1. Objectif Principal

Faciliter la gestion quotidienne des tâches avec assignation intelligente, suivi en temps réel et audit complet pour optimiser la productivité individuelle.

### 2. Analyse de l'Estimation

**Complexité** : Moyenne
- Workflow tâches
- Timeline d'audit
- Notifications automatiques

**Volume de code** : ~2,200 LOC

### 3. Périmètre

#### Utilisateurs Cibles
- Administrateurs : Gestion complète
- Managers : Assignation et suivi
- Employés : Exécution et mise à jour

#### Fonctionnalités
- CRUD tâches avec assignation
- Workflow statuts (À faire → En cours → Terminé)
- Timeline d'audit complète
- Notifications email automatiques

---

## II. Estimation des Charges (COCOMO)

### Calcul COCOMO

**Paramètres** :
- Type : Semi-detached
- KLOC : 2.2
- Complexité : Moyenne

**Résultats** :
- Charge brute : 8.5 personnes-mois
- Charge nette : 7.2 personnes-mois
- Durée : 4.8 mois
- Développeurs : 2

---

## III. Planification

### Tâches Principales

**Phase 1** : Conception (1 semaine)
**Phase 2** : Développement core (3 semaines)
**Phase 3** : Workflow et notifications (3 semaines)
**Phase 4** : Tests (1.5 semaines)

**Durée totale** : 8.5 semaines

---

## IV. Application du Modèle en V

### 1. Spécifications

#### Besoins Fonctionnels
- BF-TASK-01 : Gestion complète tâches
- BF-TASK-02 : Workflow statuts
- BF-TASK-03 : Assignation flexible
- BF-TASK-04 : Timeline d'audit

#### Besoins Non-Fonctionnels
- Performance : < 1s mise à jour
- Notifications : Temps réel
- Audit : Historique immuable

### 2. Conception Générale

**Architecture** :
```
Présentation (Livewire)
↓
Contrôleurs (TaskController)
↓  
Services (TaskService + NotificationService)
↓
Modèles (Task + TaskAudit)
↓
Base de données (MySQL)
```

### 3. Conception Détaillée

#### Base de données
```sql
CREATE TABLE tasks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    project_id BIGINT NOT NULL,
    assigned_to BIGINT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    priority ENUM('basse', 'moyenne', 'haute', 'urgente'),
    status ENUM('a_faire', 'en_cours', 'en_revision', 'termine'),
    due_date DATE,
    completed_at TIMESTAMP NULL,
    created_by BIGINT NOT NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);

CREATE TABLE task_audits (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    task_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    action VARCHAR(50) NOT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Algorithmes
**Workflow tâches** :
```php
public function updateTaskStatus(Task $task, string $newStatus, User $user) {
    $oldStatus = $task->status;
    
    // Validation workflow
    if (!$this->isValidTransition($oldStatus, $newStatus)) {
        throw new InvalidStatusTransitionException();
    }
    
    // Mise à jour
    $task->status = $newStatus;
    if ($newStatus === 'termine') {
        $task->completed_at = now();
    }
    $task->save();
    
    // Audit
    TaskAudit::create([
        'task_id' => $task->id,
        'user_id' => $user->id,
        'action' => 'status_changed',
        'old_values' => ['status' => $oldStatus],
        'new_values' => ['status' => $newStatus]
    ]);
    
    // Notification
    $this->notificationService->notifyStatusChange($task, $oldStatus, $newStatus);
}
```

### 4. Programmation

#### Technologies
- Laravel 11 + PHP 8.2
- MySQL 8.0
- Livewire 3.6
- Queue pour notifications

#### Code principal
```php
class TaskController extends Controller {
    public function updateStatus(Request $request, Task $task) {
        $request->validate([
            'status' => 'required|in:a_faire,en_cours,en_revision,termine'
        ]);
        
        $this->taskService->updateTaskStatus(
            $task, 
            $request->status, 
            auth()->user()
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour'
        ]);
    }
}
```

---

## V. Conclusion

### Synthèse
Module opérationnel avec **7.2 personnes-mois** sur **4.8 mois** pour **2 développeurs**.

### Points Forts
- Workflow intuitif
- Audit complet
- Notifications temps réel

### Impact Attendu
- Productivité individuelle +25%
- Traçabilité 100%
- Collaboration améliorée

---

*Document d'analyse - Module Tâches - 27 mars 2026*
