# Users API

This is a RESTful API built with Laravel to manage user records, supporting authentication via Laravel Sanctum.

## **Installation**

### **1. Clone the repository**

```sh
git clone https://github.com/rutecintra/usersapi.git
cd usersapi
```

### **2. Install dependencies**

```sh
composer install
```

### **3. Set up the environment**

Copy the example environment file and configure database settings:

```sh
cp .env.example .env
```

Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=users_api
DB_USERNAME=root
DB_PASSWORD=secret
```

### **4. Generate application key**

```sh
php artisan key:generate
```

### **5. Run database migrations**

```sh
php artisan migrate
```

### **6. Start the application**

```sh
php artisan serve
```

## **Authentication**

This API uses Laravel Sanctum for authentication.

To generate an authentication token, send a `POST` request to:

```sh
POST /api/login
```

With the following JSON payload:

```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

The response will contain an access token.

## **API Endpoints**

| Method   | Endpoint          | Description       |
| -------- | ----------------- | ----------------- |
| `POST`   | `/api/login`      | Authenticate user |
| `GET`    | `/api/users`      | List all users    |
| `POST`   | `/api/users`      | Create a new user |
| `GET`    | `/api/users/{id}` | Get user details  |
| `PUT`    | `/api/users/{id}` | Update user       |
| `DELETE` | `/api/users/{id}` | Delete user       |

For protected routes, include the `Authorization` header:

```sh
Authorization: Bearer {your_token}
```

## **License**

This project is open-source and available under the [MIT License](LICENSE).