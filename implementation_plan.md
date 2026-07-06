# Plan de Implementación: Sistema de Control de Acceso

Este documento detalla el plan de trabajo para construir un **Sistema de Control de Visitantes y Accesos** como proyecto **Laravel 11 independiente** (no es un módulo de SJSEGURIDAD).

---

## Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 11 + PHP 8.2+ |
| Frontend | Laravel Breeze (Blade + Alpine.js + Tailwind CSS) |
| Auth/RBAC | Spatie Laravel Permission v6 |
| Base de datos | MySQL (Laragon) |
| Assets | Vite + TailwindCSS + PostCSS |
| Tablas/Export | jQuery + DataTables con botones Excel |
| QR | `simplesoftwareio/simple-qrcode` o JS nativo |
| Notificaciones | Mailable ShouldQueue (colas) |

---

## Modelo de Datos

### Tabla: `locations`
| Campo | Tipo | Descripción |
|-------|------|------------|
| id | id | |
| code | string(20) unique | Código interno de la sede/portería |
| name | string(100) | Nombre de la ubicación |
| address | string(255) nullable | Dirección |
| phone | string(20) nullable | Teléfono de contacto |
| type | string(20) | Tipo: portería, edificio, sede, bodega |
| is_active | boolean default true | |
| timestamps | | |
| softDeletes | | |

### Tabla: `visitors`
| Campo | Tipo | Descripción |
|-------|------|------------|
| id | id | |
| document_type | string(20) | CC, NIT, CE, Pasaporte |
| document_number | string(50) | Número de documento |
| first_name | string(100) | |
| last_name | string(100) | |
| phone | string(20) nullable | |
| email | string(100) nullable | |
| nationality | string(50) nullable | |
| company | string(150) nullable | Empresa externa a la que pertenece |
| photo_path | string(255) nullable | Foto del rostro |
| visitor_type | string(20) | persona, contratista, proveedor |
| birth_date | date nullable | |
| notes | text nullable | |
| timestamps | | |
| softDeletes | | |
| **unique** | [document_type, document_number] | |

### Tabla: `vehicles`
| Campo | Tipo | Descripción |
|-------|------|------------|
| id | id | |
| visitor_id | FK→visitors nullable | Dueño del vehículo (si es visitante) |
| plate | string(20) unique | Placa |
| brand | string(50) nullable | Marca |
| model | string(50) nullable | Modelo |
| color | string(30) nullable | |
| type | string(20) | carro, moto, camión |
| photo_path | string(255) nullable | |
| timestamps | | |
| softDeletes | | |

### Tabla: `access_logs` (núcleo del sistema)
| Campo | Tipo | Descripción |
|-------|------|------------|
| id | id | |
| visitor_id | FK→visitors | Visitante que ingresa |
| vehicle_id | FK→vehicles nullable | Vehículo asociado (opcional) |
| host_id | FK→users | Anfitrión/empleado que recibe |
| location_id | FK→locations | Portería/ubicación de ingreso |
| authorized_by | FK→users | Guardia que registró el ingreso |
| entry_time | datetime | Fecha/hora de ingreso |
| exit_time | datetime nullable | Fecha/hora de salida |
| status | string(20) default 'active' | active, completed |
| purpose | string(255) nullable | Motivo de la visita |
| company_visited | string(150) nullable | Empresa a la que visita |
| screening_temp | decimal(4,1) nullable | Temperatura (control sanitario) |
| qr_code | string(100) unique nullable | Hash QR para pre-autorización |
| notes | text nullable | |
| timestamps | | |
| **índices** | [status, entry_time], [visitor_id, status], [host_id, entry_time] | |

### Tabla: `pre_authorizations`
| Campo | Tipo | Descripción |
|-------|------|------------|
| id | id | |
| visitor_id | FK→visitors | Visitante pre-autorizado |
| host_id | FK→users | Anfitrión que autoriza |
| location_id | FK→locations | Ubicación destino |
| scheduled_date | date | Fecha programada |
| scheduled_time | time nullable | Hora programada |
| expires_at | datetime nullable | Fecha de expiración |
| status | string(20) default 'pending' | pending, used, cancelled, expired |
| qr_code | string(100) unique | Hash único para el QR |
| notes | text nullable | |
| timestamps | | |

### Tabla: `correspondence`
| Campo | Tipo | Descripción |
|-------|------|------------|
| id | id | |
| visitor_id | FK→visitors nullable | Visitante que dejó el paquete |
| host_id | FK→users | Anfitrión destinatario |
| location_id | FK→locations | Ubicación donde se recibe |
| carrier | string(100) nullable | Empresa transportadora |
| courier_guide | string(100) nullable | Número de guía |
| package_type | string(30) | sobre, caja, documento, otro |
| received_at | datetime | Fecha de recepción |
| received_by | FK→users | Quién recibe |
| delivered_at | datetime nullable | Fecha de entrega al destinatario |
| delivered_by | FK→users nullable | Quién entrega |
| status | string(20) default 'pending' | pending, delivered |
| photo_path | string(255) nullable | Foto del paquete |
| notes | text nullable | |
| timestamps | | |
| softDeletes | | |

