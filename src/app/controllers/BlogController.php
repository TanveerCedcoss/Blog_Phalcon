<?php

use Phalcon\Mvc\Controller;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;


class BlogController extends Controller
{
    public function indexAction()
    {
        if ($this->cookies->has('remember')) {
            $this->response->redirect('login');
        }
        else {
            $this->response->redirect('blog/home');
        }
    }
    public function homeAction()
    {
        $this->view->blogs = Blogs::find();
    }
    public function writeAction()
    {
        $adapter = new Stream('../app/logs/blog.log');
        $logger  = new Logger(
            'messages',
            [
             'main' => $adapter,
            ]
        );
        $this->view->title = "Write Blog";

        if (!$this->session->has('userDetail')) {
            $this->response->redirect('login');
        }
        if ($this->request->ispost()) {
            $username = $this->session->userDetail->username;
            $userId =  $this->session->userDetail->id;
      
            $blog = new Blogs();
            $sanitize = new \App\Components\MyEscaper();
            $blogInputData = array(
                'heading' => $sanitize->sanitize($this->request->getPost('heading')),
                'category' => $sanitize->sanitize($this->request->getPost('category')),
                'tags' => $sanitize->sanitize($this->request->getPost('tags')),
                'brief' => $sanitize->sanitize($this->request->getPost('brief')),
                'content' => $sanitize->sanitize($this->request->getPost('content'))
            );

            $blog->assign(
                $blogInputData,
                [
                    'heading',
                    'category',
                    'tags',
                    'brief',
                    'content',
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
                $logger->error(implode(' & ', $blog->getMessages()));
            }
        }
       
    }

    public function readAction($params)
    {
        echo $params;
        $this->view->blog = Blogs::find($params);
    }
}
