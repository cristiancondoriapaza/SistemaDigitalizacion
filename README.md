# Sistema de Digitalización de Trámites Universitarios (Motor Workflow)

Este proyecto es un motor de workflow ligero desarrollado en PHP puro, diseñado para automatizar y gestionar los flujos de trámites universitarios (Postulación a Beca Comedor y Habilitación para Defensa de Grado). 

Su principal característica arquitectónica es prescindir de bases de datos relacionales tradicionales, utilizando en su lugar una estructura basada completamente en archivos planos (JSON) para la configuración de flujos, enrutamiento, roles y transaccionalidad.

## Requisitos del Sistema
* Servidor Web local (Apache).
* PHP 7.4 o superior.
* No requiere servidor MySQL.

## Instrucciones de Instalación y Ejecución

1. **Clonar el repositorio:**
   Descarga o clona este repositorio en tu máquina local.
   
2. **Ubicación de archivos:**
   Mueve la carpeta completa del proyecto al directorio público de tu servidor local. 
   * Si usas XAMPP: Colócalo dentro de `C:\xampp\htdocs\tramites_umsa`
   * Si usas Laragon: Colócalo dentro de `C:\laragon\www\tramites_umsa`

3. **Ejecución:**
   Abre el panel de control de tu servidor (XAMPP/Laragon) e inicia únicamente el servicio de **Apache**.

4. **Acceso a la plataforma:**
   Abre tu navegador web y dirígete a: `http://localhost/tramites_umsa/login.php`

## Credenciales de Prueba (Roles)
El sistema gestiona dinámicamente las bandejas según el rol. Puedes probar el flujo completo utilizando los siguientes usuarios preconfigurados (la contraseña para todos es `123`):

* **Estudiante (Inicia los trámites):** `CRISTIAN`
* **Trabajo Social (Revisión Beca):** `MARIA`
* **Comisión de Becas:** `COMISION`
* **Finanzas:** `JUAN`
* **Tutor (Revisión Tesis):** `TUTOR1`
* **Biblioteca (Solvencia):** `BIBLIOTECA`
* **Cajas (Solvencia):** `CAJAS`
* **Kardex (Resolución):** `ZULEMA`
* **Director (Firma):** `DIRECTOR`

## Estructura de Datos
El proyecto utiliza la carpeta `/datos/` para funcionar como base de datos transaccional:
* `flujo_proceso.json`: Diccionario de los 21 procesos estandarizados.
* `flujo_condicion.json`: Mapeo lógico de bifurcaciones y toma de decisiones.
* `seguimiento.json`: Archivo dinámico que registra la pista de auditoría de cada trámite.