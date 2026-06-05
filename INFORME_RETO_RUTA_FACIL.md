# Informe Tecnico del Reto - Ruta Facil Laravel

**Alumno:** Juan Cristian Yujra Quispe
**Proyecto:** Ruta Facil Laravel
**Repositorio:** https://github.com/Nobody2077/ruta-facil-laravel
**Fecha:** 2026-06-01

---

## 1. Descripcion del Reto

El reto consiste en desarrollar un sistema de gestion de transporte publico que permita administrar roles, persistir datos complejos y generar reportes automaticos. El sistema debe demostrar dominio de Eloquent ORM, arquitectura limpia con Service Pattern, integridad de datos y pruebas automatizadas.

El proyecto implementado es **Ruta Facil**, un sistema para gestionar rutas de transporte publico de El Alto, Bolivia. Permite que ciudadanos registren opiniones sobre rutas, que moderadores las revisen y que administradores gestionen proyectos, categorias y generen reportes del sistema.

---

## 2. Objetivos de Aprendizaje y Como se Cumplen

| Objetivo | Como se cumplio |
|---|---|
| Dominar Eloquent ORM: One-to-Many, Many-to-Many y Polymorphic | Implementados en 6 modelos con relaciones reales entre ellos |
| Arquitectura Limpia: Service Pattern | `OpinionService`, `ProjectService`, `CategoryService`, `ReportService` desacoplan logica de los controladores |
| Integridad de Datos: Migrations, Seeders y Factories | 9 migraciones con claves foraneas, 3 seeders, 6 factories, 50+ registros de prueba |

---

## 3. Recursos Tecnologicos Utilizados

| Recurso | Version / Detalle |
|---|---|
| Framework | Laravel 12 (compatible con Laravel 11 en estructura y APIs) |
| PHP | 8.2+ (requerido en `composer.json`) |
| Base de datos | MySQL con XAMPP / SQLite en memoria para tests |
| Composer | Gestion de dependencias |
| Laravel Sanctum | Autenticacion por tokens |
| PHPUnit | Pruebas automatizadas |
| Git | Control de versiones |
| Postman | Pruebas manuales de API |

> Nota: el enunciado menciona Laravel 10.x u 11.x. Este proyecto usa Laravel 12, que es la version actual estable y mantiene total compatibilidad estructural con Laravel 11.

---

## 4. Etapa 1: Modelado y Base de Datos

### 4.1 Esquema de la base de datos

El sistema tiene 8 tablas con relaciones entre ellas:

```
users ────────────────────────┐
  |                           | Many-to-Many
  | HasMany                   | (pivot: role_user)
  v                           v
opinions              roles
  |
  | BelongsTo
  v
projects ──── BelongsTo ──── categories
  |
  | MorphMany
  v
comments  (commentable: Project u Opinion)
```

### 4.2 Migraciones con integridad referencial

| Archivo | Tabla | Restricciones |
|---|---|---|
| `0001_01_01_000000_create_users_table.php` | `users` | Base de autenticacion de Laravel |
| `2026_05_19_210624_create_opinions_table.php` | `opinions` | Tabla inicial de opiniones |
| `2026_05_29_220000_create_roles_table.php` | `roles` | Slug unico |
| `2026_05_29_220100_create_categories_table.php` | `categories` | Slug unico |
| `2026_05_29_220200_create_projects_table.php` | `projects` | `category_id` con `restrictOnDelete`, `created_by` con `nullOnDelete` |
| `2026_05_29_220300_create_role_user_table.php` | `role_user` | `cascadeOnDelete` en user_id y role_id, columna extra `assigned_at` |
| `2026_05_29_220400_add_relationships_to_opinions_table.php` | modifica `opinions` | Agrega `user_id` y `project_id` con `nullOnDelete` |
| `2026_05_29_220500_create_comments_table.php` | `comments` | `morphs('commentable')`, `user_id` con `nullOnDelete` |
| `2026_05_30_213617_create_personal_access_tokens_table.php` | `personal_access_tokens` | Tabla de Sanctum |

#### Reglas de integridad aplicadas

- `restrictOnDelete`: no se puede eliminar una categoria si tiene proyectos.
- `nullOnDelete`: si se elimina un usuario o proyecto, las opiniones y comentarios quedan pero sin referencia.
- `cascadeOnDelete`: si se elimina un usuario o rol, la tabla pivote `role_user` se limpia automaticamente.

