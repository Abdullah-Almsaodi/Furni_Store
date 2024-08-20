<?php
// admin/classes/UserManager.php 

class UserManager
{
    private $userRepository;
    private $roleManager;
    private $passwordHasher;

    public function __construct(UserRepository $userRepository, RoleManager $roleManager, PasswordHasher $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->roleManager = $roleManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function addUser($name, $email, $password, $role_type)
    {
        if ($this->userRepository->isEmailExist($email)) {
            throw new Exception("A user with that email already exists.");
        }

        $hashedPassword = $this->passwordHasher->hash($password);
        $activationToken = bin2hex(random_bytes(16));

        $userId = $this->userRepository->addUser($name, $email, $hashedPassword, $activationToken);

        $this->roleManager->assignRoleToUser($role_type, $userId);

        $this->showSuccessModal();
    }

    public function getUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function deleteUser($id)
    {
        $this->userRepository->deleteUser($id);
    }

    private function showSuccessModal()
    {
        echo '<script type="text/javascript">
            $(document).ready(function() {
                $("#successModal").modal("show");
            });
            </script>';
    }
}