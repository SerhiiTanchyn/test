# Symfony Project

## 🚀 Project Configuration

- **🌍 Web Server:** Nginx
- **🐘 PHP Version:** 8.3
- **🐘 Database:** PostgreSQL 17
- **📦 Containerization:** Docker & Docker Compose

## 🏁 Getting Started

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/your-repo/project.git
cd project
```

### 2️⃣ Use Makefile for Setup

A `Makefile` is included to simplify project setup. Run the following command to start the project:

```bash
make start
```

### 3️⃣ Available Makefile Commands

- **Start the project:**
  ```bash
  make start
  ```
  This will build and start the Docker containers, install dependencies, and apply migrations.

- **Stop the project:**
  ```bash
  make stop
  ```
  Stops and removes all running containers.

- **Run tests:**
  ```bash
  make test
  ```
  Runs PHPUnit tests.

- **Rebuild the project:**
  ```bash
  make rebuild
  ```
  Stops, removes containers, and starts fresh.


### 2️⃣ Or you can setup manually


Run the following command to start all necessary containers:

```bash
docker-compose up -d
```

### 3️⃣ Install Dependencies

```bash
docker-compose exec php composer install
```

### 5️⃣ Run Database Migrations

```bash
docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
```

### 6️⃣ Running Tests

To execute PHPUnit tests, run script that recreate test database and run phpunit also:

```bash
./bin/setup-test-db 

docker-compose exec php bin/phpunit
```

### 4️⃣ API Documentation

Swagger documentation is available at:

```
http://localhost:9339/api/doc
```

## ⏹️ Stopping the Project

To stop all containers, run:

```bash
make stop
```

