# Ruta Facil Laravel

Proyecto Laravel MVC para presentar la app Ruta Facil y administrar un CRUD de opiniones de usuarios sobre rutas de transporte publico de El Alto.

## Requisitos

- XAMPP con PHP 8.2 y MySQL/MariaDB.
- Composer.
- Base de datos MySQL llamada `ruta_facil`.

## Preparacion de base de datos

En phpMyAdmin crea la base de datos:

```sql
CREATE DATABASE ruta_facil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Tambien puedes hacerlo desde terminal:

```powershell
G:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS ruta_facil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

## Ejecutar el proyecto

```powershell
cd G:\Usuario\Downloads\ruta-facil-laravel
G:\xampp\php\php.exe artisan migrate
G:\xampp\php\php.exe artisan serve
```

Luego abre:

```text
http://127.0.0.1:8000
```

## CRUD implementado

- `GET /opiniones`: listar opiniones.
- `GET /opiniones/create`: formulario de creacion.
- `POST /opiniones`: guardar opinion.
- `GET /opiniones/{opinion}`: ver detalle.
- `GET /opiniones/{opinion}/edit`: formulario de edicion.
- `PUT /opiniones/{opinion}`: actualizar opinion.
- `DELETE /opiniones/{opinion}`: eliminar opinion.

## Nota sobre XAMPP

Si MySQL no inicia y muestra errores de permisos en `G:\xampp\mysql\data`, abre el panel de XAMPP como administrador o corrige permisos de escritura en la carpeta `G:\xampp\mysql\data`.
