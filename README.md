# Symfony API - Documentation pour tester les routes

Cette API permet de gérer des tâches avec les opérations suivantes :
- Récupérer toutes les tâches
- Créer une nouvelle tâche
- Mettre à jour une tâche existante
- Supprimer une tâche

## 1. Récupérer toutes les tâches
### Route : `GET /tasks/get-all`
Effectuez une requête GET pour obtenir toutes les tâches enregistrées.

```bash
curl -X GET http://localhost:8000/tasks/get-all
```

## 2. Créer une nouvelle tâche
### Route : `POST /tasks/create`
Crée une nouvelle tâche avec les paramètres `titre`, `status` (obligatoires), et `description` (facultatif).

Exemples de requêtes pour tester la création de tâches :

```bash
curl -X POST http://localhost:8000/tasks/create \
-H "Content-Type: application/json" \
-d '{"titre": "Nouvelle tâche 1", "status": "à faire"}'
```

```bash
curl -X POST http://localhost:8000/tasks/create \
-H "Content-Type: application/json" \
-d '{"titre": "Nouvelle tâche 2", "status": "en cours", "description": "Description de la tâche 2"}'
```

```bash
curl -X POST http://localhost:8000/tasks/create \
-H "Content-Type: application/json" \
-d '{"titre": "Nouvelle tâche 3", "status": "à valider"}'
```


## 3. Mettre à jour une tâche
### Route : `PUT /tasks/update/{id}`
Met à jour une tâche existante par son `id`. Les champs `titre`, `description` et `status` peuvent être mis à jour, mais ne sont modifiés que si non vides.

Exemple pour mettre à jour une tâche (ici `id=1`) :

```bash
curl -X PUT http://localhost:8000/tasks/update/1 \
-H "Content-Type: application/json" \
-d '{"titre": "Tâche mise à jour", "description": "Description modifiée", "status": "en cours"}'
```


## 4. Supprimer une tâche
### Route : `DELETE /tasks/delete/{id}`
Supprime une tâche par son `id`.

Exemple pour supprimer une tâche (ici `id=1`) :

```bash
curl -X DELETE http://localhost:8000/tasks/delete/1
```