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
use \SmartWork\Utility\Database;

/**
 * User class for login, registration and check of privileges.
 *
 * @package SmartWork
 * @author  Marian Pollzien <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class User
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
     * @param integer $userId
     *
     * @return \self
     */
    public static function getUserById($userId)
    {
        $sql = '
            SELECT
                `userId`,
                `name`,
                password,
                email,
                active,
                `admin`
            FROM users
            WHERE userId = ' . Database::sqlval($userId) . '
                AND !deleted
        ';
        $userData = Database::query($sql);

        $object = new self();
        $object->userId        = intval($userData['userId']);
        $object->name          = $userData['name'];
        $object->password      = $userData['password'];
        $object->email         = $userData['email'];
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
    public static function getUser($name, $password)
    {
        $sql = '
            SELECT
                `userId`,
                `name`,
                password
            FROM users
            WHERE name = ' . Database::sqlval($name) . '
                AND !deleted
        ';
        $userData = Database::query($sql);
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
    public static function getUserByMail($mail)
    {
        $sql = '
            SELECT `userId`
            FROM users
            WHERE email = ' . Database::sqlval($mail) . '
                AND !deleted
        ';
        $userId = Database::query($sql);

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
     *
     * @return integer
     */
    public static function createUser($name, $password, $email)
    {
        $sql = '
            INSERT INTO users
            SET name = ' . Database::sqlval($name) . ',
                password = ' . Database::sqlval(self::encryptPassword($password, uniqid())) . ',
                email = ' . Database::sqlval($email) . '
        ';
        return Database::query($sql);
    }

    /**
     * Checks if the username is already in use or not.
     *
     * @param string $name
     *
     * @return boolean
     */
    public static function checkUsername($name)
    {
        $sql = '
            SELECT COUNT(*)
            FROM users
            WHERE name = ' . Database::sqlval($name) . '
        ';
        return !!Database::query($sql);
    }

    /**
     * Check if the email is already in use or not.
     *
     * @param string $email
     *
     * @return boolean
     */
    public static function checkEmail($email)
    {
        $sql = '
            SELECT COUNT(*)
            FROM users
            WHERE email = ' . Database::sqlval($email) . '
        ';
        return !!Database::query($sql);
    }

    /**
     * Encrypt the user password and a salt with md5.
     *
     * @param string $password
     * @param string $salt
     *
     * @return string
     */
    protected static function encryptPassword($password, $salt)
    {
        $saltedPassword = $salt ? $password . '-' . $salt : $password;
        return '$m5$' . $salt . '$' . md5($saltedPassword);
    }

    /**
     * Get the internal user id.
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get the users nickname.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the users email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Whether the user has admin privilege or not.
     *
     * @return boolean
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Whether the user is activated or not.
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->active;
    }

    /**
     * Get the language id of the user.
     *
     * @return integer
     */
    public function getLanguageId()
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
    public function setName($name)
    {
        $this->name = $name;
        $sql = '
            UPDATE users
            SET name = ' . Database::sqlval($this->name) . '
            WHERE userId = ' . Database::sqlval($this->userId) . '
        ';
        Database::query($sql);
    }

    /**
     * Encrypts and sets the password.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword($password)
    {
        $passwordParts = explode('$', $this->password);
        $this->password = self::encryptPassword($password, $passwordParts[2]);
        $sql = '
            UPDATE users
            SET password = ' . Database::sqlval($this->password) . '
            WHERE userId = ' . Database::sqlval($this->userId) . '
        ';
        Database::query($sql);
    }

    /**
     * Set the users email.
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $sql = '
            UPDATE users
            SET email = ' . Database::sqlval($this->email) . '
            WHERE userId = ' . Database::sqlval($this->userId) . '
        ';
        Database::query($sql);
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
            WHERE userId = ' . Database::sqlval($this->userId) . '
        ';
        Database::query($sql);
        $this->active = true;
    }

    /**
     * Set the users admin privilege.
     *
     * @param boolean $admin
     *
     * @return void
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
        $sql = '
            UPDATE users
            SET admin = ' . Database::sqlval($admin ? 1 : 0) . '
            WHERE userId = ' . Database::sqlval($this->userId) . '
        ';
        Database::query($sql);
    }

    /**
     * Set the users language.
     *
     * @param integer $languageId
     *
     * @return void
     */
    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;
        $sql = '
            UPDATE users
            SET `languageId` = ' . Database::sqlval($languageId) . '
            WHERE `userId` = ' . Database::sqlval($this->userId) . '
        ';
        Database::query($sql);
    }

    /**
     * Reset the users password and send him an email with the new password.
     *
     * @return void
     */
    public function lostPassword()
    {
        $translator = \Translator::getInstance();
        $password = $this->generatePassword();
        $passwordParts = explode('$', $this->password);
        $sql = '
            UPDATE users
            SET password = ' . Database::sqlval(
                $this->encryptPassword($password, $passwordParts[2])
            ) . '
            WHERE `userId` = ' . Database::sqlval($this->userId) . '
        ';
        Database::query($sql);

        \Helper\Mail::send(
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
     * @param integer $length Length of the password, defaults to 8
     *
     * @return string
     */
    protected function generatePassword($length = 8)
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
