# 🛒 Sistema de Ventas - Artículos de Limpieza

Sistema completo de gestión de ventas con catálogo público, control de inventario, facturación y reportes.

## ✨ Características

- 🌐 Catálogo público sin autenticación
- 📦 Gestión completa de productos con imágenes
- 🏷️ Sistema de categorías con íconos personalizables
- 👥 Multi-roles (Admin, Vendedor, Repositor, Cliente)
- 💰 Registro de ventas y facturación
- 📊 Reportes con gráficos interactivos
- 🎨 Diseño minimalista (azul marino oscuro + Inter font)

## 🚀 Instalación Rápida

```bash
# 1. Clonar repositorio
git clone https://github.com/TU_USUARIO/sistema-ventas.git

# 2. Importar BD
mysql -u root -p sistema_ventas < database.sql

# 3. Configurar
cp config.example.php config.php
# Editar config.php con tus datos de MySQL

# 4. Crear carpeta uploads
mkdir -p uploads/productos
```

## 👤 Usuarios de Prueba

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@sistema.com | admin123 | Administrador |
| vendedor@sistema.com | admin123 | Vendedor |
| repositor@sistema.com | admin123 | Repositor |

⚠️ **Cambiar contraseñas en producción**

## 📋 Requisitos

- PHP 7.4+
- MySQL 8.0+
- Extensiones: mysqli, gd

## 🏷️ Categorías Incluidas

1. 🧹 Limpieza de Pisos
2. 🍽️ Vajilla y Cocina
3. 👕 Ropa y Lavandería
4. 🚽 Baño y Sanitarios
5. 🧴 Vidrios y Superficies
6. 🧼 Higiene Personal
7. 🛠️ Accesorios
8. 💉 Desinfectantes
9. 🧻 Papelería

## 📝 Licencia

MIT License - Ver LICENSE para más detalles
