<?php

class QuestionController extends Controller
{
    protected $question;

    public function __construct()
    {
        parent::__construct();
        $this->question = new QuestionModel();
    }

    public function indexAction()
    {
        $listOfCategories = new CategoryModel();
        $listOfCategories = $listOfCategories->listOfCategories();

        $listOfQuestions = new QuestionModel();
        if (isset($_POST['categoryFilter'])) {
            $listOfQuestions = $listOfQuestions->listOfQuestions($_POST['categoryFilter']);
        } else {
            $listOfQuestions = $listOfQuestions->listOfQuestions();
        }
        $this->view->generate('index.tmpl', array(
            'rows' => $listOfQuestions,
            'listOfCategories' => isset($listOfCategories) ? $listOfCategories : null,
        ));
    }

    //список вопросов
    public function listOfQuestionAction()
    {
        if (isset($_POST['categoryFilter'])) {
            $filter = 'WHERE b.id =' . $_POST['categoryFilter'];
            return $this->question->listOfQuestions ($filter );
        } else {
            return $this->question->listOfQuestions ();
        }
    }

    //Задать вопрос
    public function askAction()
    {
        $codeForMessage = $this->question->addQuestion($_POST);
		if ($codeForMessage[0] == 0) {
			if ($codeForMessage[1] == 0) {
				$message = 'Все поля обязательны для заполнения.';
			}
		} else if ($codeForMessage[0] == 1) {
			if ($codeForMessage[1] = 0) {
				$message = 'Вы задали вопрос. Модератор опубликует его после проверки.';
			}
		}
        $this->view->generate('ask.tmpl', array(
			'listOfCategories' => isset($listOfCategories) ? $listOfCategories : null,
            'message' => isset($message) ? $message : null,
        ));
        /* $this->indexAction();*/
    }

    public function dellQuestionAction()
    {
        if (isset ($_GET['questionId'])) {
            $this->question->deleteQuestion($_GET['questionId']);
        }
        header('Location: /user/getUsers');
    }

    public function editQuestionAction()
    {
        $this->question->editQuestion ($_POST);
        $this->cardQuestionAction();
    }

    public function cardQuestionAction()
    {
        //Карточка вопроса
        $category = new CategoryController();
        $listOfCategories = $category->listOfCategoryAction();
        $questionCard = $this->question->questionCard ($_GET['questionId']);
        $this->view->generate('question-edit.tmpl', array(
            'rows' => $questionCard,
            'listOfCategories' => isset($listOfCategories) ? $listOfCategories : null,
            'stasuses' => array ('На модерации', 'Опубликован', 'Не опубликован'),
            'message' => isset($message) ? $message : null,
            'answerForQuestion' => isset($answerForQuestion) ? $answerForQuestion : null,
        ));
    }
}
