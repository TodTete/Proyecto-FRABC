# Sistema de Gestión de Documentos

Este proyecto implementa un sistema integral para la gestión, envío y seguimiento de documentos entre usuarios autenticados. Ha sido desarrollado bajo el patrón arquitectónico Cliente-Servidor y adopta la metodología ágil Kanban para su organización y evolución.

## Funcionalidades Principales

- **Autenticación**: Módulo de inicio de sesión para el acceso seguro de usuarios.
- **Gestión de Usuarios**: Crear, editar, actualizar y eliminar usuarios del sistema.
- **Panel de Control (Dashboard)**: Acceso a secciones clave como notificaciones, perfil y gestión de documentos.
- **Carga de Documentos**: Subida de archivos PDF con campos complementarios y opción para etiquetar usuarios destinatarios.
- **Sistema de Estados por Documento**:
  - **En proceso** (Rojo): Documento enviado, aún no visualizado.
  - **Pendiente** (Amarillo): Visualizado pero no marcado como leído.
  - **Entregado** (Verde): Confirmado como leído.
- **Tipo de Envío**:
  - **Urgente**: Identificado con un ícono de estrella.
  - **Ordinario**: Valor por defecto.
- **Notificaciones**: Los usuarios etiquetados recibirán alertas al ser incluidos en un envío.
- **Búsqueda Avanzada**: Filtro de documentos por folio, fecha, carrera y tipo.

## Requisitos Técnicos

- **Base de Datos**: MariaDB (SQL)
- **Frontend**: React Native (Web/Móvil)
- **Backend**: Node.js + Express
- **Pruebas**: Playwright
- **Editor recomendado**: Visual Studio / Cursor
- **Patrones utilizados**: MVC + Repository Pattern
- **Control de versiones**: Git
- **Arquitectura**: Cliente - Servidor
- **Metodología de desarrollo**: Kanban

---

Este proyecto tiene como objetivo optimizar el flujo de información entre usuarios mediante un sistema de gestión documental claro, accesible y seguro.
