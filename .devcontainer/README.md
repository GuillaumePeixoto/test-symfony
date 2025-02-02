# Devcontainer - Symfony avec Docker

Ce projet utilise un devcontainer avec Docker pour le développement de l'application Symfony.

## Scripts de gestion de la base de données

Deux scripts Bash permettent d'exporter et d'importer la base de données.

### 1. Exporter la base de données

Ce script effectue un dump de la base de données et le stocke dans un fichier `dump.sql`.

#### Script :

```bash
#!/bin/bash

if [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
fi

dump_file=".devcontainer/dump.sql"

# Exécuter le dump
mysqldump -h db -u$DATABASE_USER -p$DATABASE_PASSWORD $DATABASE_DB_NAME > $dump_file

echo "Dump de la base de données enregistré dans $dump_file"
```

### 2. Importer la base de données

Ce script lit le fichier `dump.sql` et restaure la base de données.

#### Script : 

```bash
#!/bin/bash

if [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
fi

dump_file=".devcontainer/dump.sql"

# Vérification de l'existence du fichier dump
if [ ! -f "$dump_file" ]; then
    echo "Erreur : Le fichier $dump_file n'existe pas."
    exit 1
fi

# Exécuter la restauration
mysql -h db -u$DATABASE_USER -p$DATABASE_PASSWORD $DATABASE_DB_NAME < $dump_file

echo "Base de données restaurée depuis $dump_file"
```

Ces scripts facilitent la sauvegarde et la restauration de ta base de données lors du développement.