### 4.3 Seeders

| Seeder | Registros generados |
|---|---|
| `RoleSeeder.php` | 3 roles: `admin`, `moderador`, `usuario` |
| `CategorySeeder.php` | 4 categorias de rutas |
| `DatabaseSeeder.php` | 10 usuarios (con roles asignados), 12 proyectos, 50 opiniones, 27 comentarios polimorficos |

Total: **106+ registros** de prueba, superando el minimo de 50 requerido.

Credenciales de prueba:

| Nombre | Email | Password | Rol |
|---|---|---|---|
| Admin Ruta Facil | admin@rutafacil.test | password | admin |
| Moderador Ruta Facil | moderador@rutafacil.test | password | moderador |
| Lucia Choque | lucia.choque@rutafacil.test | password | usuario |

### 4.4 Factories

| Factory | Modelo | Datos generados |
|---|---|---|
| `UserFactory.php` | User | Nombre, email unico, password |
| `RoleFactory.php` | Role | Nombre y slug dinamicos |
| `CategoryFactory.php` | Category | Nombre, slug, color hex, descripcion |
| `ProjectFactory.php` | Project | Titulo, slug, origen, destino, estado aleatorio |
| `OpinionFactory.php` | Opinion | Nombre, ruta, mensaje, estado aleatorio |
| `CommentFactory.php` | Comment | Cuerpo, estado, con estados `forOpinion()` y `forProject()` |

---

## 5. Etapa 2: Logica de Negocio y API

### 5.1 Controladores tipo Resource

| Controlador | Tipo | Endpoints |
|---|---|---|
| `Api/AuthController` | Auth | register, login, logout |
| `Api/OpinionController` | API Resource | index, show, store, update, destroy |
| `Api/ProjectController` | API Resource | index, show, store, update, destroy |
| `Api/CategoryController` | API Resource | index, show, store, update, destroy |
| `Api/ReportController` | API | summary |
| `OpinionController` | Web Resource | index, create, store, show, edit, update, destroy |

### 5.2 Service Pattern

Cada recurso tiene su propio servicio que desacopla la logica del controlador:

**`OpinionService`**
- `paginated()`: lista paginada con relaciones.
- `store()`: crea opinion y fuerza `user_id` y `status = nuevo`.
- `update()`, `destroy()`, `loadForDetail()`, `projectList()`.

**`ProjectService`**
- `paginated()`: lista paginada con categoria.
- `store()`: crea proyecto y asigna `created_by = Auth::id()`, genera slug si no se provee.
- `update()`, `destroy()`.

**`CategoryService`**
- `all()`: lista ordenada por nombre.
- `store()`, `update()`: genera slug automaticamente desde el nombre si no se provee.
- `destroy()`.

**`ReportService`**
- `summary()`: genera resumen con total de opiniones, opiniones por estado, opiniones por proyecto, proyectos por categoria y total de comentarios.

### 5.3 Form Requests

| Form Request | Campos validados |
|---|---|
| `RegisterRequest` | name, email unico, password con confirmacion |
| `LoginRequest` | email, password |
| `StoreOpinionRequest` | name, route, message (requeridos), project_id (opcional, debe existir) |
| `UpdateOpinionRequest` | Mismos que store + status (enum: nuevo, revisado, archivado) |
| `StoreCategoryRequest` | name (requerido), slug unico, color, description |
| `UpdateCategoryRequest` | Igual que store pero `slug` ignora el registro actual con `Rule::unique()->ignore()` |
| `StoreProjectRequest` | category_id (existe), title, origin, destination, description (requeridos), status (enum), estimated_minutes, distance_km |
| `UpdateProjectRequest` | Igual que store pero `slug` ignora el registro actual |

### 5.4 API Resources

| Resource | Campos expuestos |
|---|---|
| `OpinionResource` | id, name, route, message, status, created_at, project, user, comments (whenLoaded) |
| `ProjectResource` | id, title, origin, destination, status, estimated_minutes, distance_km, category (whenLoaded) |
| `CategoryResource` | id, name, color |
| `CommentResource` | id, body, status, user (whenLoaded), created_at |

### 5.5 Tabla de endpoints API

