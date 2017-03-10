<?php
require_once 'lib.config.php';
/**
 * idorm/paris ORM Configuration
 */
ORM::configure("mysql:host={$dbHost};dbname={$dbName}");
ORM::configure('username', $dbUser);
ORM::configure('password', $dbPass);
ORM::configure('logging', $logging);


class QuestionSet extends Model
{
}

class Question extends Model
{
    public function questionSet()
    {
        return $this->has_one('QuestionSet');
    }
}

class Student extends Model
{
    public function questionSet()
    {
        return $this->has_one('QuestionSet');
    }
}

class Answer extends Model
{
    public function student()
    {
        return $this->has_one('Student');
    }

    public function question()
    {
        return $this->has_one('Question');
    }
}

class Admin extends Model
{
}