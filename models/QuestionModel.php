<?php

class QuestionModel extends Model
{
    /**
     * List of questions
     *
     * @param string $filter filter by
     *
     * @return object with list of questions
     */

    public function listOfQuestions ($filter = null)
    {
        if (isset($filter)) {
            $filter = 'WHERE b.id = ' . $filter;
        }
        $query = $this->pdo->prepare("SELECT a.date, a.id, a.username, a.useremail, a.title, a.description, a.status, b.name as category, c.description as answer FROM questions a LEFT JOIN categories b ON b.id = a.category_id LEFT JOIN answers c ON c.question_id = a.id $filter ORDER BY a.id ASC");
        $query->execute();
        return $query->fetchAll();

    }

    /**
     * Add question
     *
     * @param array $array POST array with params of new question
     *
     * @return succes message
     */

    public function addQuestion ($array)
    {
        foreach ($array as $param => $key) {
            if ($param != 'add-question' && empty ($key)) {
                return [0,0]; // 'Поле ' . $param . ' не заполнено.'
            }
        }
        $add = $this->pdo->prepare('INSERT INTO questions (username, useremail, category_id, title, description) VALUES (?, ?, ?, ?, ?)');
        $add->bindParam(1, $array['question-author-name'], PDO::PARAM_STR);
        $add->bindParam(2, $array['question-author-email'], PDO::PARAM_STR);
        $add->bindParam(3, $array['question-category'], PDO::PARAM_INT);
        $add->bindParam(4, $array['question-title'], PDO::PARAM_STR);
        $add->bindParam(5, $array['question-text'], PDO::PARAM_STR);
        $add->execute();
        header ('Location: /');
    }

    /**
     * Delete question
     *
     * @param int $id id of question for deleting
     */

    public function deleteQuestion($id)
    {
        $action = $this->pdo->prepare('DELETE from questions WHERE id = ?');
        $action->bindValue (1,$id, PDO::PARAM_INT);
        $action->execute(array($id));
    }

    /**
     * Changing of parameters of question
     *
     * @param array $array POST array with params of the question
     */

    public function editQuestion ($array) {
        $action = $this->pdo->prepare('UPDATE questions SET date = ?, username= ?, useremail = ?, title = ?, description = ?, category_id = ?, status = ? WHERE questions.id = ?');
        $action->bindValue (1,$array['question-edit-date'], PDO::PARAM_STR);
        $action->bindValue (2,$array['question-edit-username'], PDO::PARAM_STR);
        $action->bindValue (3,$array['question-edit-useremail'], PDO::PARAM_STR);
        $action->bindValue (4,$array['question-edit-title'], PDO::PARAM_STR);
        $action->bindValue (5,$array['question-edit-description'], PDO::PARAM_STR);
        $action->bindValue (6,$array['question-edit-category'], PDO::PARAM_INT);
        $action->bindValue (7,$array['question-edit-status'], PDO::PARAM_STR);
        $action->bindValue (8,$array['question-edit-id'], PDO::PARAM_INT);
        $action->execute();
    }

    /**
     * Page of question
     *
     * @param int $id id of question
     */

    public function questionCard ($id)
    {
        $query = $this->pdo->prepare("SELECT a.date, a.id, a.username, a.useremail, a.title, a.description, a.status, b.name as category, c.description as answer FROM questions a LEFT JOIN categories b ON a.category_id = b.id LEFT JOIN answers c ON a.id = c.question_id WHERE a.id = $id");
        $query->execute();
        return $query->fetchAll();
    }
}