| Metodo | Ruta | Acceso | Descripcion |
|---|---|---|---|
| POST | `/api/auth/register` | Publico | Registrar usuario, retorna token |
| POST | `/api/auth/login` | Publico | Login, retorna token |
| POST | `/api/auth/logout` | Autenticado | Revocar token |
| GET | `/api/opinions` | Publico | Listar opiniones paginadas |
| GET | `/api/opinions/{id}` | Publico | Ver opinion |
| POST | `/api/opinions` | Autenticado | Crear opinion |
| PUT | `/api/opinions/{id}` | Admin / Moderador | Actualizar opinion |
| DELETE | `/api/opinions/{id}` | Solo Admin | Eliminar opinion |
| GET | `/api/projects` | Publico | Listar proyectos paginados |
| GET | `/api/projects/{id}` | Publico | Ver proyecto |
| POST | `/api/projects` | Autenticado | Crear proyecto |
| PUT | `/api/projects/{id}` | Admin / Moderador | Actualizar proyecto |
| DELETE | `/api/projects/{id}` | Solo Admin | Eliminar proyecto |
| GET | `/api/categories` | Publico | Listar categorias |
| GET | `/api/categories/{id}` | Publico | Ver categoria |
| POST | `/api/categories` | Admin / Moderador | Crear categoria |
| PUT | `/api/categories/{id}` | Admin / Moderador | Actualizar categoria |
| DELETE | `/api/categories/{id}` | Solo Admin | Eliminar categoria |
| GET | `/api/reports/summary` | Admin / Moderador | Reporte resumen del sistema |

---

## 6. Etapa 3: Middleware y Seguridad

### 6.1 Middleware personalizado CheckRole

Archivo: `app/Http/Middleware/CheckRole.php`

Funcionamiento paso a paso:

1. Recibe los roles permitidos como parametros (ej: `role:admin,moderador`).
2. Verifica si el usuario esta autenticado.
3. Llama a `$user->hasRole($slug)` que consulta la relacion Many-to-Many con roles.
4. Si el usuario tiene al menos uno de los roles permitidos, deja pasar la solicitud.
5. Si no tiene permiso devuelve HTTP `403`.
6. Si no esta autenticado en rutas solo-JSON devuelve HTTP `401`.

Registro en `bootstrap/app.php`:

```php
$middleware->alias(['role' => \App\Http\Middleware\CheckRole::class]);
```

### 6.2 Uso en rutas

```php
// Solo admin puede eliminar
Route::middleware(['auth:sanctum', 'role:admin'])
    ->apiResource('opinions', OpinionController::class)
    ->only(['destroy']);

// Admin o moderador pueden actualizar
Route::middleware(['auth:sanctum', 'role:admin,moderador'])
    ->apiResource('opinions', OpinionController::class)
    ->only(['update']);
```

### 6.3 Autenticacion por tokens con Sanctum

- `User` usa el trait `HasApiTokens`.
- Al hacer login, `AuthController` genera un token con `createToken('api-token')->plainTextToken`.
- El token se envia en cada peticion protegida como header `Authorization: Bearer <token>`.
- Al hacer logout, el token se revoca con `$user->currentAccessToken()->delete()`.

### 6.4 Tabla de control de acceso

| Accion | usuario | moderador | admin |
|---|---|---|---|
| Leer opiniones, proyectos, categorias | Si | Si | Si |
| Crear opinion o proyecto | Si | Si | Si |
| Actualizar opinion o proyecto | No | Si | Si |
| Crear o actualizar categoria | No | Si | Si |
| Ver reportes | No | Si | Si |
| Eliminar cualquier recurso | No | No | Si |

---

## 7. Etapa 4: Pruebas y Documentacion

### 7.1 Feature Tests implementados

| Archivo | Que verifica | Tests |
|---|---|---|
| `AuthApiTest.php` | Registro, login, logout, validaciones, asignacion de rol | 10 |
| `OpinionApiTest.php` | CRUD completo de opiniones, autenticacion, permisos por rol | 12 |
| `OpinionCrudTest.php` | CRUD web Blade, creacion, edicion, eliminacion, validaciones | 11 |
| `ModelRelationshipTest.php` | Relaciones Eloquent Many-to-Many y polimorficas | 2 |
| `ReportApiTest.php` | 401 sin auth, 403 rol usuario, 200 admin/moderador, conteos correctos | 5 |
| `ProjectApiTest.php` | CRUD completo de proyectos, permisos, validaciones | 14 |
| `CategoryApiTest.php` | CRUD completo de categorias, permisos, validaciones | 14 |
| `ExampleTest.php` | Respuesta basica de la aplicacion | 1 |

