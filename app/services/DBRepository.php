<?php

/**
 * Allows communication with data base.
 */
class DBRepository {

    /**
     * Data base user name.
     */
    private const USERNAME="root";
    /**
     * Data base password.
     */
    private const PASSWORD="12345678";
    /**
     * Data base host address.
     */
    private const HOST="localhost";
    /**
     * Data base name.
     */
    private const DB="ssp";
    /**
     * File that contains MySQL commands for creating tables.
     */
    private const CREATE_TABLES_FILE = '../app/create_tables.sql';
    /**
     * @var PDO Data base connection.
     */
    private $pdo;

    /**
     * DBRepository constructor. Sets up connection with the data base and
     * creates tables if they don't exist.
     */
    public function __construct() {
        $dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::DB;
        $pdo = new PDO($dsn, self::USERNAME, self::PASSWORD);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        $sql = file_get_contents(self::CREATE_TABLES_FILE);
        $pdo->exec($sql);

        $this->pdo = $pdo;
    }

    /**
     * Checks if user with given email exists.
     *
     * @param string $email User email.
     * @return bool True if user with given email exists, else false.
     */
    public function userWithEmailExists(string $email): bool {
        $sql = 'SELECT * FROM users WHERE email = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);

        return 1 === $stmt->rowCount();
    }

    /**
     * Searches for user with given name. If such user exists
     * he is returned, else null is returned.
     *
     * @param string $userName Name of user that is being searched for.
     * @return null|User User if one is found, else null.
     */
    public function findUserByName(string $userName): ?User {
        $sql = 'SELECT * FROM users WHERE name = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userName]);

        if (1 === $stmt->rowCount()) {
            $user = $stmt->fetch();
            return new User(
                $user->id,
                $user->name,
                $user->password,
                $user->email,
                $user->premium,
                $user->administrator
            );
        }

        return null;
    }

    /**
     * Searches for user with given email. If such user exists
     * he is returned, else null is returned.
     *
     * @param string $email Email of user that is being searched for.
     * @return null|User User if one is found, else null.
     */
    public function findUserByEmail(string $email): ?User {
        $sql = 'SELECT * FROM users WHERE email = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);

        if (1 === $stmt->rowCount()) {
            $user = $stmt->fetch();
            return new User(
                $user->id,
                $user->name,
                $user->password,
                $user->email,
                $user->premium,
                $user->administrator
            );
        }

        return null;
    }

