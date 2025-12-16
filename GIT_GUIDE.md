# ==========================================
# GUÍA PARA SUBIR A GIT
# ==========================================

# 1. Inicializar repositorio (si aún no está inicializado)
git init

# 2. Agregar todos los archivos (respeta .gitignore)
git add .

# 3. Ver qué archivos se van a subir
git status

# 4. Hacer el primer commit
git commit -m "Initial commit: Sistema de ventas completo con categorías"

# 5. Conectar con GitHub (crear repositorio primero en GitHub)
git remote add origin https://github.com/TU_USUARIO/sistema-ventas.git

# 6. Subir todo
git push -u origin master

# ==========================================
# COMANDOS ÚTILES
# ==========================================

# Ver estado del repositorio
git status

# Ver historial de commits
git log --oneline

# Crear una nueva rama
git checkout -b nombre-rama

# Agregar solo archivos específicos
git add archivo.php

# Commit con mensaje
git commit -m "Descripción del cambio"

# Push a GitHub
git push

# Pull (descargar cambios)
git pull

# Ver archivos ignorados
git status --ignored

# ==========================================
# NOTAS IMPORTANTES
# ==========================================

1. El archivo config.php NO se sube (está en .gitignore)
2. La carpeta uploads/ NO se sube (imágenes locales)
3. Usa config.example.php como plantilla
4. Recuerda cambiar contraseñas en producción
