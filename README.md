# KYC Back Office app
==================

Docker setup project for KYC back-office project.

## [Docs](./Docs)

## Usage

For the initial install, you should use:
- make install

You can override ports in docker-compose.override.yml file.

For project permissions, you can run:
- make app-permissions

For test, you can run:
- make run-test

Please check Makefile file for more available commands

JWT
---

In order to generate keys, you can run:
 - make generate-jwt-keys

Your keys will land in config/jwt/private.pem and config/jwt/public.pem

Hosts
-----

Add the following host in the /etc/hosts file:

```bash
127.0.0.1 bo_traitement.local
```

After that, you can access the application using the following link:

- http://bo_traitement.local:8090/

Set git hooks
-------------
```
cp githooks/pre-push .git/hooks/pre-push
chmod +x .git/hooks/pre-push
```

In case you need to push changes without running the tests (for partial work), you can use `--no-verify` to skip the hook.

Docker Containers
-----------------
The project has one container, defined in _docker_compose.yaml_ file:
- **app**: The application container build over `php:7.4-apache` docker image, where composer and other php related
  tasks can be run. Use this container to install new packages.
    - It exposes an internal API
    - The API documentation can be viewed by accessing `http://bo_traitement.local:8090/api/doc.json`
    - connects to the external `beprems` database via network

Debugging
---------

The container `app` contains xdebug enabled by default. Make sure to set the correct xdebug cookie in your browser.
You can check and update (if needed) the xdebug.ini file by accessing the 'app' folder in the docker directory

In order to have xdebug up and running, follow the above:
1. the PHPStorm server needs to be configured and mapped from `Settings > Languages and Frameworks > PHP > Servers`.
2. Add `bo_traitement.local` as `host`
3. Map the folders properly: `/local/path/to/bo_traitement_agent/ > /var/www/html`