### Tabla: `guard_logs`
| Campo | Tipo | Descripción |
|-------|------|------------|
| id | id | |
| user_id | FK→users | Guardia que registra |
| location_id | FK→locations | Ubicación del evento |
| log_time | datetime | Fecha/hora del evento |
| type | string(20) | novedad, turno, incidente, general |
| shift_type | string(20) | diurno, nocturno |
| description | text | Descripción detallada |
| timestamps | | |
| softDeletes | | |

### Tabla: `visitor_documents`
| Campo | Tipo | Descripción |
|-------|------|------------|
| id | id | |
| visitor_id | FK→visitors | Visitante asociado |
| type | string(30) | cedula, contrato, carnet, otros |
| file_path | string(255) | Ruta del archivo |
| notes | text nullable | |
| timestamps | | |

---

## Roles y Permisos

### Roles
| Rol | Descripción |
|-----|-------------|
| `super-admin` | Acceso total al sistema |
| `admin-accesos` | Administra ubicaciones, configuración, reportes |
| `guardia` | Registra ingresos/salidas, correspondencia, minutas |
| `anfitrion` | Pre-autoriza visitas, consulta sus registros |

### Permisos (config/access.php)
```
access.dashboard
access.manage.visitors
access.manage.vehicles
access.register.entry
access.register.exit
access.manage.pre_authorizations
access.manage.correspondence
access.manage.guard_logs
access.manage.locations
access.view.reports
```

---

## Estructura del Proyecto

```
routes/modules/access.php

app/Http/Controllers/Access/
├── DashboardController.php
├── LocationController.php
├── VisitorController.php
├── VehicleController.php
├── AccessLogController.php
├── PreAuthorizationController.php
├── CorrespondenceController.php
├── GuardLogController.php
└── ReportController.php

app/Models/
├── Location.php
├── Visitor.php
├── Vehicle.php
├── AccessLog.php
├── PreAuthorization.php
├── Correspondence.php
├── GuardLog.php
└── VisitorDocument.php

app/Http/Requests/Access/
├── StoreVisitorRequest.php
├── UpdateVisitorRequest.php
├── StoreAccessLogRequest.php
├── StoreCorrespondenceRequest.php
├── StoreGuardLogRequest.php
├── StorePreAuthorizationRequest.php
├── StoreLocationRequest.php
└── UpdateLocationRequest.php

resources/views/modules/access/
├── dashboard.blade.php
├── locations/index.blade.php
├── visitors/index.blade.php
├── visitors/create.blade.php
├── visitors/edit.blade.php
├── vehicles/index.blade.php
├── logs/index.blade.php (dashboard del guarda)
├── pre_authorizations/index.blade.php
├── pre_authorizations/create.blade.php
├── correspondence/index.blade.php
├── correspondence/create.blade.php
├── guard_logs/index.blade.php
├── guard_logs/create.blade.php
├── reports/index.blade.php
└── partials/subnav.blade.php

config/access.php
```

---

## Fases de Implementación

### Fase 1: Setup del proyecto + Base de datos
1. Crear proyecto Laravel 11
2. Instalar Breeze (Blade stack) + Spatie Permission
3. Configurar `.env`, base de datos MySQL
4. Crear migraciones de las 8 tablas
5. Crear modelos con relaciones, casts, factories
6. Crear seeders (roles, permisos, admin por defecto)
7. Middleware personalizados (`active`, `password.changed`)

### Fase 2: Layout + Navegación + Config
1. Layout `app.blade.php` con sidebar de módulos
2. Navegación dinámica desde `config('access.navigation')`
3. Configuración de permisos en `config/access.php`
4. Super-admin bypass (Gate::before)

### Fase 3: CRUD de Ubicaciones (Locations)
1. Controlador + request + vistas CRUD
2. Solo accesible para admin-accesos y super-admin

### Fase 4: CRUD de Visitantes + Vehículos
1. CRUD completo de visitantes con búsqueda por documento
2. CRUD de vehículos vinculados a visitantes
3. Componente de búsqueda rápida con Alpine.js

### Fase 5: Núcleo — Ingresos y Salidas
1. Dashboard del guarda optimizado
2. Flujo de ingreso (buscar/crear visitante, seleccionar anfitrión, registrar)
3. Flujo de salida (lista de activos, botón de salida)
4. Cálculo de tiempo de estadía

### Fase 6: Pre-autorizaciones + QR
1. Anfitrión crea pre-autorización
2. Generación de código QR
3. Guarda escanea/ingresa código para auto-completar ingreso
4. Expiración automática

### Fase 7: Correspondencia + Minutas
1. Registro de paquetes con fotos
2. Minutas digitales por turno
3. Notificaciones por email

### Fase 8: Reportes + Dashboard Admin
1. Dashboard administrativo con gráficos
2. Exportación Excel/CSV
3. Tests automatizados

---

## Plan de Verificación

```bash
# Tests automatizados
php artisan test --filter AccessLogTest
php artisan test --filter VisitorTest
php artisan test --filter PreAuthorizationTest

# Flujos manuales
# 1. Login como guardia → registrar ingreso → marcar salida
# 2. Login como anfitrión → crear pre-autorización
# 3. Login como admin → CRUD ubicaciones
# 4. QR: generar, escanear, validar expiración
```
