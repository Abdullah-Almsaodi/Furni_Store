<?php

class UserManagementFacade
{
    private $userManager;
    private $roleManager;
    private $profileManager;

    public function __construct(UserManager $userManager, RoleManager $roleManager, ProfileManager $profileManager)
    {
        $this->userManager = $userManager;
        $this->roleManager = $roleManager;
        $this->profileManager = $profileManager;
    }

    public function registerUser($userData)
    {
        $user = $this->userManager->addUser(
            $userData['username'],
            $userData['email'],
            $userData['password'],
            $userData['role_id']
        );
        $this->roleManager->assignRoleToUser($user['id'], $user['role_id']);
        $this->profileManager->initializeProfile($user['id']);
        return $user;
    }

    public function updateProfile($userId, $profileData)
    {
        return $this->profileManager->updateProfile($userId, $profileData);
    }
}