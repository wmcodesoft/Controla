# Diseño UI — Controla (Panel Empresa v1)

Referencia oficial del sistema visual implementado en el **panel empresa** (`/company`). Usar este documento al migrar o crear vistas.

**Vista canónica (implementada):** `resources/views/modules/company/dashboard.blade.php`  
**Layout shell:** `resources/views/layouts/company.blade.php`  
**Mockup interactivo:** `canvases/company-dashboard-nav-mockup.canvas.tsx` (Cursor)

---

## 1. Componentes Blade (`x-ui.*`)

| Componente | Ruta | Uso |
|------------|------|-----|
| Botón | `resources/views/components/ui/button.blade.php` | `<x-ui.button>` |
| Label | `resources/views/components/ui/label.blade.php` | `<x-ui.label>` |
| Input | `resources/views/components/ui/input.blade.php` | `<x-ui.input>` |
| Error campo | `resources/views/components/ui/field-error.blade.php` | `<x-ui.field-error :messages="$errors->get('campo')" />` |

**Regla:** no inventar clases de botón/input en vistas nuevas del panel empresa; usar estos componentes.

### Ejemplo formulario

```blade
<div>
    <x-ui.label for="name">Nombre del conjunto</x-ui.label>
    <x-ui.input id="name" type="text" name="name" :value="old('name')" required />
    <x-ui.field-error :messages="$errors->get('name')" />
</div>

<x-ui.button type="submit" size="md">Guardar</x-ui.button>
```

### Ejemplo acciones header

```blade
<x-ui.button variant="secondary" :href="route('company.clients.select')">Portería</x-ui.button>
<x-ui.button :href="route('company.clients.create')">+ Conjunto</x-ui.button>
```

---

## 2. Tipografía — jerarquía (L1 → L6)

Fuente: **Figtree** (`font-sans`). Base operativa: **14px** (`text-sm`).

| Nivel | Tailwind | Peso | Color | Dónde |
|-------|----------|------|-------|-------|
| **L1** | `text-base` | `font-semibold` | `text-white` | Título de página en header nav |
| **L2** | `text-xs` | normal | `text-slate-500` | Subtítulo contexto (nombre empresa) |
| **L3** | `text-sm` | `font-semibold` | `text-white` | Título de sección (Conjuntos, Cuenta) |
| **L4** | `text-sm` | `font-medium` / normal | `text-slate-200` / `text-slate-400` | Cuerpo, celdas tabla |
| **L5** | `text-xs` | `font-medium` (thead) | `text-slate-500` | Meta, captions, encabezados tabla |
| **L6** | `text-xs` | `font-medium` | `text-slate-400` | Labels de formulario (`x-ui.label`) |
| **Métrica** | `text-base` | `font-semibold` | `text-white` + `tabular-nums` | Importes destacados (ej. próximo cargo) |
| **Mono** | `text-xs` | — | `font-mono text-indigo-300/90` | Login APP, códigos |

### Subniveles en tabla (L4)

- Celda primaria: `font-medium text-slate-200`
- Subtítulo (slug): `text-xs text-slate-600`

### Prohibido en panel empresa

- `text-2xl`, `text-3xl` en contenido operativo
- `uppercase tracking-widest` en botones
- Gradientes en datos/tablas/formularios

---

## 3. Botones (`x-ui.button`)

| Prop | Valores | Default |
|------|---------|---------|
| `variant` | `primary`, `secondary`, `success` | `primary` |
| `size` | `sm`, `md` | `sm` |
| `href` | URL opcional (renderiza `<a>`) | — |
| `type` | `button`, `submit`, `reset` | `button` |

### Tamaños

| Size | Altura | Clases |
|------|--------|--------|
| **sm** | 36px (`h-9`) | `h-9 px-4 text-sm font-medium` |
| **md** | 40px (`h-10`) | `h-10 px-5 text-sm font-semibold` |

### Variantes (panel empresa — acento indigo)

| Variant | Clases | Uso |
|---------|--------|-----|
| **primary** | `bg-indigo-600 text-white hover:bg-indigo-500` | CTA principal (+ Conjunto, Solicitar ampliación) |
| **secondary** | `border border-slate-700 text-slate-200 hover:bg-slate-800` | Acción secundaria (Portería) |
| **success** | `bg-emerald-600 text-white hover:bg-emerald-500` | Upsell ahorro (licencia anual) |

**Forma:** `rounded-lg` · `inline-flex items-center justify-center`

### Links de acción en tabla (no son botones)

| Acción | Clases |
|--------|--------|
| Ver | `text-xs text-indigo-400 hover:text-indigo-300` |
| Operar | `text-xs text-emerald-400 hover:text-emerald-300` |

---

## 4. Formularios

### Label (`x-ui.label`)

```
block text-xs font-medium text-slate-400 mb-1
```

### Input (`x-ui.input`)

```
w-full h-9 px-3 text-sm rounded-lg
border border-slate-700 bg-slate-950 text-white
placeholder:text-slate-600
focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30
```

### Error (`x-ui.field-error`)

```
mt-1 text-xs text-red-400
```

### Contenedor formulario

```
space-y-4 rounded-lg border border-slate-800 bg-slate-900/80 p-4
```

### Input compuesto (prefijo `usuario@`)

Misma altura `h-9`, borde compartido, focus en contenedor:

```
flex h-9 rounded-lg border border-slate-700 bg-slate-950
focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500/30
```

