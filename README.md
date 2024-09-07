## The older **FurniStore** project structure
```
furni-store
├── .idea
|   └──
├── admin
│   ├── assets
│   ├── css
│   │   ├── style.min.css
│   │   └── styles.css
│   ├── js
│   │   ├── all.js
│   │   ├── datatables-simple-demo.js
│   │   └── scripts.js
|   ├──classes
|   |   ├──  CategoryManager.php
|   |   ├──  Database.php
|   |   ├── ProductManager.php
|   |   ├── UserManager.php
|   |   ├──  
|   |   └── Repository
|   |        ├──   CategoryRepository.php
|   |        ├──  ProductRepository.php
|   |        └──   UserRepository.php
│   ├── upload
│   │   ├── footer.php
│   │   ├── header.php
│   │   ├── product-1.png
│   │   ├── product-2.png
│   │   ├── product-3.png
│   │   ├── top-view-circular-frame-with-cleaning-products.jpg
│   │   └── y.jpg
│   ├── layout-sidenav-light.php
│   ├── layout-static.php
│   ├── login.php
│   ├── logout.php
│   ├── password.php
│   ├── products.php
│   ├── register.php
│   ├── tables.php
│   ├── categories.php
│   ├── charts.php
│   ├── 401.php
│   ├── 404.php
│   ├── 500.php
│   ├── database.php
│   ├── editcategory.php
│   ├── editusers.php
│   ├── index.php
│   └── users.php
├── css
│   ├── all.min.css
│   ├── bootstrap.min.css
│   ├── nrmalize.css
│   ├── style.css
│   ├── style1.css
│   └── tiny-slider.css
├── images
|   └──
├── include
|   └──
├── jquery
|   └──
├── js
|   └──
├── plugins
|   └──
├── scss
│   └── style.scss
├── uploads
│   ├── user_img
│   │   ├── person-1.png
│   │   ├── person_2.jpg
│   │   ├── person_3.jpg
│   │   └── pxfuel.jpg
│   ├── user_imgbowl-3.png
│   └── user_imgperson_3.jpg
├── login.php
├── noscrpit.php
├── prepros-6.config
├── products.php
├── index.php
├── profile.php
├── error.php
├── favicon.png
├── furniture_store.sql
├── hi.html
├── README.md
├── README.txt
├── cart.php
├── checkout.php
├── contact.php
├── Register.php
├── reg_test.php
├── services.php
├── shop.php
├── blog.php
├── test.php
├── thankyou.php
├── about.php
└── unauthorized.php
```




## here's how we can guide and structure our **FurniStore** project in alignment with the **Cross-Platform Application Development** requirements.

### Key Areas of Focus:
1. **Platform 2 Development (Web Application)**
2. **API Development**
3. **Design Patterns**
4. **Clean Code Practices**
5. **Testing & Documentation**

### 1. **Platform 2 Development: Web Application using Pure PHP**

You’ve chosen **pure PHP**, so we will ensure your web app aligns with best practices in modular PHP development.

#### **Organize Your Classes (OOP)**:
- You already have a `classes` directory for various components. Ensure that classes follow OOP principles (e.g., SRP from SOLID).
  - **`Database.php`**: Centralize your database connection here (PDO is preferred for security).
  - **`UserManager.php`, `ProductManager.php`, `CategoryManager.php`**: These should contain the core business logic for users, products, and categories.
  - **`Repository` Directory**: A good choice to handle database queries. Keep the SQL logic inside these repositories, which interact with the `Manager` classes.

#### **Modularize Common Code**:
- **`include/`** directory (not present): Move all reusable components like `header.php`, `footer.php`, and other common views to a new `include` folder.
- **View Templates**: Follow a clear separation between logic (PHP) and views (HTML/CSS). Use your templates like `products.php` and `users.php` as views, while logic is handled by controllers or managers.

---

### 2. **API Development**

