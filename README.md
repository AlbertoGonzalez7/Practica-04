# Pràctica 04 - Inici d'usuaris i registre de sessions - Alberto González

## Testejar el projecte:
- [Enllaç per provar](https://xampp.garc.pro/www/practiques/Practica-04-alberto/index.php)

## Usuari de Prova
- **Usuari**: Xavi 
- **Contrasenya**: P@ssw0rd

## Descripció
Aquest projecte és una aplicació web que permet als usuaris registrar-se i iniciar sessió. Els usuaris poden veure tots els articles creats quan no estan loguejats. Un cop inicien sessió, només poden veure els articles que ells mateixos han creat. A més, els usuaris tenen la capacitat d'inserir, modificar i eliminar els seus propis articles.

## Temàtica
La temàtica del projecte està inspirada en el videojoc **Clash of Clans**.

## Característiques

- **Registre i Inici de Sessió**: Els usuaris poden registrar-se i després iniciar sessió per accedir als seus articles.
- **Control d'Articles**: Els usuaris poden inserir, modificar i eliminar només els articles que ells han creat.
- **Seguretat**:
  - No es guarda la contrasenya en el formulari per motius de seguretat.
  - La contrasenya es guarda de forma encriptada.
  - La contrasenya ha de complir els següents requisits:
    - Un mínim de 8 caràcters.
    - Almenys una lletra majúscula.
    - Almenys un número.
    - Almenys un símbol.
- **Missatges amb Cookies**: S'han utilitzat cookies per mostrar missatges d'èxit en iniciar i tancar sessió.
- **Timeout de Sessió**: La sessió es tanca automàticament després de 40 minuts d'inactivitat.
- **Paginació**: S'ha implementat un sistema de paginació per a la visualització dels articles.
- **Selecció d'Articles per Pàgina**: Els usuaris poden escollir quants articles volen veure per pàgina (5, 10 o 15).

## Estructura de Carpetes
- **CSS**: Conté tots els arxius CSS del projecte.
- **Database**: Conté la base de dades (`pt04_alberto_gonzalez.sql`).
- **Login**: Conté tota la funcionalitat relacionada amb el login, logout i registre d'usuaris.
- **Vistes**: Conté les vistes que es mostren a l'usuari.
- **Controlador**: Conté els arxius que actuen com a intermediaris entre les vistes i els models.
- **Model**: Conté els arxius relacionats amb la lògica de negoci i l'accés a dades.
- **Arrel**: Conté el fitxer (`index.php`) i aquest mateix README.

## Navegació
- S'ha implementat una barra de navegació (navbar) en totes les vistes que permet als usuaris tancar sessió fàcilment.
- La barra de navegació també mostra el nom de l'usuari actualment connectat.

## Gestió d'Errors
- S'han implementat missatges d'error i èxit detallats per proporcionar una millor retroalimentació a l'usuari.
