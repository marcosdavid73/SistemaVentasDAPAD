# Sistema de Ventas - Artículos de Limpieza

Sistema web que armé para gestionar la venta de productos de limpieza. Tiene un catálogo donde la gente puede ver todos los productos disponibles sin necesidad de registrarse, y un panel de administración completo para manejar stock, ventas y reportes.

## Qué incluye

- Catálogo público donde cualquiera puede ver los productos
- Administración de productos con fotos
- Categorías organizadas por tipo de artículo con iconos
- Diferentes roles de usuario (administrador, vendedor, repositor)
- Sistema de ventas y facturación
- Reportes con gráficos 
- Diseño minimalista en azul oscuro

## Instalación rápida

```bash
# Clonar el repo
git clone https://github.com/marcosdavid73/SistemaVentasDAPAD.git

# Importar la base de datos
# Usá phpMyAdmin o desde consola:
mysql -u root -p sistema_ventas < database.sql

# Configurar
# Copiá config.example.php a config.php y editalo con tus datos de MySQL

# Listo! Entrá a http://localhost/sistema/login.php
```

## Usuarios para probar

Ya vienen algunos usuarios cargados en la base de datos:

- **Admin**: admin@sistema.com / admin123
- **Vendedor**: vendedor@sistema.com / admin123  
- **Repositor**: repositor@sistema.com / admin123

(Obviamente cambiales la contraseña si vas a usarlo en serio)

## Requisitos

- PHP 7.4 o más nuevo
- MySQL
- Apache (XAMPP, Laragon o similar)

## Categorías

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