### 7.2 Resultado final de pruebas

```bash
G:\xampp\php\php.exe artisan test
```

```
Tests: 71 passed (193 assertions)
Duration: 2.63s
```

Todos los tests pasan con base de datos SQLite en memoria, lo que garantiza que los endpoints y relaciones funcionan correctamente en un entorno limpio y reproducible.

### 7.3 Flujo tecnico: crear una opinion por API

```
Cliente
  |
  | POST /api/opinions  { Authorization: Bearer <token> }
  v
routes/api.php
  |
  | middleware auth:sanctum → verifica token, devuelve 401 si invalido
  v
StoreOpinionRequest
  |
  | valida name, route, message → devuelve 422 si falla
  v
Api/OpinionController@store
  |
  | llama OpinionService::store(validated)
  v
OpinionService
  |
  | Opinion::create([...datos, user_id => Auth::id(), status => 'nuevo'])
  v
Eloquent → INSERT INTO opinions
  |
  v
OpinionResource
  |
  | { "data": { id, name, route, message, status, ... } }
  v
HTTP 201 Created
```

### 7.4 Flujo tecnico: reporte resumido

```
Cliente
  |
  | GET /api/reports/summary  { Authorization: Bearer <token de admin o moderador> }
  v
routes/api.php
  |
  | middleware auth:sanctum → verifica token
  | middleware role:admin,moderador → verifica rol, devuelve 403 si no tiene permiso
  v
Api/ReportController@summary
  |
  | llama ReportService::summary()
  v
ReportService
  |
  | Opinion::count()
  | Opinion::selectRaw('status, count(*) as total')->groupBy('status')
  | Project::withCount('opinions')->orderByDesc('opinions_count')->get()
  | Category::withCount('projects')->get()
  | Comment::count()
  v
HTTP 200 OK
  |
  | { "data": { total_opinions, opinions_by_status, opinions_by_project,
  |             projects_by_category, total_comments } }
```

---

## 8. Estructura del Proyecto

```
app/
  Http/
    Controllers/
      Api/
        AuthController.php
        OpinionController.php
        ProjectController.php
        CategoryController.php
        ReportController.php
      OpinionController.php           (CRUD web Blade)
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
      OpinionResource.php
      ProjectResource.php
      CategoryResource.php
      CommentResource.php
  Models/
    User.php
    Role.php
    Category.php
    Project.php
    Opinion.php
    Comment.php
  Services/
    OpinionService.php
    ProjectService.php
    CategoryService.php
    ReportService.php
database/
  factories/        (6 factories)
  migrations/       (9 migraciones)
  seeders/
    DatabaseSeeder.php
    RoleSeeder.php
    CategorySeeder.php
routes/
  api.php
  web.php
tests/
  Feature/
    AuthApiTest.php
    OpinionApiTest.php
    OpinionCrudTest.php
    ModelRelationshipTest.php
    ReportApiTest.php
    ProjectApiTest.php
    CategoryApiTest.php
```

---

## 9. Conclusion

El proyecto **Ruta Facil Laravel** cumple todos los requisitos del reto:

- **Etapa 1**: 9 migraciones con integridad referencial, 3 seeders con 106+ registros, 6 factories.
- **Etapa 2**: Controladores Resource para Opinions, Projects y Categories; Service Pattern en 4 servicios; 8 Form Requests; 4 API Resources; 19 endpoints REST documentados.
- **Etapa 3**: Middleware `CheckRole` personalizado, autenticacion por tokens con Laravel Sanctum, control de acceso por rol en todos los endpoints.
- **Etapa 4**: 71 tests Feature con 193 assertions, todos pasando; documentacion del flujo de datos.

El sistema demuestra dominio de las relaciones Eloquent (One-to-Many, Many-to-Many con columna pivote `assigned_at`, Polymorphic con `commentable_type/id`), aplica arquitectura limpia con Service Pattern y cuenta con una suite de pruebas que verifica cada caso de uso relevante del sistema.
