# mpet
Proyecto Personal de Mario

**Repositorio:** https://github.com/SeSoDi/mpet  
**URL de Producción:** http://mpet.ccorporativo.mx

## Descripción General
Una aplicación Laravel con sistema de control de acceso basado en roles (RBAC) que incluye interfaz basada en permisos y gestión de usuarios.

## Características
- **Control de Acceso Basado en Roles**: Sistema RBAC completo con roles y permisos
- **Interfaz Basada en Permisos**: Navegación dinámica y botones de acción según permisos del usuario
- **Gestión de Usuarios**: Crear, editar y gestionar usuarios con asignación de roles
- **Localización en Español**: Descripciones de roles y permisos en español
- **Comandos Artisan Personalizados**: Utilidades CLI para gestión de usuarios

## Instalación

### Instalación Inicial
```bash
composer install
npm install
```

### Configuración del Entorno
```bash
cp .env.example .env
php artisan key:generate
```

**Variables importantes a configurar en `.env`:**

```bash
# Base de datos MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mpet_database
DB_USERNAME=tu_usuario_mysql
DB_PASSWORD=tu_password_mysql

# Contraseña para usuarios de prueba (requerido para seeding)
ROOT_USER_PASSWORD=tu_contraseña_segura_aqui

# Configuración de la aplicación
APP_NAME="MPET"
APP_URL=http://localhost:8000

# En producción, cambiar a:
APP_ENV=production
APP_DEBUG=false
APP_URL=http://mpet.ccorporativo.mx
```

> **Nota:** La variable `ROOT_USER_PASSWORD` es necesaria para crear los usuarios de prueba durante el seeding. Si no se configura, los usuarios no serán creados.

### Configuración de Base de Datos
```bash
php artisan migrate:fresh --seed
```

Esto creará las tablas de la base de datos y las poblará con:
- Roles por defecto (SuperAdmin, Admin, Manager, User)
- Permisos con descripciones en español
- Usuarios de prueba (si `ROOT_USER_PASSWORD` está configurado en .env)

### Usuarios por Defecto
Si tienes `ROOT_USER_PASSWORD` configurado en tu archivo `.env`, el seeder creará:
- **root** (SuperAdmin): `root@notAnyDomain.local`
- **admin** (Admin): `admin@example.local`

Ambos usuarios usarán la contraseña de la variable de entorno `ROOT_USER_PASSWORD`.

## Comandos Artisan Personalizados

### Resetear Contraseña de Usuario
Resetea la contraseña de cualquier usuario usando su nombre de usuario:

```bash
# Modo interactivo (solicita contraseña)
php artisan user:reset-password <nombre_usuario>

# Proporcionar contraseña en línea
php artisan user:reset-password <nombre_usuario> --password=<nueva_contraseña>
```

**Ejemplos:**
```bash
# Resetear contraseña de admin interactivamente
php artisan user:reset-password admin

# Resetear contraseña de root con contraseña en línea
php artisan user:reset-password root --password=NuevaContraseñaSegura123
```

**Características:**
- Usa nombre de usuario en lugar de email para simplicidad
- Solicitudes interactivas de contraseña con confirmación
- Validación de contraseña (mínimo 8 caracteres)
- Muestra información del usuario antes de confirmar
- Solicitud de confirmación para prevenir resets accidentales

## Desarrollo

### Iniciar Servidor de Desarrollo
```bash
composer run dev
```

Esto inicia:
- Servidor de desarrollo Laravel (http://127.0.0.1:8000)
- Worker de cola
- Monitoreo de logs
- Servidor de desarrollo Vite

### Sistema de Roles y Permisos

La aplicación incluye un sistema RBAC completo:

#### Roles
- **SuperAdmin**: Acceso completo al sistema (omite todas las verificaciones de permisos)
- **Admin**: Acceso administrativo a la mayoría de funciones
- **Manager**: Permisos de nivel gerencial
- **User**: Permisos básicos de usuario

#### Permisos
Todos los permisos tienen descripciones en español y controlan:
- Gestión de usuarios (ver, crear, editar, eliminar)
- Gestión de roles (ver, crear, editar, eliminar)
- Gestión de permisos (ver, crear, editar, eliminar)
- Acceso general al sistema

#### Interfaz Basada en Permisos
La interfaz se adapta dinámicamente según los permisos del usuario:
- Los elementos del menú de navegación se muestran/ocultan según permisos
- Los botones de acción (Crear, Editar, Eliminar) aparecen condicionalmente
- Los formularios y componentes respetan las capacidades del usuario

### Ejemplo Práctico: Página de Gestión de Usuarios

La página `/users` (`resources/js/pages/Users/Index.vue`) es un ejemplo perfecto de cómo funciona la seguridad basada en permisos:

#### 🔒 **Control de Acceso a Nivel de Página**
```typescript
// El usuario debe tener permiso 'users.view' para acceder a la página
// Verificado en el middleware del backend antes de mostrar la página
```

#### 🎛️ **Control de Elementos de Interfaz**
```vue
<script setup>
import { usePermissions } from '@/composables/usePermissions';
const { hasPermission } = usePermissions();
</script>

<template>
  <!-- Botón "Crear Usuario" solo aparece si tiene permiso -->
  <Link v-if="hasPermission('users.create')" href="/users/create">
    Crear Usuario
  </Link>
  
  <!-- Botones de acción por fila -->
  <td class="actions">
    <Link v-if="hasPermission('users.view')" :href="`/users/${user.id}`">
      Ver
    </Link>
    <Link v-if="hasPermission('users.edit')" :href="`/users/${user.id}/edit`">
      Editar
    </Link>
    <button v-if="hasPermission('users.delete')" @click="deleteUser(user)">
      Eliminar
    </button>
  </td>
</template>
```

#### 🔐 **Comportamiento por Rol**

| Rol | Ver Usuarios | Crear | Editar | Eliminar | Resultado en UI |
|-----|-------------|-------|--------|----------|-----------------|
| **SuperAdmin** | ✅ | ✅ | ✅ | ✅ | Ve todos los botones |
| **Admin** | ✅ | ✅ | ✅ | ✅ | Ve todos los botones |
| **Manager** | ✅ | ✅ | ✅ | ❌ | No ve botón "Eliminar" |
| **User** | ❌ | ❌ | ❌ | ❌ | No puede acceder a la página |

#### 🛡️ **Capas de Seguridad**

1. **Backend (Laravel)**: Las rutas están protegidas con middleware que verifica permisos
2. **Frontend (Vue)**: Los elementos se ocultan/muestran según permisos del usuario
3. **Base de Datos**: Los permisos se verifican antes de cualquier operación

#### 🔄 **Funcionamiento Reactivo**
- Los permisos se cargan automáticamente al iniciar sesión
- La interfaz se actualiza inmediatamente al cambiar roles/permisos
- No requiere recarga de página para reflejar cambios de permisos

## API y Rutas

### Autenticación
La aplicación usa Laravel Fortify para autenticación.

### Rutas Protegidas
Todas las rutas administrativas están protegidas y respetan el sistema de permisos.

## Contribuciones

Este es el proyecto personal de Mario para aprender y experimentar con características de Laravel.

## Enlaces Útiles

- **Repositorio GitHub**: https://github.com/SeSoDi/mpet
- **Aplicación en Producción**: http://mpet.ccorporativo.mx
- **Documentación Laravel**: https://laravel.com/docs
- **Laravel Fortify**: https://laravel.com/docs/fortify