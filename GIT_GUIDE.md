# Comandos Git que uso

Acá dejo algunos comandos de Git que me fueron útiles por si los necesito después.

## Para empezar

```bash
# Iniciar git en una carpeta
git init

# Ver qué archivos están listos para subir
git status

# Agregar todos los archivos
git add .

# Hacer el primer commit
git commit -m "Primer commit del sistema"

# Conectar con GitHub (primero crear el repo en GitHub)
git remote add origin https://github.com/tuusuario/tuproyecto.git

# Subir todo
git push -u origin master
```

## Comandos del día a día

```bash
# Ver cambios pendientes
git status

# Agregar un archivo específico
git add miarchivo.php

# Hacer commit de los cambios
git commit -m "Arreglé tal cosa"

# Subir a GitHub
git push

# Bajar cambios si trabajas en equipo
git pull

# Ver el historial
git log --oneline
```

## Trabajar con ramas

```bash
# Crear una rama nueva
git checkout -b nueva-funcionalidad

# Cambiar de rama
git checkout master

# Ver todas las ramas
git branch
```

Nada muy complicado, lo básico para no perder el código.

# ==========================================
# NOTAS IMPORTANTES
# ==========================================

1. El archivo config.php NO se sube (está en .gitignore)
2. La carpeta uploads/ NO se sube (imágenes locales)
3. Usa config.example.php como plantilla
4. Recuerda cambiar contraseñas en producción
