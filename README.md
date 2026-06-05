# Ruta Facil Laravel

Proyecto Laravel MVC para administrar rutas de transporte publico de El Alto, Bolivia. Permite registrar y gestionar opiniones ciudadanas sobre rutas, desvios, demoras y sugerencias, con API REST, autenticacion por tokens y control de acceso por roles.

## Estado del proyecto

| Requisito | Estado |
|---|---|
| Modelos: User, Role, Category, Project, Opinion, Comment | Cumplido |
| Migraciones con integridad referencial | Cumplido |
| Seeders con 50+ registros de prueba | Cumplido |
| Factories para todos los modelos | Cumplido |
| Relacion One-to-Many | Cumplido |
| Relacion Many-to-Many con pivot | Cumplido |
| Relacion Polymorphic | Cumplido |
| Controladores Resource API (Opinions, Projects, Categories) | Cumplido |
| Form Requests para validaciones avanzadas | Cumplido |
| API Resources con respuestas JSON estandarizadas | Cumplido |
| Service Pattern (OpinionService, ProjectService, CategoryService, ReportService) | Cumplido |
| Middleware personalizado de verificacion de roles | Cumplido |
| Autenticacion por tokens (Sanctum) | Cumplido |
| Proteccion de rutas por rol | Cumplido |
| Reporte automatico JSON con resumen del sistema | Cumplido |
| Feature Tests para endpoints principales (71 tests) | Cumplido |
| Documentacion tecnica del flujo de datos | Cumplido |

## Tecnologias

- Laravel 12
- PHP 8.2 o superior
- MySQL / MariaDB
- Laravel Sanctum (autenticacion por tokens)
- Composer
- Vite
- Blade

> Nota: el documento de requisitos menciona Laravel 10.x u 11.x. Este proyecto usa Laravel 12; conviene confirmar con el docente si la version sera aceptada para la entrega.

---

## Flujo de datos del sistema

### Arquitectura general

```
Cliente (HTTP)
    |
    v
routes/api.php  o  routes/web.php
    |
    v
Middleware (auth:sanctum, role:admin, ...)
    |
    v
Form Request  (valida y autoriza la entrada)
    |
    v
Controller  (recibe el request validado, delega al Service)
    |
    v
Service  (logica de negocio: que hacer con los datos)
    |
    v
Eloquent Model  (ejecuta la consulta en la base de datos)
    |
    v
API Resource  (transforma el modelo en JSON estandarizado)
    |
    v
Respuesta JSON / Vista Blade
```

### Flujo detallado: crear una opinion via API

1. **Cliente** envia `POST /api/opinions` con `Authorization: Bearer <token>` y body JSON.

2. **`routes/api.php`** asigna la ruta al middleware `auth:sanctum` y luego a `Api\OpinionController@store`.

3. **Middleware `auth:sanctum`** verifica el token. Si es invalido devuelve `401`.

4. **`StoreOpinionRequest`** valida que `name`, `route` y `message` esten presentes y cumplan las reglas de longitud. Si falla devuelve `422` con errores de validacion.

5. **`Api\OpinionController@store`** recibe el array validado y llama a `OpinionService::store()`.

6. **`OpinionService::store()`** crea el modelo `Opinion` forzando `user_id = Auth::id()` y `status = 'nuevo'`, sin importar lo que el cliente haya enviado.

7. **Eloquent** ejecuta `INSERT INTO opinions ...` y retorna el modelo creado.

8. **`OpinionResource`** envuelve el modelo en `['data' => [...]]` con los campos publicos seleccionados.

9. El controlador retorna `response()->json($resource, 201)`.

### Flujo detallado: eliminar una opinion via API (solo admin)

1. **Cliente** envia `DELETE /api/opinions/{id}` con token de usuario admin.

2. **`auth:sanctum`** valida el token.

3. **Middleware `CheckRole`** llama a `$user->hasRole('admin')`. Si el usuario tiene rol `usuario` o `moderador`, devuelve `403`.

4. **`Api\OpinionController@destroy`** recibe la instancia de `Opinion` resuelta por route model binding.

