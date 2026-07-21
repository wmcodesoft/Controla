# Panel Plataforma — Súper Admin

Documentación del panel `/admin`: dashboard operativo, ciclo comercial, archivo de cartera y retención legal de datos.

**Última actualización:** julio 2026

---

## Superficie y roles

| Prefijo | Rol | Permisos clave |
|---------|-----|----------------|
| `/admin` | `super-admin` | `platform.dashboard`, `platform.companies.view`, `platform.companies.manage` |

Layout: `resources/views/layouts/admin.blade.php` (acento **violet**).  
Guía visual: [`DISENO-UI-CONTROLA.md`](DISENO-UI-CONTROLA.md) §13.

---

## Dashboard (`GET /admin/dashboard`)

Pantalla principal de cartera viva. **Sin scroll de página**; scroll solo en paneles internos (mapa árbol y tabla detalle).

### Filtros (server-side, query params)

| Parámetro | Valores | Efecto |
|-----------|---------|--------|
| `alert` | `current`, `due_soon`, `overdue`, `archived` | Filtra empresas/conjuntos por bucket de alerta |
| `archive` | `recovery`, `cancelled` | Subfiltro cuando `alert=archived` |
| `company` | ID empresa | Drill-down de una empresa |
| `view` | `global` | Vista global (todas las filas de conjuntos) |

Alpine.js se usa **solo** para expand/collapse del árbol; el estado de filtros vive en la URL.

### Buckets de alerta (`CompanyAlertBucket`)

| Bucket | Regla |
|--------|--------|
| **Al día** | Suscripción activa, vence en más de 30 días |
| **Por vencer** | Vence en ≤ 30 días |
| **Vencidos** | Gracia vencida o estado `expired` |
| **Archivados** | `archived_at` o `subscription_status = suspended` |

Motor: `App\Support\Tenancy\CompanySubscriptionState`

### Acciones

| Acción | Ruta | Efecto |
|--------|------|--------|
| Archivar empresa (baja) | `POST /admin/companies/{company}/archive` | Suspende servicio, cascada `lifecycle = archived_company` en clientes |
| Retirar conjunto | `POST /admin/companies/{company}/clients/{client}/release` | `lifecycle = released`, libera cupo, datos en retención |
| Gestionar paquete | `GET /admin/companies/{company}` | Asignación de SKU y ciclo |

---

## Ciclo comercial de licencia

```
Venta → vigencia → gracia (30 días) → sin pago → suspender → archivo por recuperar
Cancelación voluntaria → archivo inmediato (motivo cancelled)
```

### Estados (`SubscriptionStatus`)

`active` · `grace` · `expired` · `suspended`

### Campos empresa (`security_companies`)

| Campo | Uso |
|-------|-----|
| `package_ends_at` | Fin de vigencia contratada |
| `grace_ends_at` | Fin del mes de gracia |
| `suspended_at` | Fecha de suspensión |
| `archived_at` | Fecha de archivo |
| `archive_reason` | `cancelled` \| `recovery` |

### Campos conjunto (`clients`)

| Campo | Uso |
|-------|-----|
| `lifecycle` | `active` · `released` · `archived_company` |
| `released_at` | Retiro de conjunto (empresa activa) |
| `archived_at` | Archivo en cascada por empresa |
| `tenant_data_purged_at` | Purga operativa completada |

### Cupo operativo

Solo cuentan clientes con `lifecycle = active`:

```php
SecurityCompany::operationalClientsCount()
```

`released` y `archived_company` **no consumen cupo**.

### Job automático

```bash
php artisan subscriptions:process-lifecycle
```

Programado: **diario a las 02:00** (`routes/console.php`).

Servicio: `App\Services\Platform\ProcessSubscriptionLifecycleService`

---

## Retención y purga legal de datos

> Orientación de diseño alineada a Ley 1581 de 2012 (Colombia). No constituye asesoría legal.

### Política implementada

