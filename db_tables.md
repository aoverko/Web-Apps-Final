# Database Documentation

This documentation describes the structure of the `Users` and `Products` tables in our database.

## Table: Users

The `Users` table stores information about each user who registers in the application.

| Column       | Data Type      | Description                           |
|--------------|----------------|---------------------------------------|
| `id`         | INT (Primary Key, Auto Increment) | Unique identifier for each user. |
| `firstname`  | First Name     | First Name of user. |
| `lastname`   | Last Name      | Last Name of user. | 
| `username`   | VARCHAR(50)    | Username chosen by the user. Must be unique. |
| `email`      | VARCHAR(100)   | User's email address. Must be unique. |
| `password`   | VARCHAR(255)   | Hashed password for user authentication. |
| `created_at` | TIMESTAMP      | Timestamp of when the user account was created. |
| `updated_at` | TIMESTAMP      | Timestamp of the last update to the user's record. |
| `is_admin TINYINT(1) DEFAULT 0`| Admin          | Does user have admin rights. |
 
### Notes:
- **Primary Key**: The `id` field serves as the primary key for the `Users` table.
- **Unique Constraints**: Both `username` and `email` are unique to prevent duplicate user records.
- **Password Storage**: Passwords are stored in a hashed format to enhance security.

---

## Table: Products

The `Products` table stores details about the products available in the application.

| Column         | Data Type        | Description                             |
|----------------|------------------|-----------------------------------------|
| `id`           | INT (Primary Key, Auto Increment) | Unique identifier for each product. |
| `name`         | VARCHAR(100)     | Name of the product.                   |
| `description`  | TEXT             | A brief description of the product.    |
| `price`        | DECIMAL(10, 2)   | Price of the product in USD.           |
| `stock`        | INT              | Quantity of the product in stock.      |
| `created_at`   | TIMESTAMP        | Timestamp of when the product was added. |
| `updated_at`   | TIMESTAMP        | Timestamp of the last update to the product record. |
| `image_url`    | Image Url        | Image of Product. |
| `product_type` | Product Type     | Type of Product. |
### Notes:
- **Primary Key**: The `id` field is the primary key for the `Products` table.
- **Price Format**: Prices are stored with two decimal places (e.g., 19.99).

---

## Relationships

- There is no direct relationship between `Users` and `Products` in this basic setup. However, in a more comprehensive system, a `Purchases` or `Orders` table could be introduced to link users and products.

---

## Sample Queries

Here are some example queries for interacting with these tables:

### Insert a New User
```sql
INSERT INTO Users (username, email, password, created_at, updated_at)
VALUES ('johndoe', 'johndoe@example.com', 'hashed_password', NOW(), NOW());
