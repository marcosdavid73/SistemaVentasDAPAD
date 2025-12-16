# 👥 SISTEMA DE USUARIOS MULTI-ROL

## 📋 Descripción General

Sistema completo de gestión de usuarios con 4 niveles de acceso diferenciados, control de permisos granular y catálogo público sin necesidad de autenticación.

---

## 🌐 Estructura del Sistema

### Página Principal (Pública)
- **URL:** `http://localhost/sistema/` o `http://localhost/sistema/index.php`
- **Acceso:** Completamente público, sin necesidad de login
- **Contenido:** Catálogo de productos con búsqueda y filtros
- **Botón:** "Acceso Personal" para ir al login del sistema

### Panel de Control (Privado)
- **URL:** `http://localhost/sistema/dashboard.php`
- **Acceso:** Requiere autenticación
- **Roles:** Admin, Vendedor, Repositor (NO Cliente)

### Login del Sistema
- **URL:** `http://localhost/sistema/login.php`
- **Acceso:** Para personal autorizado
- **Botón:** "Volver al Catálogo" para regresar a la página pública

---

## 🎭 Roles y Permisos

### 1. **ADMINISTRADOR** (`admin`)
**Acceso total al sistema**

✅ **Permisos:**
- Gestión completa de usuarios (crear, editar, activar/desactivar)
- Acceso a todos los módulos
- Configuración del sistema
- Reportes completos
- Gestión de productos, clientes y proveedores
- Operaciones de ventas y caja

🔐 **Cuenta por defecto:**
- Email: `admin@sistema.com`
- Password: `admin123`

---

### 2. **VENDEDOR** (`vendedor`)
**Orientado a operaciones de venta**

✅ **Permisos:**
- ✅ Ventas (crear, ver, modificar)
- ✅ Clientes (gestión completa)
- ✅ Facturas (generar, consultar)
- ✅ Caja (operaciones diarias)
- ✅ Reportes (solo visualización)

❌ **Restricciones:**
- ❌ No puede gestionar productos
- ❌ No puede gestionar proveedores
- ❌ No puede gestionar usuarios
- ❌ No tiene acceso a configuración

🔐 **Cuenta de prueba:**
- Email: `vendedor@sistema.com`
- Password: `admin123`

---

### 3. **REPOSITOR** (`repositor`)
**Enfocado en gestión de inventario**

✅ **Permisos:**
- ✅ Productos (gestión completa: crear, editar, eliminar)
- ✅ Proveedores (gestión completa)
- ✅ Reportes de stock (solo visualización)

❌ **Restricciones:**
- ❌ No puede realizar ventas
- ❌ No puede gestionar clientes
- ❌ No tiene acceso a caja
- ❌ No puede ver reportes financieros
- ❌ No puede gestionar usuarios

🔐 **Cuenta de prueba:**
- Email: `repositor@sistema.com`
- Password: `admin123`

---

### 4. **CLIENTE** (`cliente`)
**Acceso público al catálogo - SIN NECESIDAD DE LOGIN**

✅ **Acceso:**
- ✅ Catálogo público en página principal (index.php)
- ✅ NO requiere crear cuenta ni iniciar sesión
- ✅ Filtrar por categorías
- ✅ Buscar productos
- ✅ Ver disponibilidad de stock

❌ **Restricciones:**
- ❌ No puede realizar ventas
- ❌ No puede ver precios (opcional)
- ❌ No tiene acceso a módulos internos
- ❌ Solo visualización, sin modificaciones

🌐 **Acceso:**
- URL directa: `http://localhost/sistema/` (página pública)
- No requiere credenciales

🔐 **Cuenta de prueba (opcional para personal):**
- Email: `cliente@sistema.com`
- Password: `admin123`

> **Nota:** El catálogo es 100% público. Las cuentas de cliente son opcionales para futuras funcionalidades como listas de deseos o historial.

---

## 🛠️ Funcionalidades del Sistema

### 📊 Panel de Administración de Usuarios
**Ubicación:** `usuarios.php` (solo para administradores)

#### Funciones disponibles:

1. **Crear Usuario**
   - Nombre completo
   - Email único
   - Contraseña (mínimo 6 caracteres)
   - Selección de rol
   - Estado activo por defecto

2. **Editar Usuario**
   - Modificar nombre y email
   - Cambiar rol
   - Actualizar contraseña (opcional)
   - Mantener historial de último acceso

3. **Activar/Desactivar Usuario**
   - Control de estado sin eliminar datos
   - Usuarios inactivos no pueden iniciar sesión
   - Protección: no se puede desactivar al admin principal

