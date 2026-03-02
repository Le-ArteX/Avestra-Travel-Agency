# Avestra Travel Agency

A PHP + MySQL web application for booking tickets, hotels, and tour packages.

---

## Running locally with Docker

### Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/Le-ArteX/Avestra-Travel-Agency.git
cd Avestra-Travel-Agency

# 2. Copy the environment template
cp .env.example .env

# 3. (Optional) Open .env and set secure passwords before first run
#    The defaults work fine for a local test.

# 4. Start the application
docker compose up -d

# 5. Open the site in your browser
#    http://localhost
```

> **Tip:** On first start MySQL may take ~20 seconds to initialise.  
> Run `docker compose logs -f db` to watch it; you'll see "ready for connections" when it is done.

To stop the application:

```bash
docker compose down
```

---

## Deploying to a server

### 1. Prepare the server

Install **Docker** and **Docker Compose** on the server, then create the project directory:

```bash
sudo mkdir -p /opt/avestra-travel-agency
sudo chown $USER /opt/avestra-travel-agency
cd /opt/avestra-travel-agency

# Copy docker-compose.yml to the server (or git clone the repo)
# Then create the .env file with production values:
cp .env.example .env
nano .env          # set strong passwords and real SMTP credentials
```

### 2. Start the stack

```bash
docker compose up -d
```

The site will be reachable at **`http://<your-server-ip>`** (port 80, standard HTTP — no port number needed in the browser).

> To use a custom domain, point your domain's A record to the server IP, then open `http://yourdomain.com`.

### 3. Automated deployment via GitHub Actions (optional)

The workflow in `.github/workflows/deploy.yml` builds a Docker image on every push to `main` and can deploy it automatically.

Set these values in your repository **Settings → Secrets and variables**:

| Category | Name | Example value |
|----------|------|---------------|
| Variable | `DEPLOY_HOST` | `203.0.113.10` |
| Variable | `DEPLOY_USER` | `ubuntu` |
| Secret | `DEPLOY_SSH_KEY` | *(contents of your private SSH key)* |
| Variable | `DB_HOST` | `db` |
| Variable | `DB_USER` | `avestra` |
| Secret | `DB_PASSWORD` | *(strong password)* |
| Variable | `DB_NAME` | `avestra-Travel-Agency` |
| Secret | `DB_ROOT_PASSWORD` | *(strong root password)* |
| Variable | `SMTP_ENABLED` | `false` |
| Variable | `SMTP_HOST` | `smtp.gmail.com` |
| Variable | `SMTP_PORT` | `465` |
| Variable | `SMTP_USER` | `you@gmail.com` |
| Secret | `SMTP_PASS` | *(Gmail App Password)* |
| Variable | `SMTP_FROM` | `noreply@avestra-travel.com` |
| Variable | `SMTP_FROM_NAME` | `Avestra Travel Agency` |
| Variable | `LOCAL_DEBUG_MODE` | `false` |

Once set, every push to `main` will build and redeploy automatically.

---

## Local development (XAMPP)

The project can still be run with XAMPP exactly as before — no changes required.  
All database and SMTP settings fall back to the original defaults (`localhost`, `root`, empty password) when the environment variables are not set.
