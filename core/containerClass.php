<?php
class Container
{
    private $requests;
    private $messages;
    private $userInfo; 
    private $comments;

    function __construct()
    {
        $this->requests=NULL;
        $this->messages=NULL;
        $this->userInfo=NULL;
        $this->comments=NULL;
    }

    public function setRequests($requests)
    {
        $this->requests=$requests;
    }

    public function setMessages($messages)
    {
        $this->messages=$messages;
    }

    public function setUserInfo($userInfo)
    {
        $this->userInfo=$userInfo;
    }

    public function setComments($comments)
    {
        $this->comments=$comments;
    }

    public function getRequests()
    {
        return $this->requests;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getUserInfo()
    {
        return $this->userInfo;
    }

    public function getComments()
    {
        return $this->comments;
    }
}
?>