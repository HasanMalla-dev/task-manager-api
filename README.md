# Task Manager RESTful API

A robust RESTful API built with **Laravel** and **Laravel Sanctum** for task management, authentication, and user authorization.

## Features
- **User Authentication:** Registration, Login, and Logout using Sanctum API Tokens.
- **Task Management:** Full CRUD operations (Create, Read, Update, Delete) for tasks.
- **Authorization:** Secure access ensured so users can only manage their own tasks.
- **Status Filtering:** Filter tasks by status (`pending`, `in_progress`, `completed`).

## Tech Stack
- **Framework:** Laravel
- **Database:** MySQL
- **Authentication:** Laravel Sanctum
- **API Testing:** Postman

## API Endpoints

### Authentication
- `POST /api/register` - Register a new user
- `POST /api/login` - Login and receive Bearer Token
- `POST /api/logout` - Revoke Token (Protected)

### Tasks (Protected Routes)
- `GET /api/tasks` - Fetch all tasks for logged-in user
- `POST /api/tasks` - Create a new task
- `GET /api/tasks/{id}` - Show specific task details
- `PUT /api/tasks/{id}` - Update a task
- `DELETE /api/tasks/{id}` - Delete a task