**Vistas de referencia:** `resources/views/modules/company/clients/create.blade.php`, `edit.blade.php`

---

## 5. Tablas

### Contenedor

```
rounded-lg border border-slate-800 overflow-hidden bg-slate-900/80
```

### Tabla

```
min-w-full text-sm
```

### Header (`thead`)

```
bg-slate-950/60 text-xs uppercase tracking-wide text-slate-500
th: px-4 py-2.5 text-left font-medium
```

### Body

```
tbody: divide-y divide-slate-800
tr: hover:bg-slate-800/30
td: px-4 py-3
```

### Empty state

```
px-4 py-10 text-center text-sm text-slate-500
```

---

## 6. Superficies y espaciado

| Elemento | Clases |
|----------|--------|
| Página (contenido) | `space-y-4` |
| Grid 8+4 | `grid grid-cols-1 lg:grid-cols-12 gap-4` |
| Franja contexto / licencia | `rounded-lg border border-slate-800 bg-slate-900/60 px-4 py-3` |
| Panel con secciones | `rounded-lg border border-slate-800 bg-slate-900/80 divide-y divide-slate-800` |
| Sección panel | `px-4 py-3` |
| Main layout | `max-w-7xl px-4 sm:px-6 lg:px-8 py-5` |
| Header nav | `py-3` |

### Barra de progreso (cupo)

```
Contenedor: h-1.5 rounded-full bg-slate-800
Relleno normal: bg-indigo-500
Relleno alerta (≥90%): bg-amber-500
```

---

## 7. Badges y estado

| Estado | Clases |
|--------|--------|
| Activo | `rounded-full bg-emerald-900/30 px-2 py-0.5 text-xs text-emerald-300` |
| Inactivo | `rounded-full bg-slate-800 px-2 py-0.5 text-xs text-slate-500` |
| Promo badge | `rounded-full bg-emerald-500/20 px-1.5 py-0.5 text-[10px] font-medium text-emerald-300` |
| Chip plan | `rounded-md border border-indigo-800/50 bg-indigo-900/30 px-2 py-0.5 text-xs text-indigo-200` |

### Estado suscripción (franja licencia)

- Activa: `text-emerald-400`
- Renovación próxima: `text-amber-400`
- Vencida: `text-red-400`

---

## 8. Zonas comerciales (panel Cuenta)

Misma estructura `px-4 py-3` — solo cambia color semántico:

| Zona | Fondo | Acento texto | CTA |
|------|-------|--------------|-----|
| Próximo cargo | neutro | blanco / slate-500 | — |
| Licencia anual | `bg-emerald-950/25` | `text-emerald-200` / `text-emerald-400` | `variant="success"` |
| Ampliar cupo | `bg-indigo-950/20` | `text-indigo-200` / `text-indigo-300` | `primary` |
| Incluido en plan | neutro | `text-indigo-300/80` + chips indigo | — |

---

## 9. Layout empresa — shell

**Archivo:** `resources/views/layouts/company.blade.php`  
**Composer:** `app/View/Composers/CompanyLayoutComposer.php` (inyecta `companyContext` para cupo en header)

### Header nav

- **Izquierda:** L1 título (`$title`) + L2 empresa (`$companyContext['company_name']`)
- **Derecha:** Portería (secondary) + Conjunto (primary, si cupo disponible)
- **Sin** Cerrar sesión en header

### Sidebar

- Ancho: `w-64`
- Nav item activo: `bg-indigo-600 text-white`
- Nav item idle: `text-slate-300 hover:bg-slate-800`
- Pie: nombre usuario + **Cerrar sesión** (`text-xs text-slate-500 hover:text-white`)

### Flash messages

```
success: rounded-lg bg-emerald-900/40 border border-emerald-700 text-emerald-200 px-4 py-3 text-sm
warning: rounded-lg bg-amber-900/40 border border-amber-700 text-amber-200 px-4 py-3 text-sm
```

---

## 10. Acentos por panel (futuro)

| Panel | Acento primary | Estado migración |
|-------|----------------|------------------|
| Empresa (`/company`) | `indigo` | ✅ Referencia actual |
| Plataforma (`/admin`) | `violet` | Pendiente |
| Conjunto (`/client`) | `teal` | Pendiente |
| Portería (`/access`) | `indigo` | Pendiente |

Al migrar admin/client/access: duplicar patrones de este doc cambiando solo el color acento en botones, focus de inputs y nav activo.

---

## 11. Checklist al migrar una vista

1. Usar `<x-company-layout title="...">` (título = L1 en header).
2. No duplicar botones Portería/Conjunto en contenido (van en layout).
3. Secciones con L3 (`text-sm font-semibold`).
4. Tablas según sección 5.
5. Formularios con `x-ui.label`, `x-ui.input`, `x-ui.field-error`, submit `size="md"`.
6. Sin `text-3xl`, sin gradientes, sin `rounded-2xl` en datos.
7. Comparar visualmente con `/company/dashboard`.

---

## 12. Vistas empresa — estado migración

| Vista | Estado |
|-------|--------|
| `company/dashboard` | ✅ Referencia completa |
| `company/clients/create` | ✅ Formulario UI |
| `company/clients/edit` | ✅ Formulario UI |
| `company/clients/index` | Pendiente |
| `company/clients/show` | Pendiente |
| `company/clients/select` | Pendiente |

---

*Última actualización: julio 2026 — alineado con resumen empresa aprobado.*
