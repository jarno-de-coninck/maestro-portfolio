# HZ-Portfolio
This is my personal portfolio website showcasing my educational journey from MBO to HBO-ICT at HZ University of Applied Sciences. It features a custom MVC architecture and includes my professional profile, a study progress dashboard, personal blogs, and a FAQ section.

## Author
- [Jarno de Coninck](https://github.com/jarno-de-coninck)

## Maestro Framework
This portfolio is built using the Maestro Framework. Maestro is a lightweight PHP framework that follows the MVC architectural pattern, making it easy to develop and maintain web applications.

**Maestro Framework Authors:**
- [Frans Blauw](https://github.com/FransBlauw)
- [Valeria Stamenova](https://github.com/v-stamenova)

## Docker Environment Setup

This project was developed iteratively following the DevOps requirements. Below are the documented steps.

### Step 1: Single Manual Container
*Note: This documents the initial phase of the project using SQLite. The project has since migrated to Step 3.*
1. Build the image: `docker build -t hz-portfolio .`
2. Run the container: `docker run -d -p 8080:80 -v ${PWD}:/var/www/html --name hz-portfolio-web hz-portfolio`
3. Run migrations: `docker exec hz-portfolio-web php maestro migrate`

### Step 2: Single Container via Docker Compose
*Note: This documents the second phase of the project. It has been superseded by Step 3.*
1. Start the service: `docker compose up -d`
2. Install dependencies: `docker compose exec web composer install`
3. Run migrations: `docker compose exec web php maestro migrate`

### Step 3: Multi-Container Setup (Web + MySQL)
**This is the current and active local development environment.** The application runs on two separate containers (Apache/PHP and MySQL 8.0) communicating over an internal Docker network.

1. Build and start the containers:
   ```bash
   docker compose up -d --build
   ```
2. Install PHP dependencies inside the web container:
   ```bash
   docker compose exec web composer install
   ```
3. Run the database migrations to set up the MySQL tables:
   ```bash
   docker compose exec web php maestro migrate
   ```

## Production Deployment

To deploy this application to a live server (e.g., a VPS), the process mirrors the local setup.

1. Provision a Linux server and connect to it via SSH.
2. Install Docker and Docker Compose on the server.
3. Clone this repository to the server.
4. Start the environment in the background:
   ```bash
   docker compose up -d --build
   ```
5. Install the production dependencies:
   ```bash
   docker compose exec web composer install --no-dev --optimize-autoloader
   ```
6. Initialize the production database:
   ```bash
   docker compose exec web php maestro migrate
   ```

## Releasing New Versions

Whenever code is pushed to the `main` branch, the GitHub Actions CI pipeline automatically tests the code against PHPStan, PHPCS, and Deptrac. If all checks pass, you can release the new version to production.

1. Connect to your production server via SSH.
2. Navigate to the project directory.
3. Pull the latest code:
   ```bash
   git pull origin main
   ```
4. Rebuild the web container with the new code:
   ```bash
   docker compose up -d --build
   ```
5. Run migrations to apply any database changes:
   ```bash
   docker compose exec web php maestro migrate
   ```