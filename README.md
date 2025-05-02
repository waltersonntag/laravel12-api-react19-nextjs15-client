# Installation Instructions

## Installation

### Initialize Laravel

```bash
cd api
# Install composer packages
composer install
# Create .env file
cp .env.example .env
# Generate new app key
php artisan key:generate
# Install npm packages
npm install
```

### Initialize ReactJS / NextJS

```bash
cd client
npm install
```

### Setup client local .env variable

```bash
# Create .env.local file...
cp client/.env.example client/.env.local
```

### Start Laravel API backend

```bash
cd api
# start Laravel server
php artisan serve
```

### Start ReactJS / NextJS frontend client

```bash
cd client
# Start ReactJS / NextJS frontend client 
npm run dev
```
