<?php

class UserModel extends Model
{
    /**
     * Authorization
     *
     * @param string $user username
     *
     * @param string $password password
     *
     * @return Return message in error case and transport to index.php with $_SESSION data in succes case
     */

    public function login ($user, $password)
    {
        if (empty($user)) {
            return [0,0];
        } else if (empty($password)) {
            return [0,1];
        } else {
            $action = $this->pdo->prepare("SELECT id, login, password FROM users WHERE login = ?");
            $action->bindValue(1, $user, PDO::PARAM_STR);
            $action->execute();
            $row = $action->fetchAll();
            if (isset ($row[0]) && $row[0]['login'] == $user && $row[0]['password'] == md5($password)) {
                $_SESSION ['user'] = $row[0]['login'];
                $_SESSION ['userId'] = $row[0]['id'];
                header ('Location: /');
            } else {
                return [0,2];
            }
        }
    }

    /**
     * Log out
     *
     * @return Destroy session and transport to login.php
     */

    //Выход/смена пользователя
    public function logout ()
    {
        unset ($_SESSION['user']);
        unset ($_SESSION['userId']);
    }

    /**
     * List of administratos
     *
     * @return array $listOfUsers list with users
     */

    //Список администраторов
    public function allMembers ()
    {
        $stmt = $this->pdo->query("SELECT id, login FROM users");
        $listOfUsers = array ();
        $n = 0;
        while ($row = $stmt->fetch()) {
            $listOfUsers[$n]['login'] = $row['login'];
            $listOfUsers[$n]['id'] = $row['id'];
            $n++;
        }
        return $listOfUsers;
    }


    /**
     * Creating of administrator
     *
     * @param string $user user
     *
     * @param password $password password
     *
     * @return Return message in error case and create new user in DB in succes case
     */

    //Создание администратора
    public function register ($user, $password)
    {
        if (empty($user)) {
            return [0,3]; // 'Введите логин';
        } else if (empty($password)) {
            return [0,4]; // 'Введите пароль';
        } else {
            $password = md5($password);
            $action = $this->pdo->prepare("SELECT login FROM users WHERE login = ?");
            $action->bindValue(1, $user, PDO::PARAM_STR);
            $action->execute();
            $row = $action->fetchAll();
            if ($row == true) {
                return [0,5]; // 'Логин занят';
            } else {
                $add = $this->pdo->prepare('INSERT INTO users (login, password) VALUES (?, ?)');
                $add->bindParam(1, $user, PDO::PARAM_STR);
                $add->bindParam(2, $password, PDO::PARAM_STR);
                $add->execute();
                return [1,0]; // 'Новый пользователь создан.';
            }
        }
    }

    /**
     * Translate id of choosed admin to his id
     *
     * @param int $id id of user
     *
     * @return Return user name by his id
     */

    //Выбор администратора для изменения пароля
    public function change($id)
    {
        if (!empty($id)) {
            $action = $this->pdo->prepare('SELECT id, login FROM users WHERE id = ?');
            $action->bindValue (1, $id, PDO::PARAM_INT);
            $action->execute();
            while ($row = $action->fetch()) {
                return $row['login'];
            }
        }
    }

    /**
     * Change password of admin
     *
     * @param string $password password
     *
     * @param int $idfoChanging id of admin choosed for changing
     *
     * @return Return new password in DB and success message
     */

    //Изменение пароля администратора
    public function changeAdministratorPassword ($password, $idForChanging)
    {
        if (empty($password)) {
            return [0,6]; // 'Введите пароль'
        } else {
            $password = md5($password);
            $action = $this->pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            $action->bindParam(1, $password, PDO::PARAM_STR);
            $action->bindParam(2, $idForChanging, PDO::PARAM_STR);
            $action->execute();
            return [1,1]; // 'Пароль изменён.'
        }
    }

    /**
     * Deleting of user
     *
     * @param object $pdo PDO connect
     *
     * @param int $id id
     */

    //Удаление администратора
    public function delete($id)
    {
        $action = $this->pdo->prepare('DELETE from users WHERE id = ?');
        $action->bindValue (1,$id, PDO::PARAM_INT);
        $action->execute(array($id));
    }
}
