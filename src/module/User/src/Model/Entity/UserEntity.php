<?php

declare(strict_types=1);
namespace User\Model\Entity;

class UserEntity{
    protected $user_id;
    protected $username;
    protected $email;
    protected $password;
    protected $birthday;
    protected $gender;
    protected $photo;
    protected $role_id;
    protected $active;
    protected $created;
    protected $modified;
    #roles table column
    protected $role;

    public function getUserId()
    {
        return $this->user_id;
    }
    public function setUserId($user_id)
    {
        # code...
        $this->user_id=$user_id;
    }
    public function getUserName()
    {
        return $this->username;
    }
    public function setUserName($username)
    {
        # code...
        $this->username=$username;;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        # code...
        $this->email=$email;;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        # code...
        $this->password=$password;
    }
    public function getBirthday()
    {
        return $this->birthday;
    }
    public function setBirthday($birthday)
    {
        # code...
        $this->birthday=$birthday;
    }

    public function getGender()
    {
        return $this->gender;
    }
    public function setGender($gender)
    {
        # code...

        $this->gender=$gender;
    }
    public function getPhoto()
    {
        return $this->photo;
    }
    public function setPhoto($photo)
    {
        # code...
        $this->photo=$photo;
    }
    public function getRoleId()
    {
        return $this->role_id;
    }
    public function setRoleId($role_id)
    {
        # code...
        $this->role_id=$role_id;
    }
    public function getActive()
    {
        return $this->active;
    }
    public function setActive($active)
    {
        # code...
        $this->active=$active;
    }
    public function getCreated()
    {
        return $this->created;
    }
    public function setCreated($created)
    {
        # code...
        $this->created=$created;
    }
    public function getModified()
    {
        return $this->modified;
    }
    public function setModified($modified)
    {
        # code...
        $this->modified=$modified;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function setRole($role)
    {
        # code...
        $this->role=$role;
    }

}
?>