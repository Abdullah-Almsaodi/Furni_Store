<?php
class UserManagementFacade
{
    private $userManager;
    private $roleManager;
    private $profileManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
        $this->roleManager = new RoleManager();
        $this->profileManager = new ProfileManager();
    }

    public function registerUser($userData)
    {
        $user = $this->userManager->createUser($userData);
        $this->roleManager->assignDefaultRole($user['id']);
        $this->profileManager->initializeProfile($user['id']);
        return $user;
    }

    public function updateProfile($userId, $profileData)
    {
        return $this->profileManager->updateProfile($userId, $profileData);
    }
}
