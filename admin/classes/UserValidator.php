<?php


class UserValidator
{
    private $errors = [];


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

    public function validate($username, $email, $password, $password1, $role, $active = 1): array
    {
        $this->validateUsername($username);
        $this->validateEmail($email);
        $this->validatePassword($password, $password1);
        $this->validateEmail($role);
        $this->validateEmail($active);

        return $this->errors;
    }

    private function validateUsername($username)
    {
        if (empty($username)) {
            $this->errors['username'] = "Username is required.";
        } elseif (strlen($username) < 3) {
            $this->errors['username'] = "Username must be at least 3 characters long.";
        }
    }

    private function validateEmail($email)
    {
        if (empty($email)) {
            $this->errors['email'] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Invalid email format.";
        }
    }

    private function validatePassword($password, $password1)
    {
        if (empty($password)) {
            $this->errors['password'] = "Password is required.";
        } elseif ($password !== $password1) {
            $errors['passEM'] = "Passwords do not match.";
        } elseif (strlen($password) < 8) {
            $this->errors['password'] = "Password must be at least 8 characters long.";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $this->errors['password'] = "Password must contain at least one uppercase letter.";
        } elseif (!preg_match('/[a-z]/', $password)) {
            $this->errors['password'] = "Password must contain at least one lowercase letter.";
        } elseif (!preg_match('/\d/', $password)) {
            $this->errors['password'] = "Password must contain at least one number.";
        } elseif (!preg_match('/[\W]/', $password)) {
            $this->errors['password'] = "Password must contain at least one special character.";
        }
    }

    public function validateRole($role)
    {
        // Validate Role
        if (empty($role)) {
            $errors['roleE'] = "Role is required";
        }
    }

    public function validateActive($active)
    {
        // Validate active status
        if (!is_bool($active)) {
            $errors['activeE'] = "Invalid active status";
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
}
