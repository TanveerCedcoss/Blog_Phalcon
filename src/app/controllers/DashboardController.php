<?php

use Phalcon\Mvc\Controller;


class DashboardController extends Controller
{
    public function indexAction($params='')
    {
        echo $params;
        if ($params == 'users') {
            $user = new Users();
            $id = $_POST['id'];
            $this->view->users = Users::find();
            if ($_POST) {
                $user = Users::findFirst($id);
                $user->delete();
                $this->response->redirect('dashboard/index/users');
            }
        } elseif ($params == 'blogs') {
            $blog = new Blogs();
            $id = $_POST['id'];
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

