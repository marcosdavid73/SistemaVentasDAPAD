# 🚀 Instalación con Laragon (OPCIÓN MÁS FÁCIL)

## ¿Por qué Laragon?
- ✅ Instalación de 1 clic
- ✅ No requiere configuración
- ✅ Más rápido que XAMPP
- ✅ Auto-crea dominios locales
- ✅ Solo 50MB

## Pasos de Instalación

### 1. Descargar Laragon
Ve a: https://laragon.org/download/
- Descarga **Laragon - Full** (incluye Apache, MySQL, PHP)
- Archivo: ~160MB

### 2. Instalar Laragon
1. Ejecuta el instalador descargado
2. Acepta la ubicación por defecto: `C:\laragon`
3. Haz clic en **Next → Next → Install**
4. Espera 2-3 minutos
5. ✅ ¡Listo!

### 3. Iniciar Laragon
1. Abre **Laragon** desde el escritorio o menú inicio
2. Haz clic en **Iniciar Todo** (botón grande)
3. Espera que Apache y MySQL se pongan en verde 🟢

### 4. Importar el Proyecto
1. **Opción A: Copiar carpeta**
   - Copia la carpeta `sistema` completa
   - Pégala en: `C:\laragon\www\`
   - Resultado: `C:\laragon\www\sistema\`

2. **Opción B: Mover todo**
   - Mueve TODOS los archivos PHP a `C:\laragon\www\sistema\`

### 5. Crear la Base de Datos
1. En Laragon, haz clic en **Base de datos**
2. Se abrirá HeidiSQL (gestor de base de datos)
3. En el menú: **Archivo → Ejecutar archivo SQL**
4. Busca y selecciona: `sistema_ventas.sql`
5. Haz clic en **Abrir**
6. Espera a que termine la importación ✅

### 6. Acceder al Sistema
Abre tu navegador y ve a:
```
http://localhost/sistema/login.php
```

O si Laragon creó el dominio automático:
```
http://sistema.test/login.php
```

## 🔐 Credenciales de Acceso
- **Email:** `admin@sistema.com`
- **Contraseña:** `admin123`

---

## ⚡ Ventajas de Laragon vs XAMPP
- ✓ Instalación automática
- ✓ No necesita configurar nada
- ✓ Más rápido
- ✓ Interfaz más simple
- ✓ Auto-crea dominios locales (.test)
- ✓ Actualiza PHP fácilmente

## 🛠️ Solución de Problemas

### No puedo acceder a localhost
- Verifica que Apache esté en verde 🟢 en Laragon
- Haz clic en **Reiniciar Todo**

### Error de base de datos
- Verifica que MySQL esté en verde 🟢
- Abre HeidiSQL y verifica que la BD `sistema_ventas` exista

### Página en blanco
- Verifica que los archivos estén en `C:\laragon\www\sistema\`
- Verifica que `config.php` esté en la carpeta

---

**¡En 5 minutos está funcionando!** ⚡
