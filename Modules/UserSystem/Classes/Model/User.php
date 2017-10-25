<?php
/**
 * This file is part of SmartWork.
 *
 * SmartWork is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SmartWork is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with SmartWork.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2015, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork;

use \SmartWork\Model;

/**
 * User model for login, registration and check of privileges.
 *
 * @package SmartWork
 * @author  Marian Pollzien <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class User extends Model
{
    /**
     * @var integer
     */
    protected $userId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var boolean
     */
    protected $admin;

    /**
     * @var integer
     */
    protected $languageId;

    /**
     * @var boolean
     */
    protected $active;

    /**
     * Get the logged in user by userId.
     *
     * @param int $userId
     *
     * @return \self
     */
    public static function getUserById(int $userId): self
    {
        $sql = '
            SELECT
                `userId`,
                `name`,
                `password`,
                `email`,
                `languageId`,
                `active`,
                `admin`
            FROM `users`
            WHERE `userId` = ' . $this->db->sqlval($userId) . '
                AND !`deleted`
        ';
        $userData = $this->db->execute($sql);

        $object = new self();
        $object->userId        = intval($userData['userId']);
        $object->name          = $userData['name'];
        $object->password      = $userData['password'];
        $object->email         = $userData['email'];
        $object->languageId    = $userData['languageId'];
        $object->admin         = !!$userData['admin'];
        $object->orderDuration = $userData['orderDuration'];
        $object->active        = !!$userData['active'];

        return $object;
    }

    /**
     * Get the user that wants to log in.
     *
     * @param string $name
     * @param string $password
     *
     * @return boolean|\self
     */
    public static function getUser(string $name, string $password)
    {
        $sql = '
            SELECT
                `userId`,
                `name`,
                password
            FROM users
            WHERE name = ' . $this->db->sqlval($name) . '
                AND !deleted
        ';
        $userData = $this->db->execute($sql);
        $passwordParts = explode('$', $userData['password']);

        $encPassword = self::encryptPassword($password, $passwordParts['2']);

        if (strcasecmp($name, $userData['name']) === 0 && $encPassword == $userData['password'])
        {
            return self::getUserById($userData['userId']);
        }
        else
        {
            return false;
        }
    }

    /**
     * Get the user by the entered email address
     *
     * @param string $mail
     *
     * @return boolean|\self
     */
    public static function getUserByMail(string $mail)
    {
        $sql = '
            SELECT `userId`
            FROM users
            WHERE email = ' . $this->db->sqlval($mail) . '
                AND !deleted
        ';
        $userId = $this->db->execute($sql);

        if (!$userId)
        {
            return false;
        }

        return self::getUserById($userId);
    }

    /**
     * Create a new user and save it into the database.
     *
     * @param string $name
     * @param string $password
     * @param string $email
     *
     * @return int
     */
    public static function createUser(
        string $name, string $password, string $email
    ): int
    {
        $sql = '
            INSERT INTO users
            SET name = ' . $this->db->sqlval($name) . ',
                password = ' . $this->db->sqlval(
                    self::encryptPassword($password, uniqid())
                ) . ',
                email = ' . $this->db->sqlval($email) . ',
                `languageId` = ' . $this->db->sqlval(
                    Translator::getInstance()->getCurrentLanguage()
                ) . '
        ';
        return $this->db->execute($sql);
    }

    /**
     * Checks if the username is already in use or not.
     *
     * @param string $name
     *
     * @return bool
     */
    public static function checkUsername(string $name): bool
    {
        $sql = '
            SELECT COUNT(*)
            FROM users
            WHERE name = ' . $this->db->sqlval($name) . '
        ';
        return !!$this->db->execute($sql);
    }

    /**
     * Check if the email is already in use or not.
     *
     * @param string $email
     *
     * @return bool
     */
    public static function checkEmail(string $email): bool
    {
        $sql = '
            SELECT COUNT(*)
            FROM users
            WHERE email = ' . $this->db->sqlval($email) . '
        ';
        return !!$this->db->execute($sql);
    }

    /**
     * Encrypt the user password and a salt with md5.
     *
     * @param string $password
     * @param string $salt
     *
     * @return string
     */
    protected static function encryptPassword(
        string $password, string $salt
    ): string
    {
        $saltedPassword = $salt ? $password . '-' . $salt : $password;
        return '$m5$' . $salt . '$' . md5($saltedPassword);
    }

    /**
     * Get the internal user id.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Get the users nickname.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the users email address.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Whether the user has admin privilege or not.
     *
     * @deprecated since version 2.0, will be removed in two versions
     * @return bool
     */
    public function getAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * Whether the user has admin privilege or not.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * Whether the user is activated or not.
     *
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->active;
    }

    /**
     * Get the language id of the user.
     *
     * @return int
     */
    public function getLanguageId(): int
    {
        return $this->languageId;
    }

    /**
     * Set the users nickname.
     *
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
        $sql = '
            UPDATE users
            SET name = ' . $this->db->sqlval($this->name) . '
            WHERE userId = ' . $this->db->sqlval($this->userId) . '
        ';
        $this->db->execute($sql);
    }

    /**
     * Encrypts and sets the password.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword(string $password)
    {
        $passwordParts = explode('$', $this->password);
        $this->password = self::encryptPassword($password, $passwordParts[2]);
        $sql = '
            UPDATE users
            SET password = ' . $this->db->sqlval($this->password) . '
            WHERE userId = ' . $this->db->sqlval($this->userId) . '
        ';
        $this->db->execute($sql);
    }

    /**
     * Set the users email.
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
        $sql = '
            UPDATE users
            SET email = ' . $this->db->sqlval($this->email) . '
            WHERE userId = ' . $this->db->sqlval($this->userId) . '
        ';
        $this->db->execute($sql);
    }

    /**
     * Activate the current user.
     *
     * @return void
     */
    public function activate()
    {
        $sql = '
            UPDATE users
            SET active = 1
            WHERE userId = ' . $this->db->sqlval($this->userId) . '
        ';
        $this->db->execute($sql);
        $this->active = true;
    }

    /**
     * Set the users admin privilege.
     *
     * @param bool $admin
     *
     * @return void
     */
    public function setAdmin(bool $admin)
    {
        $this->admin = $admin;
        $sql = '
            UPDATE users
            SET admin = ' . $this->db->sqlval($admin ? 1 : 0) . '
            WHERE userId = ' . $this->db->sqlval($this->userId) . '
        ';
        $this->db->execute($sql);
    }

    /**
     * Set the users language.
     *
     * @param int $languageId
     *
     * @return void
     */
    public function setLanguageId(int $languageId)
    {
        $this->languageId = $languageId;
        $sql = '
            UPDATE users
            SET `languageId` = ' . $this->db->sqlval($languageId) . '
            WHERE `userId` = ' . $this->db->sqlval($this->userId) . '
        ';
        $this->db->execute($sql);
    }

    /**
     * Reset the users password and send him an email with the new password.
     *
     * @return void
     */
    public function lostPassword()
    {
        $translator = Translator::getInstance();
        $password = $this->generatePassword();
        $passwordParts = explode('$', $this->password);
        $sql = '
            UPDATE users
            SET password = ' . $this->db->sqlval(
                $this->encryptPassword($password, $passwordParts[2])
            ) . '
            WHERE `userId` = ' . $this->db->sqlval($this->userId) . '
        ';
        $this->db->execute($sql);

        Helper\Mail::send(
            array($this->email, $this->name),
            $translator->gt('lostPasswordSubject'),
            str_replace(
                array('##USER##', '##PASSWORD##'),
                array($this->name, $password),
                $translator->gt('lostPasswordMessage')
            )
        );
    }

    /**
     * Generate a password containing of the following characters:
     * - 0-9
     * - a-f
     * - A-f
     * - !$%&
     *
     * @param int $length Length of the password, defaults to 8
     *
     * @return string
     */
    protected function generatePassword(int $length = 8): string
    {
        $characters = '0123456789!$%&abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLengh = strlen($characters);
        $password = '';

        for ($i = 0; $i < $length; $i++)
        {
            $password .= $characters[rand(0, $charactersLengh - 1)];
        }

        return $password;
    }
}
