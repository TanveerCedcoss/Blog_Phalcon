<?php

use Phalcon\Mvc\Controller;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;

class SignupController extends Controller{

    public function IndexAction(){

    }

    public function registerAction() 
    {
        $adapter = new Stream('../app/logs/signup.log');
        $logger  = new Logger(
            'messages',
            [
             'main' => $adapter,
            ]
        );
        $user = new Users();
        $sanitize = new \App\Components\MyEscaper();
        $userInputData = array(
            'username' => $sanitize->sanitize($this->request->getPost('username')),
            'role' => $sanitize->sanitize($this->request->getPost('role')),
            'password' => $sanitize->sanitize($this->request->getPost('password')),
            'email' => $sanitize->sanitize($this->request->getPost('email')),
        );
        $user->assign(
            $userInputData,
            [
                'username',
                'role',
                'password',
                'email'
            ]
        );

        $success = $user->save();

        $this->view->success = $success;

        if($success){
            $this->view->message = "Register succesfully";
        }else{
            $this->view->message = "Not Register succesfully due to following reason: <br>".implode("<br>", $user->getMessages());
            $logger->error(implode(' & ', $user->getMessages()));
        }
    }
}