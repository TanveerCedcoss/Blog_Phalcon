<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Phalcon\Security\Random;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;


class LoginController extends Controller
{
    public function indexAction()
    {
        if ($this->cookies->has('remember')) {
            $key =  $this->cookies->get('key');
            $check = Users::findFirst("ref_key = '$key'");

            if ($check> 0) {
                $userDetail = [
                    "id" => $check-> id,
                    "username" => $check-> name,
                    "role" => $check-> email,
                ];
                $this->session->set('userDetail', (object)$userDetail);
            }
        }
        if ($this->session->has('userDetail')) {
            $this->response->redirect('blog/home');
        }
    }
    public function loginAction()
    {

        $adapter = new Stream('../app/logs/login.log');
        $logger  = new Logger(
            'messages',
            [
             'main' => $adapter,
            ]
        );
    
        $sanitize = new \App\Components\MyEscaper();
        $email = $sanitize->sanitize($this->request->getpost('email'));
        $password =  $sanitize->sanitize($this->request->getpost('password'));

        $user = Users::find (
            [
                'conditions' => 'password = :password: and email = :email:' ,
                'bind'       => [
                    'password' => $password,
                    'email' => $email,
                ]
            ]
        );

        if (count($user)>0)
        {
            $this->view->user = $user;

            $userDetail = array('id'=>$user[0]->user_id, 'username' => $user[0]->username, 'role'=> $user[0]->role);
   
            $this->session->set('userDetail', (object)$userDetail);
            if ($this->request->getpost('remember')) {

                $this->cookies->set(
                    'remember',
                    'Y',
                    time() + 15 * 86400
                );
                $this->cookies->send();
                $random = new Random();
                $key = $random->hex(10);
                // $email = $info['email'];
                $addKey = Users::findFirst("email = '$email'");
                $addKey->ref_key = $key;
                $addKey->update();

                $this->cookies->set(
                    'key',
                    $key,
                    time() + 15 * 86400
                );
                $this->cookies->send();
            }
            $this->response->redirect('blog/home');
            
        }
        else
        {
            $response = new Response();
            $logger->error('Authentication failed');
            $response->setStatusCode(404, 'Not Found');
            $response->setContent("Authentication failed");
            $response->send();
            die();
        }
       
    }
    public function logoutAction()
    {
        $this->session->remove('userDetail');
        $this->response->redirect('login');
        $rem = $this->cookies->get('remember');
        $rem->delete();
        $key =  $this->cookies->get('key');
        $key->delete();

    }
}