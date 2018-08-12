<?php
Class Controller_Admin Extends Controller_Base 
{
  protected function _initTemplate($title)
  {

    return parent::_initTemplate($title);

  }

  public function index() 
  {

    $template = $this->_initTemplate('Панель управління');

    $template->setFile('templates/admin.phtml');

    $db = $this->_registry->get('db');

    $errorsl = array();

      if (isset($_POST['log'])) {      
        $login = htmlspecialchars(stripslashes(trim($_POST['login'])));
        $password = htmlspecialchars(stripslashes(md5($_POST['password'])));

        $result = mysqli_query($db, "SELECT * FROM users_admin WHERE login='$login'");
        $row_users = mysqli_fetch_array($result);

        if (empty(trim($_POST['login']))) {
            $errorsl = 'Введіть login!';
        }
        elseif (empty($row_users['id'])) {
            $errorsl = 'Невірний login!';
        } 
        elseif (empty($_POST['password'])) {
            $errorsl = 'Введіть пароль!';
        } 
        elseif (empty($row_users['password'] == $password)) {
            $errorsl = 'Невірний пароль!';
        }

        if (empty($errorsl)) {

            $_SESSION['login_admin'] = $row_users['login'];
        }

    }
    $template->set('errorsl', $errorsl);

    $query = "SELECT * FROM `message_client` ORDER BY id DESC";
    $result = mysqli_query($db, $query);
    $num = mysqli_num_rows($result);

    $template->set('result', $result);
    $template->set('num', $num);

      //код для виведення матеріалу а одну сторінку
    $id = isset($_GET['id']) ? $_GET['id'] : 0; 

    if (isset($_GET['message'])) {
      $res = mysqli_query($db, "   SELECT * FROM message_client WHERE id='$id'");
      $roww = mysqli_fetch_array($res);
      $template->set('roww', $roww);

      if ($_GET['id']) {
        $query_update = "UPDATE `message_client` SET `stan_massage` = 'Прочитано' WHERE id='$id'";
        $result_update = mysqli_query($db, $query_update);
      }
    }


    mysqli_close($db);

    $this->_renderLayout($template);
  }

  public function users() 
  {

    $template = $this->_initTemplate('Користувачі панелі управління');

    $template->setFile('templates/admin/users.phtml');

    $db = $this->_registry->get('db');

      $query= mysqli_query($db, "SELECT * FROM `users_admin`");
      $num = mysqli_num_rows($query);
      $row_users = mysqli_fetch_array($query);

      $template->set('query', $query);
      $template->set('num', $num);
      $template->set('row_users', $row_users);

      $id = isset($_GET['id']) ? $_GET['id'] : 0; 

      $res = mysqli_query($db, "   SELECT * FROM users_admin WHERE id='$id'");

      $roww = mysqli_fetch_array($res);
      $template->set('roww', $roww);
//редагування
      if (isset($_GET['edit_site'])) {
        $errore = array();

        if (isset($_POST['save'])) {
            $password = md5($_POST['password']);
            $password_confirm = $_POST['password-confirm'];

            if (empty($_POST['password'])) {
                $errore = 'Введіть, будь ласка, новий пароль!';
            } elseif ($_POST['password'] != $password_confirm) {
                $errore = 'Новий пароль і підтвердження пароля не співпадають!';
            }
            
            if (empty($errore)) {
                mysqli_query($db, "UPDATE `users_admin` SET `password`='$password' WHERE id='$id'");

                header("Location:" .base_url .'admin/users');
                exit();
            }   
        }
        $template->set('errore', $errore);
    }

//видалення 
    if (isset($_GET['delete_site'])) {
        if (isset($_POST['delete_user'])) {
            if ($_POST['delete_user'] == 1) {
                $delete = mysqli_query($db, "DELETE FROM `users_admin` WHERE id='$id'");

                header("Location:" .base_url .'admin/users');
            } else {
                header("Location:" .base_url .'admin/users');
                exit();
            }
        }
        $template->set('delete', $delete);
    }

    mysqli_close($db);

    $this->_renderLayout($template);
  }

  public function add_users() 
  {

    $template = $this->_initTemplate('Додавання користувачів панелі управління');

    $template->setFile('templates/admin/add_users.phtml');

    $db = $this->_registry->get('db');
    $errore = array();


    if (isset($_POST['add'])) {
        $login = $_POST['login'];
        $password = md5($_POST['password']);
        $password_confirm = $_POST['password-confirm'];
        $date = $_POST['date'];

        if (empty($login)) {
            $errore = 'Введіть, будь ласка, <u>логін</u> користувача!';
        } elseif (empty($_POST['password'])) {
            $errore = 'Введіть, будь ласка, <u>пароль</u> користувача!';
        } elseif ($_POST['password'] != $password_confirm) {
            $errore = 'Пароль і пітвердження пароля не співпадають!';
        }

        if (empty($errore)) {
            mysqli_query($db, "INSERT INTO `users_admin`(`id`, `login`, `password`, `date`) VALUES (null, '$login', '$password', '$date')");

            header("Location:" .base_url .'admin/users');
            exit();
        }
    }
    $template->set('errore', $errore);
    mysqli_close($db);

    $this->_renderLayout($template);
  }
}