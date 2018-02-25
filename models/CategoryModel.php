<?php

class CategoryModel extends Model
{
    /**
     * List with all of categories
     */
    public function listOfCategories ()
    {
        $query = $this->pdo->prepare("SELECT b.id, b.name, count(a.id) as rows, SUM(if(a.status = 'На модерации', 1, 0)) AS pending, SUM(if(a.status = 'Не опубликован', 1, 0)) AS nonpublish,  SUM(if(a.status = 'Опубликован', 1, 0)) AS approved FROM questions a RIGHT JOIN categories b ON b.id = a.category_id GROUP BY b.id ASC");
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * creating of new category
     *
     * @param string $name name of the new category
     */
    public function createCategory ($name)
    {
        if (empty($name)) {
            return [0,0];
        } else {
            $action = $this->pdo->prepare("SELECT name FROM categories WHERE name = ?");
            $action->bindValue(1, $name, PDO::PARAM_STR);
            $action->execute();
            $row = $action->fetchAll();
            if ($row == true) {
                return [0,1];
            } else {
                $add = $this->pdo->prepare('INSERT INTO categories (name) VALUES (?)');
                $add->bindParam(1, $name, PDO::PARAM_STR);
                $add->execute();
                return [1,0];
            }
        }
    }

    /**
     * Deleting of category
     *
     * @param int $id id of the category
     */
    public function deleteCategory($id)
    {
        $action = $this->pdo->prepare('DELETE categories, questions FROM categories LEFT JOIN questions ON questions.category_id=categories.id WHERE categories.id = ?');
        $action->bindValue (1,$id, PDO::PARAM_INT);
        $action->execute(array($id));
    }
}
