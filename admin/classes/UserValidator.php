<?php


class UserValidator
{
    private $errors = [];

    public function validate($username, $email, $password): array
    {
        $this->validateUsername($username);
        $this->validateEmail($email);
        $this->validatePassword($password);

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

    private function validatePassword($password)
    {
        if (empty($password)) {
            $this->errors['password'] = "Password is required.";
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

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