5. **`OpinionService::destroy()`** llama a `$opinion->delete()`.

6. Eloquent ejecuta `DELETE FROM opinions WHERE id = ?`.

7. El controlador retorna `response()->noContent()` con HTTP `204`.

### Relaciones Eloquent del dominio

```
User ──────────────────┐
 |                     |
 | hasMany             | belongsToMany (pivot: role_user)
 v                     v
Opinion            Role
 |
 | belongsTo
 v
Project ──── belongsTo ──── Category
 |
 | morphMany
 v
Comment (commentable: Project o Opinion)
```

- **One-to-Many**: `User` → `Opinion`, `User` → `Project` (created_by), `Project` → `Opinion`, `Category` → `Project`.
- **Many-to-Many**: `User` ↔ `Role` mediante tabla pivote `role_user` con columna `assigned_at`.
- **Polymorphic**: `Comment` puede pertenecer a un `Project` o a una `Opinion` mediante `commentable_type` y `commentable_id`.

### Control de acceso por rol

| Endpoint | Acceso |
|---|---|
| `GET /api/opinions` | Publico |
| `GET /api/opinions/{id}` | Publico |
| `POST /api/opinions` | Autenticado (cualquier rol) |
| `PUT /api/opinions/{id}` | `admin` o `moderador` |
| `DELETE /api/opinions/{id}` | Solo `admin` |
| `POST /api/auth/register` | Publico |
| `POST /api/auth/login` | Publico |
| `POST /api/auth/logout` | Autenticado |

---

## Requisitos locales

- XAMPP con PHP 8.2+ y MySQL/MariaDB
- Composer
- Node.js y npm (solo si se compilan assets con Vite)
- Base de datos MySQL llamada `ruta_facil`

## Preparacion de base de datos

En phpMyAdmin o desde terminal:

```powershell
G:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS ruta_facil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

## Configuracion

```powershell
copy .env.example .env
```

Verifica que `.env` tenga la conexion correcta:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ruta_facil
DB_USERNAME=root
DB_PASSWORD=
```

Genera la clave de la aplicacion:

```powershell
G:\xampp\php\php.exe artisan key:generate
```

## Instalacion

```powershell
composer install
```

Si vas a trabajar con assets frontend:

```powershell
npm install
npm run build
```

## Migraciones y datos de prueba

```powershell
G:\xampp\php\php.exe artisan migrate
G:\xampp\php\php.exe artisan db:seed
```

O desde cero:

```powershell
G:\xampp\php\php.exe artisan migrate:fresh --seed
```

El seeder crea:

- 3 roles: `admin`, `moderador`, `usuario`
- 4 categorias de rutas
- 10 usuarios (admin, moderador y 8 usuarios regulares)
- 12 proyectos de rutas
- 50 opiniones ciudadanas
- 27 comentarios polimorficos (sobre proyectos y opiniones)

Credenciales de prueba:

| Usuario | Email | Password | Rol |
|---|---|---|---|
| Admin Ruta Facil | admin@rutafacil.test | password | admin |
| Moderador Ruta Facil | moderador@rutafacil.test | password | moderador |
| Lucia Choque | lucia.choque@rutafacil.test | password | usuario |

## Ejecutar el proyecto

```powershell
G:\xampp\php\php.exe artisan serve
```

Luego abre `http://127.0.0.1:8000`.

## Rutas principales

### Web (Blade)

| Metodo | Ruta | Descripcion |
|---|---|---|
| GET | `/` | Landing de Ruta Facil con ultimas opiniones |
| GET | `/opiniones` | Listar opiniones |
| GET | `/opiniones/create` | Formulario de creacion |
| POST | `/opiniones` | Guardar opinion |
| GET | `/opiniones/{id}` | Ver detalle |
| GET | `/opiniones/{id}/edit` | Formulario de edicion |
| PUT | `/opiniones/{id}` | Actualizar opinion |
| DELETE | `/opiniones/{id}` | Eliminar opinion |

### API REST (`/api`)

#### Autenticacion

