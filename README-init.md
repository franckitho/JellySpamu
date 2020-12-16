## Initialization du projet

### Installation du projet

Premiérement installer les dépendances :
```Bash
composer install
```

Localizer fichier .env.example, copier son contenue dans un fichier vide nommer **.env**

Si tu peut passer par du bash :
```Bash
cp .env.example .env
```

Ensuite utilisez l'assistant **artisan** pour toutes oppération relative au projet:

* Généré une clé de projet 
```Bash
php artisan key:generate
```
* Créé un fichier de migration (le nom est important example: create_tablename_table formateras le fichier pour une nouvelle table) 
```Bash
php artisan make:migration <nom>
```
* Créer un model
```Bash
php artisan make:model <nom>
```
* Créer un controller
```Bash
php artisan make:controller <nom>
```
* Lister les routes
```Bash
php artisan route:list
```
Pour plus d'info taper simplement 
```Bash
php artisan make
```
Pour afficher l'ensemble des arguments pris en charge



