<?php
class PasswordService
{
    /**
     * Hashes the password using the bcrypt algorithm.
     *
     * @param string $password The plain password to hash.
     * @return string The hashed password.
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verifies a plain password against a hashed password.
     *
     * @param string $password The plain password.
     * @param string $hashedPassword The hashed password to compare against.
     * @return bool True if the password matches, false otherwise.
     */
    public function verifyPassword(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }

    /**
     * Checks if a password hash needs to be rehashed, for example if the algorithm or cost has changed.
     *
     * @param string $hashedPassword The hashed password.
     * @return bool True if the password needs rehashing, false otherwise.
     */
    public function needsRehash(string $hashedPassword): bool
    {
        return password_needs_rehash($hashedPassword, PASSWORD_BCRYPT);
    }
}
