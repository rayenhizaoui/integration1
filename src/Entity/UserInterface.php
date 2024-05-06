<?php

namespace App\Entity;

/**
 * Interface UserInterface
 */
interface UserInterface
{
    /**
     * Returns the roles granted to the user.
     *
     * @return array The user roles
     */
    public function getRoles(): array;

    /**
     * Returns the password used to authenticate the user.
     *
     * @return string The password
     */
    public function getPassword(): string;



    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): string;

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials(): void;
}
