# Proyecto API REST - Concesionaria

## Descripción general

Este proyecto implementa un servicio API REST para la gestión de una concesionaria de autos, desarrollado en PHP puro con una arquitectura funcional. El backend provee métodos para manejar usuarios, autos, clientes y ventas.

## Funcionalidades implementadas

Se crearon los siguientes métodos principales para la entidad **clientes** como ejemplo funcional:

- **GET**: Consulta lista de clientes o un cliente específico por ID.  
- **POST**: Agrega un nuevo cliente a la base de datos.  
- **PUT**: Edita los datos de un cliente existente.  
- **DELETE**: Elimina un cliente (requiere rol admin).  

Además, se implementó un sistema de autenticación con JSON Web Tokens (JWT) para proteger las rutas.

## Usuarios creados para pruebas

Se insertaron varios usuarios en la base de datos con contraseña `"123456"` hasheada con `password_hash()` de PHP.

como ejemplo, se creó el usuario:

- **Nombre**: jdavidUscamayta  
- **Email**: jdavidUscamayta@concesionaria.com  
- **Contraseña**: 123456 (guardada de forma segura hasheada)  
- **Rol**: vendedor  

Este usuario corresponde a mí para pruebas y demostraciones.

## Cómo probar la API con Postman

### Endpoint de login (POST)

URL:  
`http://localhost/concesionaria/backend/api/login.php`  

Body (raw JSON):

```json
{
  "email": "jdavidUscamayta@concesionaria.com",
  "password": "123456"
}

Obtener lista de clientes (GET)
URL:
http://localhost/concesionaria/backend/api/clientes.php

Headers:
Authorization: Bearer <token>

Agregar cliente (POST)
URL:
http://localhost/concesionaria/backend/api/clientes.php

Headers:
Authorization: Bearer <token>

Body (raw JSON):
{
  "nombre": "Juan",
  "apellido": "Pérez",
  "dni": "12345678",
  "email": "juan.perez@email.com",
  "telefono": "111234567",
  "direccion": "Av. Siempre Viva 123"
}

Editar cliente (PUT)
URL:
http://localhost/concesionaria/backend/api/clientes.php

Headers:
Authorization: Bearer <token>

Body (raw JSON):
{
  "id": 1,
  "nombre": "Juan Carlos",
  "apellido": "Pérez Gómez",
  "dni": "12345678",
  "email": "juan.perez@email.com",
  "telefono": "111234567",
  "direccion": "Av. Siempre Viva 456"
}

Conclusión
Con esta API REST se cumplen los requerimientos de crear un servicio con métodos GET, POST y PUT para consultar, agregar y modificar registros en la base de datos. La implementación de JWT garantiza seguridad y control de acceso.

Autor: J David Uscamayta R
