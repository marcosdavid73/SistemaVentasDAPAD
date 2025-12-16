# 🐳 Instalación con Docker (OPCIÓN PROFESIONAL)

## ¿Por qué Docker?
- ✅ No ensucia tu sistema
- ✅ Funciona igual en cualquier PC
- ✅ Portátil y reproducible
- ✅ Aislado del resto del sistema

## Requisitos
- Windows 10/11 (64-bit)
- 4GB RAM mínimo

## Pasos de Instalación

### 1. Instalar Docker Desktop
1. Ve a: https://www.docker.com/products/docker-desktop/
2. Descarga **Docker Desktop for Windows**
3. Ejecuta el instalador
4. Reinicia tu PC cuando lo pida
5. Abre Docker Desktop y espera a que inicie

### 2. Crear los Archivos de Configuración
Ya están creados en tu proyecto:
- `docker-compose.yml` ✅
- `Dockerfile` ✅

### 3. Iniciar el Sistema
Abre **CMD** o **PowerShell** en la carpeta del proyecto y ejecuta:

```bash
docker-compose up -d
```

Espera 2-3 minutos mientras descarga e instala todo.

### 4. Importar la Base de Datos

**Opción A: Desde el navegador**
1. Ve a: http://localhost:8080 (phpMyAdmin)
2. Usuario: `root`, Contraseña: `root123`
3. Click en "Importar"
4. Selecciona `sistema_ventas.sql`
5. Click en "Continuar"

**Opción B: Desde terminal**
```bash
docker exec -i sistema-mysql mysql -uroot -proot123 sistema_ventas < sistema_ventas.sql
```

### 5. Acceder al Sistema
```
http://localhost:8000/login.php
```

## 🔐 Credenciales
- **Sistema:** admin@sistema.com / admin123
- **phpMyAdmin:** root / root123

## 📝 Comandos Útiles

```bash
# Iniciar servicios
docker-compose up -d

# Detener servicios
docker-compose down

# Ver logs
docker-compose logs -f

# Reiniciar todo
docker-compose restart

# Ver contenedores corriendo
docker ps
```

## 🛠️ Solución de Problemas

### Puerto 8000 ocupado
Cambia el puerto en `docker-compose.yml`:
```yaml
ports:
  - "8001:80"  # Cambia 8000 por 8001
```

### Error: "Docker daemon not running"
- Abre Docker Desktop
- Espera a que termine de iniciar

---

**Ventaja:** Todo está aislado, no afecta tu sistema Windows.