| Metodo | Ruta | Acceso | Descripcion |
|---|---|---|---|
| POST | `/api/auth/register` | Publico | Registrar usuario, retorna token |
| POST | `/api/auth/login` | Publico | Login, retorna token |
| POST | `/api/auth/logout` | Auth | Revocar token |

#### Opiniones

| Metodo | Ruta | Acceso | Descripcion |
|---|---|---|---|
| GET | `/api/opinions` | Publico | Listar opiniones paginadas |
| GET | `/api/opinions/{id}` | Publico | Ver opinion |
| POST | `/api/opinions` | Auth | Crear opinion |
| PUT | `/api/opinions/{id}` | Admin / Moderador | Actualizar opinion |
| DELETE | `/api/opinions/{id}` | Solo Admin | Eliminar opinion |

#### Proyectos

| Metodo | Ruta | Acceso | Descripcion |
|---|---|---|---|
| GET | `/api/projects` | Publico | Listar proyectos paginados |
| GET | `/api/projects/{id}` | Publico | Ver proyecto |
| POST | `/api/projects` | Auth | Crear proyecto |
| PUT | `/api/projects/{id}` | Admin / Moderador | Actualizar proyecto |
| DELETE | `/api/projects/{id}` | Solo Admin | Eliminar proyecto |

#### Categorias

| Metodo | Ruta | Acceso | Descripcion |
|---|---|---|---|
| GET | `/api/categories` | Publico | Listar categorias |
| GET | `/api/categories/{id}` | Publico | Ver categoria |
| POST | `/api/categories` | Admin / Moderador | Crear categoria |
| PUT | `/api/categories/{id}` | Admin / Moderador | Actualizar categoria |
| DELETE | `/api/categories/{id}` | Solo Admin | Eliminar categoria |

#### Reportes

| Metodo | Ruta | Acceso | Descripcion |
|---|---|---|---|
| GET | `/api/reports/summary` | Admin / Moderador | Resumen: totales, estados, opiniones por proyecto, proyectos por categoria |

## Estructura relevante

```text
app/
  Http/
    Controllers/
      Api/
        AuthController.php
        OpinionController.php
        ProjectController.php
        CategoryController.php
        ReportController.php
      OpinionController.php        (CRUD web Blade)
    Middleware/
      CheckRole.php
    Requests/
      LoginRequest.php
      RegisterRequest.php
      StoreOpinionRequest.php
      UpdateOpinionRequest.php
      StoreProjectRequest.php
      UpdateProjectRequest.php
      StoreCategoryRequest.php
      UpdateCategoryRequest.php
    Resources/
      CategoryResource.php
      CommentResource.php
      OpinionResource.php
      ProjectResource.php
  Models/
    Category.php
    Comment.php
    Opinion.php
    Project.php
    Role.php
    User.php
  Services/
    OpinionService.php
    ProjectService.php
    CategoryService.php
    ReportService.php
database/
  factories/
  migrations/
  seeders/
    DatabaseSeeder.php
    CategorySeeder.php
    RoleSeeder.php
routes/
  api.php
  web.php
tests/
  Feature/
    AuthApiTest.php
    ModelRelationshipTest.php
    OpinionApiTest.php
    OpinionCrudTest.php
    ProjectApiTest.php
    CategoryApiTest.php
    ReportApiTest.php
```

## Pruebas

```powershell
G:\xampp\php\php.exe artisan test
```

| Archivo | Cobertura |
|---|---|
| `AuthApiTest` | Registro, login, logout, validaciones, asignacion de rol |
| `OpinionApiTest` | CRUD completo de la API con verificacion de roles |
| `ModelRelationshipTest` | Relaciones Eloquent (One-to-Many, Many-to-Many, Polymorphic) |
| `OpinionCrudTest` | CRUD web Blade: creacion, edicion, eliminacion, validaciones |
| `ProjectApiTest` | CRUD completo de proyectos, permisos por rol, validaciones |
| `CategoryApiTest` | CRUD completo de categorias, permisos por rol, validaciones |
| `ReportApiTest` | Reporte resumen: autenticacion, permisos y conteos correctos |

Resultado esperado:

```
Tests: 71 passed (193 assertions)
```
