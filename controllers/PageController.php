<?php

class PageController extends Controller
{
    public function askAction()
    {
        $listOfCategories = new CategoryModel();
        $listOfCategories = $listOfCategories->listOfCategories();
        $this->view->generate('ask.tmpl', array(
            'listOfCategories' => isset($listOfCategories) ? $listOfCategories : null,
            'pageTitle' => 'Задать вопрос',
            'message' => isset($message) ? $message : null
        ));
    }
}
