# Vanilla API Documentation

## Author's Note
Hello there! I'm David Duron, I wrote this php application as a way to provide a simple way to test front-end applications. This is an out-of-the-box application that allows jwt authentication/authorization to be retrieved from the server. This allows a fully state-less API, thus fulfilling the theoretical model of REST API.

## Introduction

Welcome to the Vanilla API documentation. This API provides a fully functional JWT authentication system along with the ability to upload and retrieve blog content. This documentation outlines the available endpoints and their functionality.

## Authentication

To access the API, you must authenticate using JWT (JSON Web Token). You will receive a JWT token upon successful registration or login. This token must be included in the headers of each request as follows:

# Authorization: Bearer <your_jwt_token>


## Endpoints

### 1. User Registration

**Endpoint:** `vanapi/api.php/entry/register`

- **Method**: POST
- **Description**: Register a new user with the provided email and password.
- **Request Body**:
  - `email` (string, required): The email address of the user.
  - `password` (string, required): The password for the user's account.
- **Response**:
  - `200 OK`: User successfully registered. JWT token is provided in the response.
  - `400 Bad Request`: Invalid request format.
  - `409 Conflict`: Email address already in use.

### 2. User Login

**Endpoint:** `vanapi/api.php/entry/login`

- **Method**: POST
- **Description**: Log in an existing user with the provided email and password.
- **Request Body**:
  - `email` (string, required): The email address of the user.
  - `password` (string, required): The password for the user's account.
- **Response**:
  - `200 OK`: User successfully logged in. JWT token is provided in the response.
  - `400 Bad Request`: Invalid request format.
  - `401 Unauthorized`: Invalid credentials.

### 3. Retrieve All Content

**Endpoint:** `vanapi/api.php/content/all`

- **Method**: GET
- **Description**: Retrieve all content from the database.
- **Authentication**: Required (Include JWT token in headers).
- **Response**:
  - `200 OK`: Content retrieved successfully. Returns a list of content items.
  - `401 Unauthorized`: Missing or invalid JWT token.

### 4. Retrieve Content Details

**Endpoint:** `vanapi/api.php/content/details/{contentId}`

- **Method**: GET
- **Description**: Retrieve details of a specific content item by its ID.
- **Authentication**: Required (Include JWT token in headers).
- **Parameters**:
  - `{contentId}` (integer, path, required): The ID of the content item to retrieve.
- **Response**:
  - `200 OK`: Content details retrieved successfully. Returns the content details.
  - `204 No Content`: Content not found.
  - `401 Unauthorized`: Missing or invalid JWT token.

### 5. Upload Content

**Endpoint:** `vanapi/api.php/content/upload`

- **Method**: POST
- **Description**: Upload new content to the database and move the image to the uploads directory.
- **Authentication**: Required (Include JWT token in headers).
- **Request Body**:
  - `title` (string, required): Title of the content.
  - `body` (string, required): Body of the content.
  - `keywords` (string, required): Keywords associated with the content.
  - `author` (string, required): Author of the content.
  - `path1` (string, required): Path to the first image file.
  - `path2` (string, required): Path to the second image file.
- **Response**:
  - `200 OK`: Content uploaded successfully.
  - `400 Bad Request`: Invalid request format.
  - `401 Unauthorized`: Missing or invalid JWT token.

### Data Model

To upload content, use the following data model:

```json
{
    "title": "string",
    "body": "string",
    "keywords": "string",
    "author": "string",
    "path1": "string",
    "path2": "string"
}
```


### Error Codes

    - 400 Bad Request: The request format is invalid.
    - 401 Unauthorized: Authentication failed.
    - 409 Conflict: Registration failed due to an existing email address.
    - 204 No Content: Content not found.

### Please ensure that you include the appropriate headers and provide valid input for each request to successfully interact with the Vanilla API.