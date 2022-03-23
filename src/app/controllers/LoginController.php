<?php

use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        //return '<h1> llo!!!</h1>';
    }
    public function loginAction()
    {
        // echo "hello";
        // $user = new Users();
        // $val =$_POST;
        $email = $_POST['email'];
        $password = $_POST['password'];
        // $res = $user::find();
        // print_r($res[0]->email);
        // print_r($val);
        $user = Users::find (
            [
                'conditions' => 'password = :password: and email = :email:' ,
                'bind'       => [
                    'password' => $password,
                    'email' => $email,
                ]
            ]
        );
        // echo "<pre>";
        // print_r($user[0]);

        if (count($user)>0)
        {
            session_start();
            $this->view->user = $user;
            $_SESSION['user']= array('id'=>$user[0]->id, 'username' => $user[0]->username, 'role'=> $user[0]->role);
            $this->response->redirect('blog/home');
            
            
        }
        else
        {
            echo "<h2>Incorrect email or password</h2>";
        }
       
    }
}