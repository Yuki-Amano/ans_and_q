<?php

class AnswerController extends Controller
{
    public function indexAction()
    {
        $answer = new AnswerModel();
        // Создание ответа и список ответов
        $codeForMessage = $answer->createAnswer($_POST['answer-description'], $_POST['answer-question-id']);
		if ($codeForMessage[0] == 0) {
			if ($codeForMessage[1] == 0) {
				$message = 'Введите текст ответа.';
			}
		} else if ($codeForMessage[0] == 1) {
			if ($codeForMessage[1] == 0) {
				$message = 'Вы ответили на вопрос.';
			}
		}
        // Ответ на вопрос
        $answerForQuestion = $answer->answerForQuestion($_POST['answer-question-id']);
        $category = new CategoryController();
        $listOfCategories = $category->listOfCategoryAction();
        $questionCard = new QuestionModel();
        $questionCard = $questionCard->questionCard ($_POST['answer-question-id']);
        $this->view->generate('question-edit.tmpl', array(
            'rows' => $questionCard,
            'listOfCategories' => isset($listOfCategories) ? $listOfCategories : null,
            'stasuses' => ['На модерации', 'Опубликован', 'Не опубликован'],
            'message' => isset($message) ? $message : null,
            'answerForQuestion' => isset($answerForQuestion) ? $answerForQuestion : null,
        ));
    }
}
