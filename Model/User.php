<?php


namespace Icc\Model;


use JsonSerializable;

class User implements JsonSerializable
{
    private $id;
    private $login;
    private $password;
    private $name;

    /**
     * User constructor.
     * @param $id
     * @param $login
     * @param $password
     * @param $name
     */
    public function __construct($id, $login, $password, $name)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->name = $name;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login): void
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }


    public function jsonSerialize()
    {
        return array('id' => intval($this -> getId()), 'login' => $this -> getLogin(), 'password' => $this -> getPassword(), 'name' => $this -> getName(), JSON_FORCE_OBJECT);
    }
}