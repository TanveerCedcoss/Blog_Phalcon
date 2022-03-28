<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
    public $user_id;
    public $username;
    public $password;
    public $email;
    public $ref_key;

}