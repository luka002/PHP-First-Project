<?php

/**
 * Class containing information about user.
 */
class User {

    /**
     * @var int|null User ID.
     */
    private $id;
    /**
     * @var string User name.
     */
    private $name;
    /**
     * @var string User password as hash.
     */
    private $passwordHash;
    /**
     * @var string User email.
     */
    private $email;
    /**
     * @var bool True if user is premium, else false.
     */
    private $premium;
    /**
     * @var bool True if user is admin, else false.
     */
    private $admin;

    /**
     * User constructor.
     * @param int|null $id User ID.
     * @param string $name User name.
     * @param string $passwordHash User password as hash.
     * @param string $email User email.
     * @param bool $premium User premium status.
     * @param bool $admin User admin status.
     */
    public function __construct(int $id = null, string $name, string $passwordHash,
                                string $email, bool $premium, bool $admin) {
        $this->id = $id;
        $this->name = $name;
        $this->passwordHash = $passwordHash;
        $this->email = $email;
        $this->premium = $premium;
        $this->admin = $admin;
    }

    /**
     * @return int User ID.
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string User name.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string User password as hash.
     */
    public function getPasswordHash(): string {
        return $this->passwordHash;
    }

    /**
     * @return string User email.
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @return bool User premium status.
     */
    public function getPremium(): bool {
        return $this->premium;
    }

    /**
     * @return bool User admin status.
     */
    public function getAdmin(): bool {
        return $this->admin;
    }

    /**
     * Checks if user is admin, premium or regular.
     *
     * @return string Type of user. Possible types are: "admin", "premium"
     *                and "regular".
     */
    public function getType(): string {
        if ($this->getAdmin()) {
            return 'admin';
        }

        if ($this->getPremium()) {
            return 'premium';
        }

        return 'regular';
    }

}