We need to implement a **RESTful API** to handle communication between your web platform and the mobile app (which you'll build later with Flutter).

#### **API Folder Structure**:
Create a new `api/` directory, with subfolders for better organization:
```
api/
  ├── index.php        # Entry point for all API requests
  ├── routes.php       # Define API routes and map them to the right controller methods
  ├── controllers/     # Business logic related to API handling (e.g., ProductController.php)
  ├── models/          # Data models (e.g., Product.php, User.php)
  ├── utils/           # Utility functions (e.g., error handling, validation)
```

#### **API Features**:
- **CRUD Operations**: You need to expose CRUD functionalities for `Products`, `Users`, and `Categories`.
  - **Example API Endpoints**:
    - GET `/api/products` → Fetch all products
    - POST `/api/products` → Add new product
    - PUT `/api/products/{id}` → Update product
    - DELETE `/api/products/{id}` → Delete product

- **JWT Authentication**:
  - Implement JWT for secure authentication, especially for admin operations.
  - Add authentication checks in routes like `/admin/products`.

- **Validation**: Ensure all incoming API requests are validated for the correct structure, preventing SQL injection or bad data.

---

### 3. **Design Patterns Implementation**

Implement at least three **design patterns** to fulfill your course requirement:

#### **Suggested Patterns**:
1. **Facade Pattern**:
   - Use this pattern to simplify complex interactions between your API and `ProductManager`, `UserManager`, etc.
   - Create a `StoreFacade.php` to handle higher-level interactions across multiple subsystems (products, users, etc.).

2. **Adapter Pattern**:
   - Use this for interacting with third-party services or external libraries. For example, if you integrate a payment gateway, build an `Adapter` to bridge your code with the gateway's API.

3. **Singleton Pattern**:
   - Apply this pattern to your `Database.php` class. Ensure only a single instance of the database connection is created, which can be reused throughout the application.

Document each design pattern you implement in a separate file (`design_patterns.md`) explaining the rationale and benefits.

---

### 4. **Clean Code Practices**

#### **Adopt SOLID Principles**:
- **Single Responsibility Principle (SRP)**: Ensure each class has one responsibility. For example, `UserManager.php` should handle only user-related logic.
- **Interface Segregation**: Split larger interfaces into smaller, more specific interfaces to avoid forcing classes to implement methods they don’t need.
  
#### **File Organization**:
- Place related files in logical folders, e.g., all business logic in `classes/`, all API logic in `api/`, and so on.
  
#### **Code Consistency**:
- Ensure consistent naming conventions (`camelCase` for variables, `PascalCase` for classes).
- Avoid inline SQL; use prepared statements with PDO for all database queries.

---

### 5. **Testing & Documentation**

#### **Unit Testing**:
- Use **PHPUnit** to write tests for your `Manager` and `Repository` classes.
  - Example: Test CRUD methods in `ProductRepository.php`.
  
#### **API Testing**:
- Use **Postman** or **Insomnia** to test API endpoints.
  - Check responses for various scenarios (e.g., invalid inputs, authentication failures).

#### **Documentation**:
- **API Documentation**:
  - Create a separate markdown file `api_documentation.md` detailing all API endpoints, request/response formats, and authentication requirements.
  
- **Code Documentation**:
  - Add comments to describe the purpose of classes, functions, and important blocks of code.

---

### **Next Steps for You**:
1. **Refactor Classes**: Start by refactoring your `Manager` and `Repository` classes for clarity and to follow SOLID principles.
2. **Build API**: Implement a basic RESTful API using the structure suggested.
3. **Apply Design Patterns**: Implement at least the three patterns (Facade, Adapter, Singleton) and document their use.
4. **Write Unit Tests**: Ensure you write unit tests for the core functionality in your app.
5. **Prepare for Flutter Integration**: As you implement the web API, ensure it’s scalable and easy to integrate with the future Flutter mobile app.

