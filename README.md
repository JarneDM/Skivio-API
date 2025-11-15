# Skivio API

Backend API for [Skivio](https://github.com/JarneDM/Skivio), a collaborative Kanban board application.

## Table of Contents

-   [Features](#features)
-   [Tech Stack](#tech-stack)
-   [Installation](#installation)
-   [Running the API](#running-the-api)
-   [API Endpoints](#api-endpoints)
-   [Authentication](#authentication)
-   [Database Structure](#database-structure)
-   [Contributing](#contributing)

---

## Features

-   User registration and login with Laravel Sanctum
-   CRUD operations for projects (boards), tasks, and statuses
-   Multiple projects per user
-   Team functionality: invite users to shared boards
-   Task assignment to users
-   Kanban-style workflow: Backlog, To Do, In Progress, Done

---

## Tech Stack

-   **Backend:** Laravel
-   **Authentication:** Laravel Sanctum
-   **Database:** MySQL / PostgreSQL
-   **Frontend:** React (skivio-frontend)
-   **Local Dev Environment:** Docker + DDEV

---

## Installation

### Using DDEV

1. Clone the repository:

```bash
git clone https://github.com/your-username/skivio-api.git
cd skivio-api
```

2. Start DDEV:

```bash
ddev config --project-type=laravel --docroot=public --create-docroot
ddev start
```

3. Install dependencies inside DDEV container:

```bash
ddev composer install
```

4. Copy `.env` file:

```bash
ddev exec cp .env.example .env
```

5. Generate application key:

```bash
ddev artisan key:generate
```

6. Run migrations and seed initial data:

```bash
ddev artisan migrate --seed
```

> Seeds create the global statuses: Backlog, To Do, In Progress, Done.

## Running the API

### With DDEV:

```bash
ddev artisan serve --host=0.0.0.0 --port=8000
```

Default URL: `http://127.0.0.1:8000`

## API Endpoints

### Auth

| Method | Endpoint        | Description                    |
| ------ | --------------- | ------------------------------ |
| POST   | `/api/register` | Register a new user            |
| POST   | `/api/login`    | Login a user                   |
| POST   | `/api/logout`   | Logout the current user        |
| GET    | `/api/me`       | Get current authenticated user |

### Projects

| Method | Endpoint             | Description          |
| ------ | -------------------- | -------------------- |
| GET    | `/api/projects`      | Get all projects     |
| POST   | `/api/projects`      | Create a new project |
| GET    | `/api/projects/{id}` | Get a single project |
| PUT    | `/api/projects/{id}` | Update a project     |
| DELETE | `/api/projects/{id}` | Delete a project     |

### Tasks

| Method | Endpoint          | Description       |
| ------ | ----------------- | ----------------- |
| GET    | `/api/tasks`      | Get all tasks     |
| POST   | `/api/tasks`      | Create a new task |
| GET    | `/api/tasks/{id}` | Get a single task |
| PUT    | `/api/tasks/{id}` | Update a task     |
| DELETE | `/api/tasks/{id}` | Delete a task     |

---

## Authentication

The API uses token-based authentication (**Laravel Sanctum**).

Include the token in the `Authorization` header for protected routes:

```js
Authorization: Bearer {token}
```

## Database Structure

-   **Users:** store user information
-   **Projects:** each project is a Kanban board
-   **Statuses:** Backlog, To Do, In Progress, Done
-   **Tasks:** tasks belong to a project and can be assigned to a user

---

## Contributing

1. Fork the repository
2. Create a branch (`git checkout -b feature/my-feature`)
3. Commit changes (`git commit -m 'Add feature'`)
4. Push to branch (`git push origin feature/my-feature`)
5. Open a Pull Request

---

> Developed with ❤️ by the Skivio Team (aka JarneDM)
