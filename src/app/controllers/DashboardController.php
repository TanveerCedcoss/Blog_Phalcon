<?php

use Phalcon\Mvc\Controller;


class DashboardController extends Controller
{
    public function indexAction($params='')
    {
        if ($this->session->user['role']=='user') {
            $this->response->redirect('blog/home');
        }
        echo $params;
        if ($params == 'users') {
            $user = new Users();
            $id = $this->request->getpost('id');
            print_r($id);
            $this->view->users = Users::find();
            if ($this->request->ispost()) {
                $user = Users::findFirst($id);
                $user->delete();
                $this->response->redirect('dashboard/index/users');
            }
        } elseif ($params == 'blogs') {
            $blog = new Blogs();
            $id = $this->request->getpost('id');
            print_r($id);
            $this->view->blogs = Blogs::find();
            if ($_POST) {
                $blog = Blogs::findFirst($id);
                $blog->delete();
                $this->response->redirect('dashboard/index/blogs');
            }

        } else {
            $this->view->usersCount = Users::find();
            $this->view->blogsCount = Blogs::find();
        }

    }
}

