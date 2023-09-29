# EmployeeAdministrator Back End

Módulo de gestión de empleados symfony

## Configuración del proyecto
### Clonar el repositorio
``` shell
git clone https://github.com/G-zeus/EmployeeAdministrator.git && cd ExamenBackMeda 
```
Instalamos dependencias de composer
``` shell
composer install
```
### Crear base de datos
```SQL
/*
Se debe de crear una base de datos MySQL
*/
create database _database_name_
```
##### Configuracion en .env
Se debe de configurar la variable **DATABASE_URL** del archivo .env
```
DATABASE_URL=mysql://DB_USER:DB_PASS@127.0.0.1:3306/_database_name_
```

### Ejecutar migraciones
Una vez configurada nuesta base de datos debemos de crear la estructura de tablas
``` shell
php bin/console d:s:u --force  
```
### Iniciar servidor
##### Symfony CLI
``` shell
symfony serve -d --allow-http
```
##### PHP server
``` shell
 php -S 0.0.0.0:2020 -t public/
```
