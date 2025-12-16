# 💻 Instalación SOLO con PHP (Servidor Integrado)

## ¿Cuándo usar esto?
- Solo quieres probar rápido
- No quieres instalar nada pesado
- Solo para desarrollo/pruebas (NO para producción)

## Requisitos
- Descargar PHP portable
- Tener MySQL instalado (o usar SQLite)

## Opción 1: PHP + MySQL Portable

### 1. Descargar PHP Portable
1. Ve a: https://windows.php.net/download/
2. Descarga: **PHP 8.2 Thread Safe (x64) ZIP**
3. Descomprime en: `C:\php`

### 2. Agregar PHP al PATH
1. Busca "variables de entorno" en Windows
2. Click en "Variables de entorno"
3. En "Variables del sistema", busca "Path"
4. Click en "Editar"
5. Click en "Nuevo"
6. Agrega: `C:\php`
7. Click en "Aceptar" en todo

### 3. Configurar PHP
1. Ve a `C:\php`
2. Copia `php.ini-development` y renómbralo a `php.ini`
3. Abre `php.ini` con Notepad
4. Busca `;extension=mysqli` y quita el `;`
5. Busca `;extension=pdo_mysql` y quita el `;`
6. Guarda y cierra

### 4. Descargar MySQL Portable
1. Ve a: https://dev.mysql.com/downloads/mysql/
2. Descarga: MySQL Community Server (ZIP Archive)
3. Descomprime en: `C:\mysql`

### 5. Iniciar MySQL
Abre CMD como Administrador:
```cmd
cd C:\mysql\bin
mysqld --console
```
Deja esta ventana abierta.

### 6. Crear la Base de Datos
Abre OTRA ventana CMD:
```cmd
cd C:\mysql\bin
mysql -u root -p
```
(Enter sin contraseña)

Luego ejecuta:
```sql
CREATE DATABASE sistema_ventas;
USE sistema_ventas;
SOURCE C:\Users\David\Downloads\sistema\sistema\sistema_ventas.sql;
EXIT;
```

### 7. Iniciar el Servidor PHP
Abre CMD en la carpeta del proyecto:
```cmd
cd C:\Users\David\Downloads\sistema\sistema
php -S localhost:8000
```

### 8. Acceder
```
http://localhost:8000/login.php
```

---

## Opción 2: PHP + SQLite (MÁS SIMPLE)

**Ventaja:** No necesitas MySQL, usa SQLite (base de datos en archivo)

### 1. Descargar PHP (igual que arriba)

### 2. Modificar config.php
Reemplaza todo por:
```php
<?php
// Usar SQLite en lugar de MySQL
try {
    $db = new PDO('sqlite:sistema_ventas.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
```

**PROBLEMA:** Requeriría convertir TODAS las queries de MySQLi a PDO.

---

## ⚠️ PROBLEMAS DE ESTA OPCIÓN
- Más complicado de configurar
- Requiere descargar 2 cosas por separado
- Más propenso a errores
- MySQL hay que iniciarlo manualmente

## 🎯 RECOMENDACIÓN
**Usa Laragon** → Es más fácil que todo esto y ya incluye todo.

---

**Solo usa esta opción si de verdad no puedes instalar Laragon o Docker.**
