<?php

class UserValidator
{
    private $errors = [];

    public function validate($username, $email, $password, $password1, $role, $active = 1): array
    {
        $this->validateUsername($username);
        $this->validateEmail($email);
        $this->validatePassword($password, $password1);
        $this->validateRole($role);
        $this->validateActive($active);

        // Debugging: print errors if any
        if (!empty($this->errors)) {
            error_log('Validation errors: ' . print_r($this->errors, true));
        }

        return $this->errors;
    }

    private function validateUsername($username)
    {
        // Validate Username
        if (empty($username)) {
            $this->errors['nameE'] = "Name is required";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $username)) {
            $this->errors['nameE'] = "Only letters and white space allowed";
        }
    }

    private function validateEmail($email)
    {
        // Validate Email
        if (empty($email)) {
            $this->errors['emailE'] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['emailE'] = "Invalid email format";
        }
    }

    private function validatePassword($password, $password1)
    {
        if (empty($password) || empty($password1)) {
            $this->errors['passE'] = "Password is required.";
        } elseif ($password !== $password1) {
            $this->errors['passEM'] = "Passwords do not match.";
        } elseif (strlen($password) < 8) {
            $this->errors['passE'] = "Password must be at least 8 characters long.";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $this->errors['passE'] = "Password must contain at least one uppercase letter.";
        } elseif (!preg_match('/[a-z]/', $password)) {
            $this->errors['passE'] = "Password must contain at least one lowercase letter.";
        } elseif (!preg_match('/\d/', $password)) {
            $this->errors['passE'] = "Password must contain at least one number.";
        } elseif (!preg_match('/[\W]/', $password)) {
            $this->errors['passE'] = "Password must contain at least one special character.";
        }
    }

    public function validateRole($role)
    {
        // Check if role is empty
        if (empty($role)) {
            $this->errors['roleE'] = "Role is required";
        } elseif (!is_numeric($role)) {
            // Check if role is a number
            $this->errors['roleE'] = "Role must be a valid number";
        } else {
            // Convert role to integer
            $role = (int)$role;

            // Define valid roles (adjust these based on your system's roles)
            $validRoles = [1, 2]; // Example: 1 = Admin, 2 = User

            // Check if role is valid
            if (!in_array($role, $validRoles)) {
                $this->errors['roleE'] = "Invalid role selected";
            }
        }
    }

    public function validateActive($active)
    {
        // Validate active status
        if (!is_bool($active)) {
            $this->errors['activeE'] = "Invalid active status";
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function test_input($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }
}
