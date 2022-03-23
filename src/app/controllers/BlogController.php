<?php

use Phalcon\Mvc\Controller;


class BlogController extends Controller
{
    // public function __construct()
    // {
    //     $
    // }
    public function indexAction()
    {
        
    }
    public function homeAction()
    {
        $this->view->blogs = Blogs::find();
    }
    public function writeAction()
    {
        $this->view->title = "Write Blog";
        session_start();
        if (!isset($_SESSION['user'])) {
            $this->response->redirect('login');
        }
        if ($this->request->ispost()) {
            $username = $_SESSION['user']['username'];
            $userId = $_SESSION['user']['id'];
            $blog = new Blogs();
            $blog->assign(
                $this->request->getPost(),
                [
                    'heading',
                    'category',
                    'tags',
                    'brief',
                    'description'
                ]
            );
          
            $blog->username= $username;
            $blog->user_id= $userId;
            $success = $blog->save();
            
            $this->view->success = $success;
    
            if ($success) {
                $this->view->message = "Submitted succesfully";
            } else {
                $this->view->message = "Not submitted succesfully due to following reason: <br>".implode("<br>", $blog->getMessages());
            }
            // $this->response->redirect('blog/write');
        }
       
    }

    public function readAction($params)
    {
        echo $params;
        $this->view->blog = Blogs::find($params);
    }
}
