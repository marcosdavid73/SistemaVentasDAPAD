# Sistema de Ventas para Artículos de Limpieza

Hola! Este es un sistema que hice para gestionar ventas de productos de limpieza. Incluye un catálogo público donde cualquiera puede ver los productos, y un panel de administración para manejar todo el inventario, ventas y reportes.

## Qué necesitas tener instalado

Básicamente necesitas un servidor local, yo lo armé con Laragon pero funciona perfecto con XAMPP también:
- PHP 7.4 o más nuevo
- MySQL 
- Un servidor Apache

## Cómo instalarlo

### Paso 1: Descargar XAMPP

Si no tenes ningún servidor local, bajate XAMPP de acá: https://www.apachefriends.org/

Una vez instalado, abrí el panel de control y arrancá Apache y MySQL.

### Paso 2: Crear la base de datos

1. Andá a http://localhost/phpmyadmin en tu navegador
2. Hacé click en "Nueva" para crear una base nueva
3. Ponele de nombre: `sistema_ventas`
4. En "Cotejamiento" elegí `utf8mb4_general_ci` 
5. Dale a "Crear"
6. Ahora entrá a la base que creaste
7. Andá a la pestaña "Importar"
8. Elegí el archivo `database.sql` que está en la carpeta del proyecto
9. Dale a "Continuar" y esperá que termine de importar

Listo, ya tenés toda la estructura de tablas, usuarios de prueba y las categorías cargadas.

### Paso 3: Configurar la conexión

Hay un archivo que se llama `config.example.php`, lo tenés que copiar y renombrar a `config.php`.

Después abrilo y fijate estos datos:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_ventas');
```

Si tu MySQL tiene otro usuario o contraseña, cambialos ahí. Por defecto XAMPP usa `root` sin contraseña.

### Paso 4: Poner los archivos

Copiá toda la carpeta del proyecto y pegala en:
- Si usas XAMPP en Windows: `C:\xampp\htdocs\sistema`
- Si usas Laragon: `C:\laragon\www\sistema`
- Mac: `/Applications/XAMPP/htdocs/sistema`

### Paso 5: Entrar al sistema

Abrí el navegador y andá a:
```
http://localhost/sistema/login.php
```

## 🔐 Credenciales de Acceso

**Usuario administrador por defecto:**
- **Email:** `admin@sistema.com`
- **Contraseña:** `admin123`

> ⚠️ **IMPORTANTE:** Cambia la contraseña después del primer inicio de sesión

## 📊 Estructura de la Base de Datos

El sistema incluye las siguientes tablas:

- ✅ `usuarios` - Gestión de usuarios del sistema
- ✅ `clientes` - Registro de clientes
- ✅ `productos` - Catálogo de productos
- ✅ `categorias` - Categorías de productos
- ✅ `ventas` - Registro de ventas
- ✅ `detalle_ventas` - Detalles de cada venta
- ✅ `facturas` - Facturación
- ✅ `detalle_facturas` - Detalles de facturas
- ✅ `proveedores` - Gestión de proveedores
- ✅ `compras` - Registro de compras
- ✅ `detalle_compras` - Detalles de compras
- ✅ `cuentas_corrientes` - Cuentas corrientes de clientes
- ✅ `movimientos_caja` - Movimientos de caja
- ✅ `gastos` - Registro de gastos
- ✅ `pagos` - Gestión de pagos

## 🛠️ Solución de Problemas

### Error: "No se puede conectar a la base de datos"
- Verifica que MySQL esté activo en XAMPP
- Verifica que las credenciales en `config.php` sean correctas
- Asegúrate de que la base de datos `sistema_ventas` exista

### Error: "Página en blanco"
- Verifica que Apache esté activo
- Revisa los logs de error de PHP en: `xampp/apache/logs/error.log`
- Habilita la visualización de errores en `php.ini`:
  ```ini
  display_errors = On
  error_reporting = E_ALL
  ```

### Error: "Call to undefined function..."
- Verifica que las extensiones de PHP estén habilitadas en `php.ini`:
  - `extension=mysqli`
  - `extension=mbstring`

### No redirige al login
- Asegúrate de que `session_start()` esté funcionando
- Verifica permisos de escritura en la carpeta de sesiones

## 📱 Módulos del Sistema

- 📊 **Dashboard** - Resumen general de ventas y estadísticas
- 👥 **Clientes** - Gestión de clientes
- 📦 **Productos** - Administración de productos e inventario
- 🛒 **Ventas** - Punto de venta
- 📄 **Facturas** - Facturación y comprobantes
- 🏢 **Proveedores** - Gestión de proveedores
- 💰 **Caja** - Movimientos de caja
- 💳 **Cuentas Corrientes** - Estado de cuenta de clientes
- 📈 **Reportes** - Informes y estadísticas

## 🔧 Configuración Adicional

### Cambiar la Zona Horaria
En `config.php`, puedes agregar:
```php
date_default_timezone_set('America/Argentina/Buenos_Aires');
```

### Habilitar Modo Debug
Para ver errores detallados, descomenta en `config.php`:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## 📞 Soporte

Si encuentras algún error:
1. Verifica los logs de Apache: `xampp/apache/logs/error.log`
2. Revisa los errores de PHP en tu navegador (F12 → Console)
3. Asegúrate de que todos los archivos se hayan copiado correctamente

## ✅ Checklist de Instalación

- [ ] XAMPP instalado y servicios activos
- [ ] Base de datos `sistema_ventas` creada
- [ ] Archivo SQL importado correctamente
- [ ] Archivos copiados en `htdocs`
- [ ] `config.php` configurado
- [ ] Login accesible desde el navegador
- [ ] Credenciales de prueba funcionando

---

**¡Listo! El sistema ya está funcionando.** 🎉
