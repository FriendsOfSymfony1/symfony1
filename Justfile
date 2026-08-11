dockerCompose := "docker compose"
dockerExec := dockerCompose + " exec php"
defaultPhp := "7.4"

default:
    @just --list --justfile {{ justfile() }}

# Build the docker image with the given PHP version
build version=defaultPhp *options="":
    {{ dockerCompose }} build --build-arg=PHP_VERSION={{ version }} {{ options }}

# Build the docker image (and pull new images) with the given PHP version
build-pull version=defaultPhp: (build version "--pull")

# Build the docker image (and pull new images, with no docker cache) with the given PHP version
rebuild version=defaultPhp: (build version "--pull" "--no-cache")

# Start the docker containers in detached mode (no logs) and waits for the dependencies to be up and running.
up:
    {{ dockerCompose }} up --detach --wait

# Start the docker containers and keep the daemon attached
up-foreground:
    {{ dockerCompose }} up

# Stop the running containers
down:
    {{ dockerCompose }} down --remove-orphans

# Display and follow the containers logs
logs:
    {{ dockerCompose }} logs --follow

# Get a terminal within the running PHP container
shell:
    {{ dockerExec }} ash

cs-check: (run-cs-fix "--dry-run")
cs-fix: run-cs-fix
[private]
run-cs-fix *options:
    {{ dockerExec }} tools/php-cs-fixer.phar fix --verbose {{ options }}

# Run the legacy Symfony1 tests on the currently running docker instance
tests-legacy:
    {{ dockerExec }} php data/bin/symfony symfony:test --trace

# Show the given PHP extensions's configuration from the running PHP container
php-ext-config extname:
    {{ dockerExec }} php --ri {{ extname }}

# Setup and initialize the project (docker image must be running)
setup:
    git submodule update --checkout --recursive --force
    {{ dockerExec }} composer update --optimize-autoloader

# Cleanup the local code from vendor and composer.lock file
cleanup:
    rm -fr vendor/
    rm -fr composer.lock