| Tipo de dato | Retención | Acción |
|--------------|-----------|--------|
| **Operativo** (censo, visitantes, logs, estructuras) | 365 días tras retiro/archivo | Purga tablas tenant + anonimización del registro `clients` |
| **Comercial** (metadatos empresa archivada) | 5 años tras `archived_at` | Anonimización de PII en `security_companies` |

Configuración: `config/retention.php`

```env
RETENTION_CENSUS_DAYS=365
RETENTION_COMMERCIAL_YEARS=5
```

### Flujo de datos del conjunto

```
operativo (active)
  → retirado/archivado (released | archived_company)
  → retención read-only (sin portería)
  → purga (tenant_data_purged_at)
```

Tras la purga se conserva el registro `clients` con nombre anonimizado (`Conjunto purgado #ID`) para trazabilidad comercial; se eliminan filas en tablas con `client_id`.

### Tablas purgadas

Definidas en `config/retention.php` → `purge_tables`: access_logs, correspondence, pre_authorizations, visitor_pre_authorizations, guard_logs, blocklist, structure_*, visitors, residents, housing_units, buildings, locations, client_user_assignments.

### Job automático

```bash
php artisan data:purge-retention
```

Programado: **día 1 de cada mes a las 03:00**.

Servicios:
- `ProcessDataRetentionPurgeService` — orquesta elegibles
- `PurgeClientTenantDataService` — borra datos tenant y anonimiza conjunto

---

## Rutas completas

| Método | Ruta | Función |
|--------|------|---------|
| GET | `/admin/dashboard` | Resumen cartera + árbol + detalle |
| POST | `/admin/companies/{company}/archive` | Archivar empresa |
| POST | `/admin/companies/{company}/clients/{client}/release` | Retirar conjunto |
| GET | `/admin/pricing` | Tabla de precios |
| PUT | `/admin/pricing` | Guardar unitarios |
| GET | `/admin/companies` | Listado empresas |
| GET | `/admin/companies/{company}` | Detalle y cambio de paquete |
| PUT | `/admin/companies/{company}/package` | Asignar SKU y ciclo |

Archivo: `routes/modules/admin.php`

---

## Servicios y enums

```
app/Services/Platform/
├── PlatformDashboardService.php      # Datos dashboard + filtros
├── ArchiveCompanyService.php         # Archivo en cascada
├── ReleaseClientService.php          # Retiro de conjunto
├── ProcessSubscriptionLifecycleService.php
├── ProcessDataRetentionPurgeService.php
└── PurgeClientTenantDataService.php

app/Enums/
├── ArchiveReason.php                 # cancelled, recovery
├── ClientLifecycle.php               # active, released, archived_company
├── CompanyAlertBucket.php            # current, due_soon, overdue, archived
└── SubscriptionStatus.php            # incluye Suspended

app/Support/Tenancy/CompanySubscriptionState.php
```

---

## Migraciones (julio 2026)

| Archivo | Cambios |
|---------|---------|
| `2026_07_20_160000_add_address_to_clients_table.php` | Campo `address` en conjuntos |
| `2026_07_20_170000_add_archive_and_lifecycle_fields.php` | Archivo, gracia, lifecycle |
| `2026_07_20_180000_add_data_retention_purge_fields.php` | `tenant_data_purged_at`, `commercial_anonymized_at` |

```bash
php artisan migrate
```

---

## Tests

```bash
php artisan test --filter=PlatformDashboardTest
php artisan test --filter=DataRetentionPurgeTest
```

- `tests/Feature/Platform/PlatformDashboardTest.php`
- `tests/Unit/Platform/DataRetentionPurgeTest.php`

---

## Pendiente producto / legal

- Política de Tratamiento de Datos (PTD) publicada
- Contrato de encargo de tratamiento con empresas
- Exportación de datos al responsable antes de purga (opcional)
- Log de auditoría de acciones admin (archivar/retirar)
