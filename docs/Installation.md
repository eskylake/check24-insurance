# Installation
You can easily run via docker compose.

#### Usage

Create `.env` if not any:
```sh
cp app/.env.dev app/.env
```

Run services:
```sh
docker compose -f compose.dev.yml up -d --build
```

Make sure all the services are up and running:
```sh
docker compose -f compose.dev.yml ps -a
```

Install dependencies:
```sh
docker exec -t check24-app composer install
```
```sh
docker exec -t check24-app composer dump-autoload
```

Run the command:
```sh
docker exec -t check24-app php bin/console insurance:map-input
```

# Deliverables
- [x] Unit tests
- [x] Feature tests
- [x] A containerized solution
- [x] Error handling
- [x] Logging
- [x] Mapping Contract for non-technical people
- [ ] Better Production solution (such as [FrankenPHP](https://frankenphp.dev/))
- [ ] CI/CD
- [ ] Metrics and Monitoring
- [ ] WebServer configuration
- [ ] Remote Key/Value storage for environment variables