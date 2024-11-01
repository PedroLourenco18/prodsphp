<h1 align="center" style="font-weight: bold;">Prodsphp</h1> 

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)
![JWT](https://img.shields.io/badge/JWT-black?style=for-the-badge&logo=JSON%20web%20tokens)
![Google](https://img.shields.io/badge/google-4285F4?style=for-the-badge&logo=google&logoColor=white)

<h2>📌 About</h2>

- php 8
- MySQL
- JWT
- OAuth 2.0
- SOLID

Prodsphp is a PHP REST API that performs basic CRUD operations for products and users in a SQL database. It uses JWT for authorization and includes its own authentication system, with the option to use Google OAuth 2.0 for user authentication as well. It was created to study SOLID principles and OAuth 2.0.

<h2>Requests and Responses</h2>
All request bodies must be of type JSON. Response bodies are also in JSON format.

<h2>Authentication</h2>
Authentication is handled through a JWT token, which is obtained by logging in or creating an account. After that, you must include the token in the authorization header with the "Bearer" prefix.
<h4>Log in response example:<h4>

```json
{
    "error": false,
    "auth_token": "<token>"
}
```

<h4>Authorization header example:<h4>

```
Bearer <token>
```
<h2>Authorization</h2>
Each user has a 'role,' which defines the user's level of access in the API. There are three roles: 'junior', 'dev', and 'senior'. Since this API was created for study purposes, users can choose their role when creating an account. Accounts created with Google are assigned the 'junior' role by default, but this can be modified later.

<h3>No Authentication Required:</h3>

- View products
- Log in
- Sign up
<h3>Only Authentication Required:</h3>

- View, update or delete your own account
<h3>"Senior" Role Required:</h3>

- Create, update or delete products

<h2>📍 API Endpoints</h2>

base URL: http://localhost/prodsphp

Examples of requests and responses:

<h3>GET /products</h3>

**RESPONSE**
```json
{
    "error": false,
    "data": [
        {
            "id": 1,
            "brand": "Sansung",
            "name": "Galaxy A21s",
            "price": "1000.00"
        },
        {
            "id": 3,
            "brand": "Apple",
            "name": "Iphone 15",
            "price": "5000.00"
        }
    ]
}
```

<h3>GET /products/{id}</h3>

**RESPONSE**
```json
{
    "error": false,
    "data": {
        "id": 1,
        "brand": "Sansung",
        "name": "Galaxy A21s",
        "price": "1000.00"
    }
}
```

<h3>POST /products</h3>

**REQUEST**
```json
{
    "brand": "Apple",
    "name": "Iphone 13",
    "price": 4000
}
```

**RESPONSE**
```json
{
    "error": false,
    "data": {
        "id": 9,
        "brand": "Apple",
        "name": "Iphone 13",
        "price": "4000.00"
    }
}
```

<h3>PUT /products/{id}</h3>

**REQUEST**
```json
{
    "price": 3500
}
```

**RESPONSE**
```json
{
    "error": false,
    "data": {
        "id": 9,
        "brand": "Apple",
        "name": "Iphone 13",
        "price": "3500.00"
    }
}
```

<h3>DELETE /products/{id}</h3>

**RESPONSE**
```json
{
    "error": false,
    "message": "The product has been deleted successfully"
}
```

<h3>GET /user</h3>

**RESPONSE**
```json
{
    "error": false,
    "data": {
        "id": 6,
        "name": "Pedro Example",
        "email": "example@gmail.com",
        "role": "junior"
    }
}
```

<h3>POST /user</h3>

**REQUEST**
```json
{
    "name": "Pedro Example",
    "email": "example@gmail.com",
    "role": "junior",
    "password": "1234"
}
```

**RESPONSE**
```json
{
    "error": false,
    "auth_token": "<token>"
}
```

<h3>PUT /user</h3>

**REQUEST**
```json
{
    "role": "senior"
}
```

**RESPONSE**
```json
{
    "error": false,
    "message": "Information has been updated successfully"
}
```


<h3>DELETE /user</h3>

**RESPONSE**
```json
{
    "error": false,
    "message": "User 'Pedro Example' deleted"
}
```

<h3>POST /user/login</h3>

**REQUEST**
```json
{
    "email": "example@gmail.com",
    "password": "1234"
}
```

**RESPONSE**
```json
{
    "error": false,
    "auth_token": "<token>"
}
```

<h2>Google OAuth 2 - How To Use</h2>
<h3>Get Google OAuth link</h3>
<h4>GET /oauth2/google/link</h4>
This route returns the link to be used in the 'Sign in with Google' button.

**RESPONSE**

```
https://accounts.google.com/o/oauth2/v2/auth?response_type=code&access_type=online&client_id=158856340228-t0l2jq45kn9ok6hgu10iquo0fpvljagt.apps.googleusercontent.com&redirect_uri=http%3A%2F%2Flocalhost%2Fprodsphp%2Foauth2%2Fgoogle&state&scope=email%20profile&approval_prompt=auto
```

<h4>GET /oauth2/google</h4>
This route is where the user is redirected after authenticating with Google

**RESPONSE**

```json
{
  "error": false,
  "auth_token": "<token>"
}
```