<?php

class UserManager
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validateUserData($name, $email, $password, $password1, $role_type, $active = null)
    {
        $errors = [];

        if (empty($name)) {
            $errors['nameE'] = "Name is required";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $errors['nameE'] = "Only letters and white space allowed";
        }

        if (empty($email)) {
            $errors['emailE'] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['emailE'] = "Invalid email format";
        }


        if (empty($password) || empty($password1)) {
            $errors['passE'] = "Password is required";
        } elseif ($password !== $password1) {
            $errors['passEM'] = "Passwords do not match.";
        }

        if (empty($role_type)) {
            $errors['roleE'] = "Role is required";
        }

        if (!in_array($active, ['1', '0'], true)) {
            $errors['activeE'] = "Active status is required.";
        }

        return $errors;
    }

    public function isEmailExist(string $email): bool
    {
        return $this->userRepository->isEmailExist($email);
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function generateActivationToken(): string
    {
        return bin2hex(random_bytes(16));
    }

    public function addUser(string $username, string $email, string $password, int $role_id): void
    {
        if ($this->isEmailExist($email)) {
            throw new Exception("A user with that email already exists.");
        }

        $hashedPassword = $this->hashPassword($password);
        $activationToken = $this->generateActivationToken();

        $this->userRepository->addUser($username, $email, $hashedPassword, $activationToken, $role_id);
    }

    public function updateUser($id, $name, $email, $password, $role, $active)
    {
        // Validate the inputs
        $errors = $this->validateUserData($name, $email, $password, $role, $active);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Update user
        $updated = $this->userRepository->updateUser($id, $name, $email, $password, $role, $active);
        if ($updated) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to update user']];
        }
    }

    public function getUserById($id) {
        return $this->userRepository->getUserById($id);
    }



    public function getUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    public function softDeleteUser(int $user_id): void
    {
        $this->userRepository->softDeleteUser($user_id);
    }

    public function deleteUser(int $user_id): void
    {
        $this->userRepository->deleteUser($user_id);
    }

    public function updateUserRole(int $user_id, int $role_id): void
    {
        $this->userRepository->updateUserRole($user_id, $role_id);
    }

    public function reactivateUser(int $user_id): void
    {
        $this->userRepository->reactivateUser($user_id);
    }

    public function testInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }
}