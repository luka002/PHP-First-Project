<?php

/**
 * Abstract class that any concrete form will inherit.
 * Purpose of this class is to reduce code duplication.
 */
abstract class BaseForm {

    /**
     * @var array Array that contains all errors in the form.
     */
    protected $errors;

    /**
     * BaseForm constructor.
     */
    public function __construct() {
        $this->errors = [];
    }

    /**
     * Checks for errors in the form. If errors are found,
     * they will be added to $errors array.
     */
    public abstract function validate(): void;

    /**
     * Checks if any errors are present.
     *
     * @return bool True if the form has an error, else false.
     */
    public function hasErrors(): bool {
        return 0 != count($this->errors);
    }

    /**
     * Checks if the form has an error under the given key.
     *
     * @param string $name Error key.
     * @return bool True is an error under the given key exists, else false.
     */
    public function hasError(string $name): bool {
        return array_key_exists($name, $this->errors);
    }

    /**
     * Fetches the error under the given key.
     *
     * @param string $name Error key.
     * @return string Error under the given key.
     */
    public function getError(string $name): string {
        return $this->errors[$name];
    }

    /**
     * Check if provided name is valid.
     * Valid name can contain only letters, number and characters "-" and "_".
     * Name also has to contain at least one letter and it can not be longer
     * than 24 characters.
     *
     * @param string $name User name.
     * @param DBRepository $dbRepository Allows communication with data base.
     * @param bool $login True if user is trying to log in, else false.
     */
    protected function validateName(string $name, DBRepository $dbRepository, bool $login): void {
        if (!preg_match("/^(?=\p{L})[\p{L}\p{N}-_]{1,24}$/u", $name)) {
            $this->errors['name'] = 'Letters, numbers, \'-\' and \'_\' characters allowed<br>'.
                                    '(min one letter, max 24 characters)';

        } else if (!$login && null != $dbRepository->findUserByName($name)) {
            $this->errors['name'] = 'User name already exists';

        } else if ($login && null == $dbRepository->findUserByName($name)) {
            $this->errors['name'] = 'User name does not exists';
        }
    }

    /**
     * Checks if provided email is valid.
     *
     * @param string $email User email.
     * @param DBRepository $dbRepository Allows communication with data base.
     */
    protected function validateEmail(string $email, DBRepository $dbRepository): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Invalid email format';

        } else if (null != $dbRepository->findUserByEmail($email)) {
            $this->errors['email'] = 'User with provided email already exists';
        }
    }

    /**
     * Checks if provided password matches current password.
     *
     * @param string $password Provided password.
     * @param User|null $user User.
     */
    protected function validatePassword(string $password, User $user = null): void {
        if (null === $user || !password_verify($password, $user->getPasswordHash())) {
            $this->errors['password'] = 'Provided password does not match current password';
        }
    }

    /**
     * Checks is provided passwords are valid. Passwords have to be
     * at least 12 characters long and they have to be identical.
     *
     * @param string $passwordNew Provided password.
     * @param string $passwordNew2 Confirmed provided password.
     */
    protected function validateNewPassword(string $passwordNew, string $passwordNew2): void {
        if (mb_strlen($passwordNew) < 12) {
            $this->errors['passwordNew'] = 'Password too short, at least 12 characters expected';

        } else if ($passwordNew !== $passwordNew2) {
            $this->errors['passwordNew'] = 'Passwords don\'t match';
        }
    }

}