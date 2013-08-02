<?php

use \Phalcon\Tag as Tag;

class CountsController extends ControllerBase
    {

    function indexAction()
    {
        $this->session->conditions = null;
    }

    public function searchAction()
{

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = \Phalcon\Mvc\Model\Criteria::fromInput($this->di, "Counts", $_POST);
            $this->session->conditions = $query->getConditions();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
            if ($numberPage <= 0) {
                $numberPage = 1;
            }
        }

        $parameters = array();
        if ($this->session->conditions) {
            $parameters["conditions"] = $this->session->conditions;
        }
        $parameters["order"] = "id";

        $counts = Counts::find($parameters);
        if (count($counts) == 0) {
            $this->flash->notice("The search did not find any counts");
            return $this->dispatcher->forward(array("controller" => "counts", "action" => "index"));
        }

        $paginator = new \Phalcon\Paginator\Adapter\Model(array(
            "data" => $counts,
            "limit"=> 10,
            "page" => $numberPage
        ));
        $page = $paginator->getPaginate();

        $this->view->setVar("page", $page);
    }

    public function newAction()
    {

    }

    public function editAction($id)
    {

        $request = $this->request;
        if (!$request->isPost()) {

            $id = $this->filter->sanitize($id, array("int"));

            $counts = Counts::findFirst('id="'.$id.'"');
            if (!$counts) {
                $this->flash->error("counts was not found");
                return $this->dispatcher->forward(array("controller" => "counts", "action" => "index"));
            }
            $this->view->setVar("id", $counts->id);
        
            Tag::displayTo("id", $counts->id);
            Tag::displayTo("links_id", $counts->links_id);
            Tag::displayTo("value", $counts->value);
            Tag::displayTo("visit_date", $counts->visit_date);
            Tag::displayTo("visitor_ip", $counts->visitor_ip);
        }
    }

    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array("controller" => "counts", "action" => "index"));
        }

        $counts = new Counts();
        $counts->id = $this->request->getPost("id");
        $counts->links_id = $this->request->getPost("links_id");
        $counts->value = $this->request->getPost("value");
        $counts->visit_date = $this->request->getPost("visit_date");
        $counts->visitor_ip = $this->request->getPost("visitor_ip");
        if (!$counts->save()) {
            foreach ($counts->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array("controller" => "counts", "action" => "new"));
        } else {
            $this->flash->success("counts was created successfully");
            return $this->dispatcher->forward(array("controller" => "counts", "action" => "index"));
        }

    }

    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array("controller" => "counts", "action" => "index"));
        }

        $id = $this->request->getPost("id", "int");
        $counts = Counts::findFirst("id='$id'");
        if (!$counts) {
            $this->flash->error("counts does not exist ".$id);
            return $this->dispatcher->forward(array("controller" => "counts", "action" => "index"));
        }
        $counts->id = $this->request->getPost("id");
        $counts->links_id = $this->request->getPost("links_id");
        $counts->value = $this->request->getPost("value");
        $counts->visit_date = $this->request->getPost("visit_date");
        $counts->visitor_ip = $this->request->getPost("visitor_ip");
        if (!$counts->save()) {
            foreach ($counts->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array("controller" => "counts", "action" => "edit", "params" => array($counts->id)));
        } else {
            $this->flash->success("counts was updated successfully");
            return $this->dispatcher->forward(array("controller" => "counts", "action" => "index"));
        }

    }

    public function deleteAction($id){

        $id = $this->filter->sanitize($id, array("int"));

        $counts = Counts::findFirst('id="'.$id.'"');
        if (!$counts) {
            $this->flash->error("counts was not found");
            return $this->dispatcher->forward(array("controller" => "counts", "action" => "index"));
        }

        if (!$counts->delete()) {
            foreach ($counts->getMessages() as $message){
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array("controller" => "counts", "action" => "search"));
        } else {
            $this->flash->success("counts was deleted");
            return $this->dispatcher->forward(array("controller" => "counts", "action" => "index"));
        }
    }

}
