<?php

class UserProfileProxy
{
    private $realProfile;
    private $logger;

    public function __construct($realProfile)
    {
        $this->realProfile = $realProfile;
        $this->logger = new Logger(); // Assume a Logger class exists
    }

    public function getProfile()
    {
        $this->logger->log("Profile accessed for user ID: " . $this->realProfile->getUserId());
        return $this->realProfile->getProfile();
    }
}
