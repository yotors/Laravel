### Building and running your application

When you're ready, start your application by running:
`docker compose up --build`.

Your application will be available at http://localhost:8001.

### PHP extensions
If your application requires specific PHP extensions to run, they will need to be added to the Dockerfile. Follow the instructions and example in the Dockerfile to add them.

### Deploying your application to the cloud

The instructions below are for manual deployment. However, for pushes to the `main` branch, a GitHub Actions workflow is configured to automatically build the Docker image and push it to GitHub Container Registry (ghcr.io). The image will be available at `ghcr.io/<your-github-username-or-org>/<your-repo-name>`. You can use this image directly in your cloud deployment environment.

If you need to build and push manually or to a different registry:

First, build your image, e.g.: `docker build -t myapp .`.
If your cloud uses a different CPU architecture than your development
machine (e.g., you are on a Mac M1 and your cloud provider is amd64),
you'll want to build the image for that platform, e.g.:
`docker build --platform=linux/amd64 -t myapp .`.

Then, push it to your registry, e.g. `docker push myregistry.com/myapp`.

Consult Docker's [getting started](https://docs.docker.com/go/get-started-sharing/)
docs for more detail on building and pushing.

### Environment Variables for Production

For production deployments, it is crucial to manage your environment variables securely and correctly. **Do not copy a populated `.env` file into your production Docker image.**

The Docker image for this application is configured with an entrypoint script (`docker-entrypoint.sh`) that will copy `.env.example` to `.env` if no `.env` file is present when the container starts. While this is helpful for ensuring the application can boot, the default values in `.env.example` are not suitable for production.

You must provide environment-specific values to the container at runtime. Common methods include:
- Using the environment variable settings provided by your cloud hosting platform (e.g., AWS Elastic Beanstalk, Heroku, Azure App Service).
- Using the `-e` flag with `docker run` (e.g., `docker run -e APP_ENV=production -e APP_KEY=... myapp`).
- Defining environment variables in Kubernetes deployment manifests or ECS task definitions.

**Key variables that MUST be set for production:**

*   `APP_ENV=production`: Ensures Laravel is running in production mode (optimizations enabled, detailed error messages disabled).
*   `APP_DEBUG=false`: Disables debug mode, which should never be enabled in a live production environment.
*   `APP_KEY`: This **must** be a unique, randomly generated 32-character string, prefixed with `base64:`. The entrypoint script will generate a key if `APP_KEY` is empty in the `.env` file. However, for consistent behavior across multiple container instances (e.g., in a load-balanced setup) and to ensure cryptographic soundness, you should generate this key securely yourself and provide it as an environment variable. You can generate a suitable key using `php artisan key:generate --show` and then prefix it with `base64:`.
*   `DB_CONNECTION`: Set to your production database type (e.g., `mysql`, `pgsql`).
    *   `DB_HOST`: The hostname or IP address of your production database server.
    *   `DB_PORT`: The port of your production database server.
    *   `DB_DATABASE`: The name of your production database.
    *   `DB_USERNAME`: The username for connecting to your production database.
    *   `DB_PASSWORD`: The password for the database user. **This must be kept secure.**
*   `SESSION_DRIVER`: Consider `redis` or `database` for production instead of `file` if you have multiple web server instances.
*   `CACHE_STORE`: Similarly, `redis` or `memcached` are typically better for production than `file`.
*   `QUEUE_CONNECTION`: If using queues, configure this for a robust driver like `redis` or `database` (or SQS, etc.) instead of `sync`.
*   `MAIL_MAILER`: Configure with a reliable mail service (e.g., `smtp`, `ses`, `mailgun`, `postmark`) and provide all associated `MAIL_*` settings.

Review your `.env.example` file for other settings that might be relevant to your specific application needs and ensure they are configured appropriately for a production environment.