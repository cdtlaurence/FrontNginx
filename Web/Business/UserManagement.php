<?php

namespace FrontNginx\Web\Business;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Utilities\Security;
use FrontNginx\Web\Utilities\Email;
use FrontNginx\Web\Utilities\SessionManagement;
use FrontNginx\Web\Utilities\AddressApiManagement;
use FrontNginx\Web\Model\Result;
use FrontNginx\Web\Model\Enum\AccountStatus;
use FrontNginx\Web\Model\Enum\ErrorCode;
use FrontNginx\Web\DataAccess\ConnectionFactory;
use FrontNginx\Web\DataAccess\Database;

class UserManagement
{
    public function login($username, $password)
    {
        $user = $this->getUser($username);
        if(!$this->checkPassword($user, $password))
        {
            return new Result(false, ErrorCode::PasswordIncorrect);
        }
        if(($user->AccountStatusId == AccountStatus::Unconfirmed))
        {
            return new Result(false, ErrorCode::UserNotActivated);
        }
        $sessionManagement = new SessionManagement;
        $sessionManagement->set('user', $user);
        return new Result(true);
    }

    public function logout()
    {
        $sessionManagement = new SessionManagement;
        $sessionManagement->set('user', null);
        return new Result(true);
    }

    public function register($username, $password, $email)
    {
        $security = new Security;
        $hashedPassword = $security->phpHash($password);
        if ($this->getUser($username))
        {
            return new Result(false, ErrorCode::UsernameTaken);
        }
        $this->addUser($username, $hashedPassword, $email);
        $activationCode = $this->getActivationCode($username);
        $this->sendConfirmation($username, $email, $activationCode);
        return new Result(true);
    }

    public function activate($username, $activationCode)
    {
        if (!$this->getUser($username))
        {
            return new Result(false, ErrorCode::UsernameNotFound);
        }
        $correctActivationCode = $this->getActivationCode($username);
        if ($correctActivationCode === $activationCode)
        {
            $this->actvateUser($username);
            return new Result(true);
        }
        return new Result(false, ErrorCode::ActivationCodeIncorrect);
    }

    public function isLoggedIn()
    {
        $sessionManagement = new SessionManagement;
        if (is_null($sessionManagement->get('user')))
        {
            return false;
        }
        return true;
    }

    public function getUser($username)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('select * from Users where Username = ?');
        $statement->bindValue(1, $username, \PDO::PARAM_STR);
        $database = new Database;
        return $database->select($statement)[0];
    }

    public function loginWithoutValidation($username)
    {
        $user = $this->getUser($username);
        $sessionManagement = new SessionManagement;
        $sessionManagement->set('user', $user);
        return new Result(true);
    }

    public function updateCurrentUser($title, $firstname, $lastname, $postcode)
    {
        $sessionManagement = new SessionManagement;
        $user = $sessionManagement->get('user');
        if($user->AddressId)
        {
        $this->updateAddress($user->AddressId, $postcode);
        } else
        {
        $user->AddressId = $this->addNewAddress($postcode);
        }
        $this->updateUser($title, $firstname, $lastname, $user->AddressId, AccountStatus::ReadyToPost, $user->UserId);
        $sessionManagement->set('user', $this->getUser($user->Username));
    }

    public function getAddress($addressId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('select * from Addresses where AddressId = ?');
        $statement->bindValue(1, $addressId, \PDO::PARAM_INT);
        $database = new Database;
        return $database->select($statement)[0];
    }

    private function updateUser($title, $firstname, $lastname, $addressId, $accountStatus, $userId)
    {   
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('update Users Set Title=?, FirstName=?, LastName=?, AddressId=?, AccountStatusId=? where UserId = ?');
        $statement->bindValue(1, $title, \PDO::PARAM_STR);
        $statement->bindValue(2, $firstname, \PDO::PARAM_STR);
        $statement->bindValue(3, $lastname, \PDO::PARAM_STR);
        $statement->bindValue(4, $addressId, \PDO::PARAM_INT);
        $statement->bindValue(5, $accountStatus, \PDO::PARAM_INT);
        $statement->bindValue(6, $userId, \PDO::PARAM_INT);
        $database = new Database;
        $database->update($statement);
    }

    private function updateAddress($addressId, $postcode)
    {   
        $addressApiManagement = new AddressApiManagement;
        $result = $addressApiManagement->getAddress($postcode)['result'];
        $longitude = ($result['longitude']);
        $latitude = ($result['latitude']);
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('update Addresses Set PostCode=?, Longitude=?, Latitude=? where addressId = ?');
        $statement->bindValue(1, $postcode, \PDO::PARAM_STR);
        $statement->bindValue(2, $longitude, \PDO::PARAM_STR);
        $statement->bindValue(3, $latitude, \PDO::PARAM_STR);
        $statement->bindValue(4, $addressId, \PDO::PARAM_INT);
        $database = new Database;
        $database->update($statement);
    }

    private function addNewAddress($postcode)
    {
        $addressApiManagement = new AddressApiManagement;
        $result = $addressApiManagement->getAddress($postcode)['result'];
        $longitude = ($result['longitude']);
        $latitude = ($result['latitude']);
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('insert into Addresses (Postcode, Longitude, Latitude) values (?, ?, ?)');
        $statement->bindValue(1, $postcode, \PDO::PARAM_STR);
        $statement->bindValue(2, $longitude, \PDO::PARAM_STR);
        $statement->bindValue(3, $latitude, \PDO::PARAM_STR);
        $database = new Database;
        $database->insert($statement);
        return $connection->lastInsertId();
    }
        
    private function addUser($username, $hashedPassword, $email)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('insert into Users (Username, Password, Email, AccountStatusId) values (?, ?, ?, ?)');
        $statement->bindValue(1, $username, \PDO::PARAM_STR);
        $statement->bindValue(2, $hashedPassword, \PDO::PARAM_STR);
        $statement->bindValue(3, $email, \PDO::PARAM_STR);
        $statement->bindValue(4, 1, \PDO::PARAM_INT);
        $database = new Database;
        $database->insert($statement);
    }

    private function actvateUser($username)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('UPDATE Users SET AccountStatusId=? WHERE Username = ?');
        $statement->bindValue(1, 2, \PDO::PARAM_INT);
        $statement->bindValue(2, $username, \PDO::PARAM_STR);
        $database = new Database;
        $database->update($statement);
    }

    private function checkPassword($user, $password)
    {
        $security = new Security;
        return $security->phpVerify($password, $user->Password);
    }

    private function getActivationCode($username)
    {
        $security = new Security;
        $hashedDecimalUsername = hexdec($security->md5Hash($username));
        return substr($hashedDecimalUsername, 2, 5);
    }

    private function sendConfirmation($username, $email, $activationCode)
    {
        $subject = 'Freecycle Registration Confirmation';
        $message = "Hello $username \r\n"
                    . "Thank you for signing up to Freecycle. "
                    . "please click the link below to complete you're registration. \r\n"
                    . "http://stuweb.cms.gre.ac.uk/~lj231/FrontNginx/Web/Controller/Activation.php?activationCode=$activationCode&username=$username \r\n"
                    . "Here is your activation code: $activationCode";
        $email = new Email($email, $subject, $message);
        $email->send();
    }
}

?>