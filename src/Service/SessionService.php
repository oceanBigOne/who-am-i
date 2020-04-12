<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionService
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function set($key,$value)
    {
        $this->session->set($key, $value);
    }

    public function get($key){
       return $this->session->get($key,null);
    }
}