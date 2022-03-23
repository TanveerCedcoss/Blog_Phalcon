<?php

use Phalcon\Mvc\Model;

Class Blogs extends Model
{
    public $id;
    public $heading;
    public $username;
    public $category;
    public $description;
    public $tags;
    public $brief;
}