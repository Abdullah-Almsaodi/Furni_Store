<?php

class UserManager
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // Function to sanitize input
    private function sanitizeInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Separate function for validation logic
    public function validateUserData($name, $email, $password, $password1, $role, $active = 1): array
    {
        $errors = [];

        // Validate Name
        if (empty($name)) {
            $errors['nameE'] = "Name is required";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $errors['nameE'] = "Only letters and white space allowed";
        }

        // Validate Email
        if (empty($email)) {
            $errors['emailE'] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['emailE'] = "Invalid email format";
        }

        // Validate Passwords
        if (empty($password) || empty($password1)) {
            $errors['passE'] = "Password is required";
        } elseif ($password !== $password1) {
            $errors['passEM'] = "Passwords do not match.";
        }

        // Validate Role
        if (empty($role)) {
            $errors['roleE'] = "Role is required";
        }

        // Validate active status
        if (!is_bool($active)) {
            $errors['activeE'] = "Invalid active status";
        }

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

    // Add user method
    public function addUser(string $username, string $email, string $password, string $password1, int $role_id): array
    {
        // Check if email already exists
        if ($this->isEmailExist($email)) {
            return ['success' => false, 'errors' => ['general' => 'A user with that email already exists.']];
        }

        // Validate user data
        $errors = $this->validateUserData($username, $email, $password, $password1, $role_id);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Hash the password and generate activation token
        $hashedPassword = $this->hashPassword($password);
        $activationToken = $this->generateActivationToken();

        // Add the user via repository
        $added = $this->userRepository->addUser($username, $email, $hashedPassword, $activationToken, $role_id);
        return $added ? ['success' => true] : ['success' => false, 'errors' => ['general' => 'Failed to add user']];
    }

    // Update user method
    public function updateUser(int $id, string $name, string $email, ?string $password, string $password1, int $role, int $active): array
    {
        // Validate input data
        $errors = $this->validateUserData($name, $email, $password, $password1, $role, $active);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Hash password only if provided
        $hashedPassword = !empty($password) ? $this->hashPassword($password) : null;

        // Update user through repository
        $updated = $this->userRepository->updateUser($id, $name, $email, $hashedPassword, $role, $active);
        return $updated ? ['success' => true] : ['success' => false, 'errors' => ['general' => 'Failed to update user']];
    }

    // Get user by ID
    public function getUserById(int $id)
    {
        return $this->userRepository->getUserById($id);
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

    // Hard delete user
    public function deleteUser(int $user_id): array
    {
        $deleted = $this->userRepository->deleteUser($user_id);
        return $deleted ? ['success' => true] : ['success' => false, 'errors' => ['general' => 'Failed to delete user']];
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
