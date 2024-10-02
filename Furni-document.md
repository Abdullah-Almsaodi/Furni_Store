

## **Theme Documentation for Furni Store and Furni App**

### **1. Introduction**

#### Project Overview
The **Furni Store** project is a complete e-commerce platform developed for web (PHP) and mobile (Flutter). The project allows users to manage products, categories, and users, and it includes a role-based system with admin functionality for managing the store. The **Furni App** extends this functionality to a mobile application using **Flutter** with API integrations, offering seamless experiences across both platforms.

### **2. Team & Development**

#### Team Structure
The project was developed by a team of up to four students, with a focus on collaboration and agile development principles.

#### Technologies Used
- **Frontend**: Flutter for mobile, HTML/CSS/JavaScript for web.
- **Backend**: PHP and MySQL for the web platform; APIs for interaction between Flutter app and server.
- **State Management**: Provider in Flutter.
- **Database**: MySQL, with API integration.
- **Version Control**: Git and GitHub for source code management.
- **API**: RESTful API developed in PHP, with JWT for secure authentication.

### **3. Features & Screens**

#### User Management
- **Login/Register**: Secure login and registration using JWT authentication.
- **CRUD Operations**: Admin can add, edit, delete users through the interface.
- **User Roles**: Role-based access control, with Admin roles able to perform CRUD operations.

#### Category & Product Management
- **CRUD Operations**: Admin can create, update, or delete categories and products.
- **API Integration**: Both categories and products are managed through API calls from the Flutter app and PHP site.
- **Dynamic UI**: Categories and products are displayed dynamically in both the web and mobile apps, utilizing the backend API.

#### Screens Overview
1. **Splash Screen**: Utilizes Lottie animations for an attractive introduction.
2. **Login/Register Screen**: User authentication and registration.
3. **Home Screen**: Displays product categories and featured items.
4. **User Management**: Admin area for managing users.
5. **Category Management**: CRUD operations for categories.
6. **Product Management**: CRUD operations for products.

### **4. Design & UI/UX**

#### User Interface
- **Responsive Design**: The layout adjusts dynamically across mobile and web screens for a consistent user experience.
- **Animations**: The project leverages animations for a modern and engaging user experience. **Lottie animations** are used in the splash screen to introduce users to the app.
- **Typography & Color Scheme**: Clear fonts and visually appealing color schemes ensure an attractive design that enhances usability.

#### State Management (Flutter)
- **Provider**: The app uses Provider to handle application state, ensuring a smooth and efficient way to manage user data, authentication, and product/category CRUD operations.

### **5. API Design & Security**

#### API Endpoints
The **API** allows communication between the web platform (PHP) and the mobile app (Flutter). Key API endpoints include:
- **User Authentication**: Handles login and registration, with secure JWT token management.
- **Product Management**: CRUD operations for products, including uploading images and setting product details.
- **Category Management**: CRUD operations for categories.
- **User Management**: Admin users can add, update, and delete users.

#### Security
- **JWT Authentication**: All API requests use JWT tokens for secure data exchange.
- **Bearer Token**: API access is secured by passing a bearer token in the headers, allowing for role-based authentication and access control.

### **6. Backend & Database**

#### Database Structure
The database is structured to support the core functionality of the platform, including user data, product listings, and categories. Tables include:
- **Users**: Stores user details, including roles for access control.
- **Products**: Contains product information, images, prices, and categories.
- **Categories**: Manages different product categories.

#### Backend Logic
- The PHP backend serves as the API provider for the Flutter app.
- Secure API endpoints are provided for all CRUD operations, handling role-based access for different user types.

### **7. Project Architecture**

#### Folder Structure (Flutter)
```bash
lib/
|-- models/
|-- providers/
|-- screens/
|-- widgets/
|-- services/
```

- **Models**: Define the data structure for users, products, and categories.
- **Providers**: Handle the state management for users, categories, and products.
- **Screens**: Represent the UI for different functionalities like login, home, and management screens.
- **Widgets**: Reusable UI components, like buttons and input fields.
- **Services**: Manage API interactions and token-based authentication.

### **8. Testing**

#### Unit Testing
- User login functionality, product, and category CRUD operations were tested using unit tests.
- **Postman** was used for testing API endpoints, ensuring correct responses for all CRUD operations.

#### Debugging & Bug Fixes
- Regular debugging and testing during development to ensure seamless integration between the web and mobile platforms.
- Proper error handling for network requests and user input.

### **9. Conclusion & Next Steps**

The **Furni Store** and **Furni App** projects are fully functional e-commerce platforms with rich features for user, category, and product management. Future iterations of the project could include:
- **Search & Filtering**: Adding advanced search features to enhance product discoverability.
- **Payment Integration**: Integrating payment gateways like Stripe or PayPal for a complete e-commerce experience.
- **Notifications**: Adding push notifications for user engagement.
  
