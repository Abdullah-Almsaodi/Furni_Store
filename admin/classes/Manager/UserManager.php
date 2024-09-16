<?php

class UserManager
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }



    // Add user
    public function addUser(string $username, string $email, string $password, string $password1, int $role_id)
    {
        if ($this->isEmailExist($email)) {
            return ['success' => false, 'errors' => ['emailE' => 'A user with that email already exists.']];
        }

        $errors = $this->validateUserData($username, $email, $password, $password1, $role_id);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $hashedPassword = $this->hashPassword($password);
        $activationToken = $this->generateActivationToken();

        $add = $this->userRepository->addUser($username, $email, $hashedPassword, $activationToken, $role_id);
        if ($add) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to add user']];
        }
    }

    // Update user
    public function updateUser($id, $name, $email, $password, $password1, $role_id, $active)
    {
        // Validate inputs
        $errors = $this->validateUserData($name, $email, $password, $password1, $role_id, $active);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Hash password if provided
        if (!empty($password)) {
            $hashedPassword = $this->hashPassword($password);
        } else {
            // If password is empty, do not update it
            $hashedPassword = null;
        }

        // Update user in repository
        $updated = $this->userRepository->updateUser($id, $name, $email, $hashedPassword, $role_id, $active);
        if ($updated) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to update user']];
        }
    }

    // Consistently named method
    public function test_input($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Updated to use parameters instead of $_POST
    public function validateUserData($name, $email, $password, $password1, $role, $active = 1)
    {
        $errors = [];

        // Validate Name
        if (empty($name)) {
            $errors['nameE'] = "Name is required";
        } else {
            $name = $this->test_input($name);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $errors['nameE'] = "Only letters and white space allowed";
            }
        }

        // Validate Email
        if (empty($email)) {
            $errors['emailE'] = "Email is required";
        } else {
            $email = $this->test_input($email);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['emailE'] = "Invalid email format";
            }
        }

        // Validate Passwords
        if (empty($password) || empty($password1)) {
            $errors['passE'] = "Password is required";
        } else {
            $password = $this->test_input($password);
            $password1 = $this->test_input($password1);
            if ($password !== $password1) {
                $errors['passEM'] = "Passwords do not match.";
            }
        }

        // Check if role is empty
        if (empty($role)) {
            $errors['roleE'] = "Role is required";
        } elseif (!is_numeric($role)) {
            // Check if role is a number
            $errors['roleE'] = "Role must be a valid number";
        } else {
            // Convert role to integer
            $role = (int)$role;

            // Define valid roles (adjust these based on your system's roles)
            $validRoles = [1, 2]; // Example: 1 = Admin, 2 = User

            // Check if role is valid
            if (!in_array($role, $validRoles)) {
                $errors['roleE'] = "Invalid role selected";
            }
        }

        // Validate active
        if (empty($active)) {
            $errors['activeE'] = "Active is required";
        } else {
            $active = $this->test_input($active);
        }

        // Active status can be validated here if needed

        return $errors;
    }

    // Check if email exists
    public function isEmailExist(string $email): bool
    {
        return $this->userRepository->isEmailExist($email);
    }

    // Hash password
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    // Generate activation token
    public function generateActivationToken(): string
    {
        return bin2hex(random_bytes(16));
    }
    // Get user by ID
    public function getUserById($id)
    {
        $get = $this->userRepository->getUserById($id);
        if ($get) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'User Not Found ']];
        }
    }

    // Get all users
    public function getUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    // Soft delete user
    public function softDeleteUser(int $user_id): void
    {
        $this->userRepository->softDeleteUser($user_id);
    }

    // Delete user
    public function deleteUser(int $user_id)
    {

        $delete = $this->userRepository->deleteUser($user_id);

        if ($delete == 1) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to delete user']];
        }
    }

    // Update user role
    public function updateUserRole(int $user_id, int $role_id): void
    {
        $this->userRepository->updateUserRole($user_id, $role_id);
    }

    // Reactivate user
    public function reactivateUser(int $user_id): void
    {
        $this->userRepository->reactivateUser($user_id);
    }
}
