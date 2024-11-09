<div style="text-align:center">
    <img src="/assets/logo.png" style="margin: auto;">
</div>

# Monarch Web Framework

Monarch is an exploration of how simple and flexible a small PHP framework can be.

Its goal is to provide the minimum amount of tools and structure to help you create a web application, while providing a structure that is easy to understand and maintain. If you are looking for a framework that is compatible with all modern PSR standards, and follows all the latest trends in web development, Monarch is not for you. If you are looking for a framework that is simple, easy to understand, that provides simple abstractions only where needed, Monarch may be what you are looking for.

At its core, Monarch uses [HTMX](https://htmx.org/) to provide a dynamic and interactive user experience. Features for working with HTMX are built directly into the Request and Response classes.

## Folder Structure

The following folders are used within Monarch:

```
app/
config/
database/
public/
routes/
tests/
writable/
```

### app/

The `app/` folder is where you will place all of your application-specific code that are not a route file or associated control file.
This is typically where you will place your models, libraries, and other classes.

### config/

Contains all of the configuration files for your application.

### database/

This is where you will place all of your database migrations and seeds.

### public/

This is the web root of your application. It contains the `index.php` file that is the entry point for all requests.

### routes/

This is where you will define all of the route files, templates, and controller logic.

### tests/

This holds all of your application's tests.

### writable/

This is where Monarch will write any logs, cache files, or other files that need to be written to. When deploying your application, make sure this folder is writable by the web server.