    /**
     * Stores given user into the "users" table in the data base.
     *
     * @param User $user User being stored.
     */
    public function saveUser(User $user): void {
        $sql = 'INSERT INTO users(name, password, email) VALUES(:name, :password, :email)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $user->getName(),
            'password' => $user->getPasswordHash(),
            'email' => $user->getEmail()
        ]);
    }

    /**
     * Stores parameters for password update link into the
     * "update_password" table in the data base.
     *
     * @param string $email Email from user that requested password update link.
     * @param string $token Unique token that is part of the password update link.
     */
    public function saveParametersForPasswordUpdateLink(string $email, string $token): void {
        $sql = 'REPLACE INTO update_password(email, token) VALUES(:email, :token)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'token' => $token
        ]);
    }

    /**
     * Deletes tuple from "update_password"
     * table where email equals $email.
     *
     * @param string $email User email.
     */
    public function disableLink(string $email): void {
        $sql = 'DELETE FROM update_password WHERE email = :email';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
    }

    /**
     * Checks if entry in "update_password" table with provided
     * email and token exists. If it does exist and if it's created
     * in the last hour, link is seen as valid.
     *
     * @param string $email User email.
     * @param string $token Unique token.
     * @return bool True if parameters exist and if tuple containing them
     *              is created in the last hour, else false.
     */
    public function linkIsValid(string $email, string $token): bool {
        $sql = 'SELECT * FROM update_password '.
                'WHERE email = ? AND token = ? AND (now()-link_sent) < 3600';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email, $token]);

        return 1 === $stmt->rowCount();
    }

    /**
     * For user with email $email, sets password as $password.
     *
     * @param string $email User email.
     * @param string $password User new password..
     */
    public function updatePasswordByEmail(string $email, string $password): void {
        $sql = 'UPDATE users SET password = :password WHERE email = :email';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'password' => $password
        ]);
    }

    /**
     * For user with name $name, sets password as $password.
     *
     * @param string $name User name.
     * @param string $password User new password.
     */
    public function updatePasswordByUserName(string $name, string $password): void {
        $sql = 'UPDATE users SET password = :password WHERE name = :name';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'password' => $password
        ]);
    }

    /**
     * Fetches all users from the "users" table.
     *
     * @return array Array containing all user from the table.
     */
    public function getAllUsers(): array {
        $stmt = $this->pdo->query('SELECT * FROM users');
        $users = [];

        while ($row = $stmt->fetch()) {
            array_push($users, new User(
                $row->id,
                $row->name,
                $row->password,
                $row->email,
                $row->premium,
                $row->administrator
            ));
        }

        return $users;
    }

    /**
     * Sets premium field as $premium for user with id $userId.
     *
     * @param int $userId User id.
     * @param bool $premium If true it will be set to true, if false it
     *                      will be set to false.
     */
    public function updatePremium(int $userId, bool $premium): void {
        $sql = 'UPDATE users SET premium = :premium WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'premium' => $premium,
            'id' => $userId
        ]);
    }

    /**
     * Sets admin field as $admin for user with id $userId.
     *
     * @param int $userId User id.
     * @param bool $admin If true it will be set to true, if false it
     *                    will be set to false.
     */
    public function updateAdmin(int $userId, bool $admin): void {
        $sql = 'UPDATE users SET administrator = :admin WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'admin' => $admin,
            'id' => $userId
        ]);
    }

    /**
     * Delete user with id $userId from the "users" table.
     *
     * @param int $userId User id.
     */
    public function removeUser(int $userId): void {
        $sql = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $userId]);
    }

    /**
     * Fetch user with id $userId. If it doesn't exist return null.
     *
     * @param int $userId User id.
     * @return null|User User if one is found, else null.
     */
    public function getUserById(int $userId): ?User {
        $sql = 'SELECT * FROM users WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $userId]);

        if (1 === $stmt->rowCount()) {
            $user = $stmt->fetch();
            return new User(
                $user->id,
                $user->name,
                $user->password,
                $user->email,
                $user->premium,
                $user->administrator
            );
        }

        return null;
    }

    /**
     * Stores normalization in the "norms" table.
     *
     * @param int $userId User id from user that owns this normalization.
     * @param string $norm Normalization.
     * @param int $text Number of text normalizations applied.
     * @param int $phone Number of phone normalizations applied.
     * @param int $date Number of date normalizations applied.
     */
    public function storeNorm(int $userId, string $norm, int $text,
                                        int $phone, int $date): void {
        $sql = 'INSERT INTO norms(user_id, norm, text, phone, date)'.
                  'VALUES(:user_id, :norm, :text, :phone, :date)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'norm' => $norm,
            'text' => $text,
            'phone' => $phone,
            'date' => $date
        ]);
    }

    /**
     * Returns number of stored normalizations by the user with id $userId.
     *
     * @param int $userId User id.
     * @return int Number of stored normalizations by the user with id $userId.
     */
    public function getNormCount(int $userId): int {
        $sql = 'SELECT * FROM norms WHERE user_id = :user_id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->rowCount();
    }

    /**
     * Fetches all normalizations owned by the user with id $userId.
     *
     * @param int $userId User id.
     * @return array All normalizations owned by the user with id $userId.
     */
    public function getUserNorms(int $userId): array {
        $sql = 'SELECT * FROM norms WHERE user_id = :user_id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $norms = [];

        while ($row = $stmt->fetch()) {
            array_push($norms, new Norm(
                $row->id,
                $userId,
                $row->norm,
                $row->text,
                $row->phone,
                $row->date
            ));
        }

        return $norms;
    }

    /**
     * Deletes normalization.
     *
     * @param int $normId Normalization id.
     * @param int $userId Normalization owner id.
     */
    public function deleteNorm(int $normId, int $userId): void {
        $sql = 'DELETE FROM norms WHERE id = :id AND user_id = :user_id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $normId, 'user_id' => $userId]);
    }

    /**
     * Fetches user id from user that is owner of the normalization
     * with id $normId.
     *
     * @param int $normId Normalization id.
     * @return int|null User id if normalization with id $normId exists,
     *                  else returns null.
     */
    public function getUserIdFromNormId(int $normId): ?int {
        $sql = 'SELECT user_id FROM norms WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $normId]);

        if (1 === $stmt->rowCount()) {
            $norm = $stmt->fetch();
            return $norm->user_id;
        }

        return null;
    }

}