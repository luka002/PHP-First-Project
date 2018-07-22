<?php

/**
 * Class that validates user input when user tries to
 * change his password.
 */
class EditProfileForm extends BaseForm {

    /**
     * @var string Current password.
     */
    private $passwordCurrent;
    /**
     * @var string New password.
     */
    private $passwordNew;
    /**
     * @var string Repeated new password.
     */
    private $passwordNew2;
    /**
     * @var DBRepository Allows communication with database.
     */
    private $dbRepository;
    /**
     * @var null|User User.
     */
    private $user;

    /**
     * EditProfileForm constructor.
     * @param array $post Array containing parameters from the form
     *                    when "POST" method has been used.
     * @param DBRepository $dbRepository Allows communication with database.
     */
    public function __construct(array $post, DBRepository $dbRepository) {
        parent::__construct();
        $this->passwordCurrent = $post['passwordCurrent'] ?? '';
        $this->passwordNew = $post['passwordNew'] ?? '';
        $this->passwordNew2 = $post['passwordNew2'] ?? '';
        $this->dbRepository = $dbRepository;
        $this->user = $dbRepository->findUserByName($post['name'] ?? '');
    }

    /**
     * @inheritdoc
     */
    public function validate(): void {
        $this->errors = [];
        $this->validatePassword($this->passwordCurrent, $this->user);
        $this->validateNewPassword($this->passwordNew, $this->passwordNew2);
    }

    /**
     * Updates user password.
     */
    public function updatePassword(): void {
        $this->dbRepository->updatePasswordByUserName(
            $this->user->getName(), password_hash($this->passwordNew, PASSWORD_ARGON2I)
        );
    }

    /**
     * @return string Provided password.
     */
    public function getPasswordNew(): string {
        return $this->passwordNew;
    }

}