4. **Eliminar Usuario**
   - Eliminación permanente
   - Protección: no se puede eliminar al admin principal (ID=1)
   - Confirmación antes de eliminar

---

## 🔒 Sistema de Seguridad

### Autenticación
- Contraseñas hasheadas con `password_hash()` (bcrypt)
- Validación de credenciales con `password_verify()`
- Verificación de estado de usuario (activo/inactivo)
- Registro de último acceso en cada login

### Autorización
- Middleware `requiere_permiso($modulo)` en cada página
- Matriz de permisos centralizada en `config.php`
- Página de error 403 personalizada para accesos no autorizados
- Redirección automática según rol en login

### Control de Sesión
- `esta_logueado()`: Verifica si hay sesión activa
- `obtener_rol()`: Retorna el rol del usuario actual
- `tiene_permiso($modulo)`: Verifica acceso a módulo específico
- `actualizar_ultimo_acceso()`: Registra actividad del usuario

---

## 📁 Estructura de Base de Datos

### Tabla: `usuarios`

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'vendedor', 'repositor', 'cliente') DEFAULT 'cliente',
    estado TINYINT(1) DEFAULT 1 COMMENT '1=activo, 0=inactivo',
    ultimo_acceso TIMESTAMP NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_rol (rol),
    INDEX idx_estado (estado)
);
```

**Campos:**
- `id`: Identificador único
- `nombre`: Nombre completo del usuario
- `email`: Email único (usado para login)
- `password`: Hash bcrypt de la contraseña
- `rol`: Tipo de usuario (4 opciones)
- `estado`: 1=Activo, 0=Inactivo
## 🚀 Uso del Sistema

### Para Visitantes (Público)

1. **Ver el catálogo:**
   - Ir a `http://localhost/sistema/`
   - NO requiere login
   - Buscar y filtrar productos libremente
   - Ver stock disponible
1. **Acceder al panel de usuarios:**
   - Login como admin en `http://localhost/sistema/login.php`
   - Ir a menú lateral → Administración → Usuarios
   - URL: `http://localhost/sistema/usuarios.php`a/login.php`

### Para Administradores
---

## 🚀 Uso del Sistema

### Para Administradores

1. **Acceder al panel de usuarios:**
   - Login como admin
   - Ir a menú lateral → Administración → Usuarios
   - URL: `http://localhost/sistema/usuarios.php`

2. **Crear nuevo usuario:**
### Para Usuarios Finales

1. **Ver catálogo (público):**
   - Ir a `http://localhost/sistema/`
   - NO requiere login
   - Disponible para todos

2. **Login (solo personal autorizado):**
   - Ir a `http://localhost/sistema/login.php`
   - Ingresar email y contraseña
   - El sistema redirige automáticamente según rol:
     - Admin/Vendedor/Repositor → Dashboard (`dashboard.php`)
     - Cliente → Catálogo público (`index.php`)

3. **Navegación:**
   - El menú lateral muestra solo opciones permitidas
   - Intentar acceder a módulos restringidos muestra error 403
   - La sesión se mantiene hasta cerrar sesión

1. **Login:**
   - Ir a `http://localhost/sistema/`
   - Ingresar email y contraseña
   - El sistema redirige automáticamente según rol:
     - Admin/Vendedor/Repositor → Dashboard
     - Cliente → Catálogo de productos

2. **Navegación:**
   - El menú lateral muestra solo opciones permitidas
   - Intentar acceder a módulos restringidos muestra error 403
   - La sesión se mantiene hasta cerrar sesión

---

## 🎨 Interfaz Visual

### Badges de Rol
Cada rol tiene un color distintivo:

- 🟣 **Admin**: Degradado morado (`#667eea` → `#764ba2`)
- 🔴 **Vendedor**: Degradado rosa-rojo (`#f093fb` → `#f5576c`)
- 🔵 **Repositor**: Degradado azul cyan (`#4facfe` → `#00f2fe`)
- 🟢 **Cliente**: Degradado verde (`#43e97b` → `#38f9d7`)

### Indicadores de Estado
- 🟢 Círculo verde: Usuario activo
- 🔴 Círculo rojo: Usuario inactivo
### Nuevos Archivos
- `usuarios.php` - Panel de gestión de usuarios
- `dashboard.php` - Panel de control del sistema (antiguo index.php)
- `index.php` - Catálogo público (antiguo catalogo.php, ahora página principal)

### Archivos Modificados
- `config.php` - Funciones de permisos
- `login.php` - Validación de estado, último acceso y botón volver al catálogo
- `dashboard.php` - Menú dinámico y redirección por rol (antiguo index.php)
- `ventas.php` - Control de permisos
- `productos.php` - Control de permisos
- `clientes.php` - Control de permisos
- `proveedores.php` - Control de permisos
- `facturas.php` - Control de permisos
- `caja.php` - Control de permisos

