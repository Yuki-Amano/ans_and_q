<?php

class AnswerModel extends Model
{
    /**
     * Creating of answer
     *
     * @param string $text text of the answer
     *
     * @param int $id id of question
     */

    public function createAnswer ($text, $id) {
        if (empty($text)) {
            return [0,0];
        } else {
            $add = $this->pdo->prepare('INSERT INTO answers (question_id, description) VALUES (:id, :desc) ON DUPLICATE KEY UPDATE question_id = :id2, description = :desc2');
            $add->bindParam(':desc', $text, PDO::PARAM_STR);
            $add->bindParam(':id', $id, PDO::PARAM_INT);
            $add->bindParam(':desc2', $text, PDO::PARAM_STR);
            $add->bindParam(':id2', $id, PDO::PARAM_INT);
            $add->execute();
            return [1,0];
        }
    }

    /**
     * Getting answer for question
     *
     * @param int $questionId id of question
     */

    public function answerForQuestion ($questionId)
    {
        $query = $this->pdo->prepare("SELECT question_id, description FROM answers WHERE question_id = ?");
        $query->bindParam(1, $questionId, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll();
    }
}
