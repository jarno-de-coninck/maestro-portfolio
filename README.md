# HZ-Portfolio
This is my personal portfolio website showcasing my educational journey from MBO to HBO-ICT at HZ University of Applied Sciences. It features a custom MVC architecture and includes my professional profile, a study progress dashboard, personal blogs, and a FAQ section.

**Live Deployment:** [https://jarno-de-coninck.up.railway.app/](https://jarno-de-coninck.up.railway.app/)

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
2. Install PHP dependencies inside the web container (required because local volume mounts overwrite the container's vendor folder):
   ```bash
   docker compose exec web composer install
   ```
   *Note: You do not need to run a manual migration command for MySQL. The `docker-compose.yml` mounts the `./database` folder to `/docker-entrypoint-initdb.d`, meaning MySQL automatically creates the tables and inserts the data when the container starts for the first time.*

## Testing

This project uses PHPUnit for both Unit and Integration testing, ensuring robust validation of the application logic and database interactions. The test suite exclusively targets the app-level code (Controllers, Services, Models, and Repositories) and achieves over 60% code coverage as required.

To run the complete test suite (with a text-based coverage report), use the custom `maestro` command inside the Docker web container:

```bash
docker compose exec web php maestro phpunit
```

*(Note: Executing this via Docker is required to access the `pdo_mysql` and `pcov` extensions necessary for database connections and coverage reporting.)*

## Production Deployment

This project utilizes Continuous Deployment (CD) through Railway's GitHub integration. The application is automatically built and deployed whenever changes are pushed to the `main` branch.

To set up this automated pipeline:
1. Create a new project on [Railway](https://railway.app/).
2. Select **Deploy from GitHub repo** and connect the repository.
3. Configure the service settings to trigger on the `main` branch.
4. **Environment Variables:** Ensure production environment variables (like the database connection string) are set in the Railway dashboard.

Railway automatically detects the `Dockerfile` in the root of the project to install Composer dependencies and host the application. The deployment process is configured to automatically run database migrations, so your remote database will be updated automatically when you push new changes to the `main` branch.

## Releasing New Versions

Releasing new versions is fully automated thanks to my CI/CD setup. 

1. Create a feature branch and commit your code.
2. Push the branch to GitHub and create a Pull Request (PR) against the `main` branch.
3. The GitHub Actions CI pipeline will automatically test the code. It enforces:
   - PHPStan static analysis (Level 8)
   - PHPCS code style checks (PSR-12)
   - Deptrac dependency checks
4. Once the CI checks pass and the PR is merged into `main`, a webhook automatically alerts Railway.
5. Railway fetches the latest code, builds a new container image, and deploys it with zero downtime.