---

## 🧪 Pruebas del Sistema

### Probar Acceso Público (SIN LOGIN)

1. **Abrir en modo incógnito:**
   - Ir a `http://localhost/sistema/`
   - Verificar que NO pide login
   - Buscar productos
   - Filtrar por categoría
   - Ver stock disponible
### Escenarios de Prueba

0. **Probar acceso público:**
   - Abrir navegador en modo incógnito
   - Ir a http://localhost/sistema/
   - Verificar acceso sin login
   - Probar búsqueda y filtros

1. **Probar permisos de vendedor:**o
   - Verificar redirección a login.php

### Credenciales de Prueba
---

## 🧪 Pruebas del Sistema

### Credenciales de Prueba
3. **Probar vista de cliente:**
   - ~~Ya NO es necesario login~~
   - El catálogo es público para todos
   - Ir a http://localhost/sistema/ directamente

4. **Probar desactivación de usuario:**dmin123 |
| Cliente | cliente@sistema.com | admin123 |

### Escenarios de Prueba

1. **Probar permisos de vendedor:**
   - Login como vendedor@sistema.com
## 📝 Matriz de Permisos

| Módulo | Admin | Vendedor | Repositor | Público |
|--------|-------|----------|-----------|---------|
| Catálogo (index.php) | ✅ | ✅ | ✅ | ✅ |
| Dashboard | ✅ | ✅ | ✅ | ❌ |
| Productos | ✅ | ❌ | ✅ | ❌ |
| Clientes | ✅ | ✅ | ❌ | ❌ |
| Proveedores | ✅ | ❌ | ✅ | ❌ |
| Ventas | ✅ | ✅ | ❌ | ❌ |
| Facturas | ✅ | ✅ | ❌ | ❌ |
| Caja | ✅ | ✅ | ❌ | ❌ |
| Cuentas Corrientes | ✅ | ✅ | ❌ | ❌ |
| Reportes | ✅ | ✅ (ver) | ✅ (ver) | ❌ |
| Usuarios | ✅ | ❌ | ❌ | ❌ |

> **Nota:** El catálogo (index.php) es la única página completamente pública sin restricciones.

---- Verificar mensaje de error apropiado

---

## 📝 Matriz de Permisos

| Módulo | Admin | Vendedor | Repositor | Cliente |
|--------|-------|----------|-----------|---------|
| Dashboard | ✅ | ✅ | ✅ | ❌ |
| Productos | ✅ | ❌ | ✅ | ❌ |
| Clientes | ✅ | ✅ | ❌ | ❌ |
| Proveedores | ✅ | ❌ | ✅ | ❌ |
| Ventas | ✅ | ✅ | ❌ | ❌ |
| Facturas | ✅ | ✅ | ❌ | ❌ |
| Caja | ✅ | ✅ | ❌ | ❌ |
| Cuentas Corrientes | ✅ | ✅ | ❌ | ❌ |
| Reportes | ✅ | ✅ (ver) | ✅ (ver) | ❌ |
| Usuarios | ✅ | ❌ | ❌ | ❌ |
| Catálogo | ✅ | ✅ | ✅ | ✅ |

---

## 🆘 Solución de Problemas

### Usuario no puede iniciar sesión
1. Verificar que el usuario esté activo (estado=1)
2. Verificar email y contraseña
3. Verificar en tabla usuarios: `SELECT * FROM usuarios WHERE email='...'`

### Error 403 al acceder a módulo
1. Verificar rol del usuario: `SELECT rol FROM usuarios WHERE email='...'`
2. Revisar matriz de permisos en `config.php`
3. Verificar que el módulo esté en el array de permisos del rol

### No aparece menú de Usuarios
1. Solo visible para rol 'admin'
2. Verificar sesión: `$_SESSION['rol']` debe ser 'admin'

---

## 🔄 Extensiones Futuras Sugeridas

- [ ] Auto-registro para clientes con aprobación admin
- [ ] Recuperación de contraseña por email
- [ ] Auditoría completa de acciones por usuario
- [ ] Permisos granulares por módulo (crear, editar, eliminar)
- [ ] Autenticación de dos factores (2FA)
- [ ] Gestión de tokens para API
- [ ] Roles personalizados dinámicos

---

## 📞 Soporte

Para consultas sobre el sistema de usuarios:
- Revisar este documento
- Verificar `config.php` para lógica de permisos
- Consultar tabla `usuarios` en base de datos

**Autor:** Sistema creado automáticamente
**Versión:** 1.0
**Fecha:** 2024
