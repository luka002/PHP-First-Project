<?php

/**
 * Class that validates user input when user tries to log in.
 */
class LoginForm extends BaseForm {

    /**
     * @var string User name.
     */
    private $name;
    /**
     * @var string User password.
     */
    private $password;
    /**
     * @var DBRepository Allows communication with database.
     */
    private $dbRepository;

    /**
     * LoginForm constructor.
     * @param array $post Array containing parameters from the form
     *                    when "POST" method has been used.
     * @param DBRepository $dbRepository Allows communication with database.
     */
    public function __construct(array $post, DBRepository $dbRepository) {
        parent::__construct();
        $this->name = $post['name'] ?? '';
        $this->password = $post['password'] ?? '';
        $this->dbRepository = $dbRepository;
    }

    /**
     * @inheritdoc
     */
    public function validate(): void {
        $this->errors = [];
        $this->validateName($this->name, $this->dbRepository, true);
        $this->validatePassword(
            $this->password,
            $this->dbRepository->findUserByName($this->name)
        );
    }

    /**
     * @return string User name.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string User password.
     */
    public function getPassword(): string {
        return $this->password;
    }

}