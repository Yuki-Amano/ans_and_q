<?php

class CategoryController extends Controller
{
    protected $category;

    public function __construct()
    {
        parent::__construct();
        $this->category = new CategoryModel();
    }

    public function createCategoryAction()
    {
        if (isset($_GET['new-category-create'])) {
            if (isset ($_POST['new-category-name']) && !empty ($_POST['new-category-name'])) {
                $codeForMessage = $this->category->createCategory($_POST['new-category-name']);
				if ($codeForMessage[0] == 0) {
					if ($codeForMessage[1] == 0) {
						$message = 'Введите название.';
					} else if ($codeForMessage[1] == 1) {
						$message = 'Категория с таким названием уже существует.';
					}
				} else if ($codeForMessage[0] == 1) {
					if ($codeForMessage[1] == 0) {
						$message = 'Вы создали новую категорию.';
					}
				}
                header('Location: /user/getUsers?message='. $message);
            }
        }
    }

    public function deleteCategoryAction()
    {
        if (isset($_GET['actionCategory'])) {
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            if ($_GET['actionCategory'] == 'delete') {
                $this->category->deleteCategory($id);
                header('Location: /user/getUsers');
            }
        }
    }

    public function listOfCategoryAction()
    {
        return $this->category->listOfCategories ();
    }
}
