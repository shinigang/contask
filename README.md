<p align="center"><img src="https://raw.githubusercontent.com/shinigang/contask/main/public/img/logo.png" width="400" alt="Contask Logo"></p>

## About ConTask

ConTask is a simple contact and task management application written using VILT (Vue, Inertia, Laravel, Tailwind) tech stack.

## Installation

1. Run Docker Desktop
2. Clone this git repo to your Project folder (Using WSL2 file system for Windows Docker)
3. Setup config file by copy-pasting .env.example to .env and update credentials:

    ```
    cd contask
    cp .env.example .env
    ```

4. Run docker file:

    ```
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php82-composer:latest \
        composer install --ignore-platform-reqs
    ```

5. Run migrations:

    ```
    alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
    sail artisan migrate
    ```

6. Run Factory and update typesense indexes (optional)

    ```
    sail artisan tinker
    Task::factory()->count(100)->create();
    exit
    sail artisan scout:import "App\Models\Task"
    sail artisan scout:import "App\Models\Person"
    sail artisan scout:import "App\Models\Business"
    ```

## Development

1. Make sure Docker Desktop is running
2. Open a terminal and run docker via:
    ```
    ./vendor/bin/sail up
    ```
3. Open another terminal and run scout queue worker via:
    ```
    ./vendor/bin/sail artisan queue:work redis --queue=scout
    ```
4. Open another terminal and run the following:
    ```
    alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
    sail npm install
    sail npm run dev
    ```
5. Open localhost in your browser.
