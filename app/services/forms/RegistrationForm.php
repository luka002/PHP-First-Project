<?php

/**
 * Class that validates user input when user tries to register.
 */
class RegistrationForm extends BaseForm {

    /**
     * @var string User name.
     */
    private $name;
    /**
     * @var string User email.
     */
    private $email;
    /**
     * @var string User password.
     */
    private $passwordNew;
    /**
     * @var string User password repeated.
     */
    private $passwordNew2;
    /**
     * @var DBRepository Allows communication with data base.
     */
    private $dbRepository;

    /**
     * RegistrationForm constructor.
     * @param array $post Array containing parameters from the form
     *                    when "POST" method has been used.
     * @param DBRepository $dbRepository Allows communication with data base.
     */
    public function __construct(array $post, DBRepository $dbRepository) {
        parent::__construct();
        $this->name = $post['name'] ?? '';
        $this->email = $post['email'] ?? '';
        $this->passwordNew = $post['passwordNew'] ?? '';
        $this->passwordNew2 = $post['passwordNew2'] ?? '';
        $this->dbRepository = $dbRepository;
    }

    /**
     * @inheritdoc
     */
    public function validate(): void {
        $this->errors = [];
        $this->validateName($this->name, $this->dbRepository, false);
        $this->validateEmail($this->email, $this->dbRepository);
        $this->validateNewPassword($this->passwordNew, $this->passwordNew2);
    }

    /**
     * Saves user in the data base.
     */
    public function saveUser(): void {
        $this->dbRepository->saveUser(new User(
            null,
            $this->name,
            ''.password_hash($this->passwordNew, PASSWORD_ARGON2I),
            $this->email,
            false,
            false
        ));
    }

    /**
     * @return string User name.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

}