<?php

/**
 * Class that validates user input when user has forgotten his
 * password and he tries set up a new one.
 */
class ChangePasswordForm extends BaseForm {

    /**
     * @var string User email.
     */
    private $email;
    /**
     * @var string Provided password.
     */
    private $passwordNew;
    /**
     * @var string Confirmed provided password.
     */
    private $passwordNew2;

    /**
     * ChangePasswordForm constructor.
     * @param array $post Array containing parameters from the form
     *                    when "POST" method has been used.
     */
    public function __construct(array $post) {
        parent::__construct();
        $this->email = $post['email'] ?? '';
        $this->passwordNew = $post['passwordNew'] ?? '';
        $this->passwordNew2 = $post['passwordNew2'] ?? '';
    }

    /**
     * @inheritdoc
     */
    public function validate(): void {
        $this->errors = [];
        $this->validateNewPassword($this->passwordNew, $this->passwordNew2);
    }

    /**
     * @return string Password that user provided.
     */
    public function getPasswordNew(): string {
        return $this->passwordNew;
    }

    /**
     * @return string User email.
     */
    public function getEmail(): string {
        return $this->email;
    }

}