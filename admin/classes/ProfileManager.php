<?php
class ProfileManager
{
    private $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->connect();
    }

    // Initialize a profile for a new user
    public function initializeProfile(int $user_id): void
    {
        $query = "INSERT INTO profiles (user_id) VALUES (:user_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to initialize profile: " . implode(', ', $stmt->errorInfo()));
        }
    }

    // Update the user's profile
    public function updateProfile(int $user_id, array $profileData): void
    {
        $query = "UPDATE profiles SET first_name = :first_name, last_name = :last_name, 
                  bio = :bio, avatar = :avatar WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':first_name', $profileData['first_name']);
        $stmt->bindParam(':last_name', $profileData['last_name']);
        $stmt->bindParam(':bio', $profileData['bio']);
        $stmt->bindParam(':avatar', $profileData['avatar']);
        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update profile: " . implode(', ', $stmt->errorInfo()));
        }
    }

    // Retrieve a user's profile by user ID
    public function getProfileByUserId(int $user_id): array
    {
        $query = "SELECT * FROM profiles WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete a profile (useful for cleanup on user deletion)
    public function deleteProfile(int $user_id): void
    {
        $query = "DELETE FROM profiles WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete profile: " . implode(', ', $stmt->errorInfo()));
        }
    }
}