<?php

class UserManager
{
    private $userRepository;
    private $passwordService;
    private $userValidator;

    public function __construct(UserRepository $userRepository, PasswordService $passwordService, UserValidator $userValidator)
    {
        $this->userRepository = $userRepository;
        $this->passwordService = $passwordService;
        $this->userValidator = $userValidator;
    }

    // Function to sanitize input
    private function sanitizeInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }



    public function addUser(string $username, string $email, string $password, string $password1, int $role_id): array
    {
        // Validate user data
        $errors = $this->userValidator->validate($username, $email, $password, $password1, $role_id);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Hash password using PasswordService
        $hashedPassword = $this->passwordService->hashPassword($password);

        // Generate activation token
        $activationToken = $this->generateActivationToken();

        // Add the user via repository
        $added = $this->userRepository->addUser($username, $email, $hashedPassword, $activationToken, $role_id);
        return $added ? ['success' => true] : ['success' => false, 'errors' => ['general' => 'Failed to add user']];
    }


    // Update user method
    public function updateUser(int $id, string $username, string $email, ?string $password, string $password1, int $role, int $active): array
    {
        // Validate input data
        $errors = $this->userValidator->validate($username, $email, $password, $password1, $role, $active);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Hash password only if provided
        $hashedPassword = !empty($password) ? $this->passwordService->hashPassword($password) : null;

        // Update user through repository
        $updated = $this->userRepository->updateUser($id, $username, $email, $hashedPassword, $role, $active);
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


    // Generate activation token
    public function generateActivationToken(): string
    {
        return bin2hex(random_bytes(16));
    }
}
