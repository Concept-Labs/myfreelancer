<?php
Class Controller_Index Extends Controller_Base 
{
    protected function _initTemplate($title)
    {
        $this->_baseTemplate->addCss('styles/main.css');
		//єто файл templates/index.phtml
        return parent::_initTemplate($title);

    }

    public function index() 
    {

      $template = $this->_initTemplate('MY FREELANCER');

      $template->setFile('templates/main.phtml');

      $db = $this->_registry->get('db');

      $data = $_POST;
      
      if (isset($data['ru'])) {
          $_SESSION['lang'] = $data['ru'];
          $redirect = $data['redirect'];
          header("Location: $redirect");
      }
      if (isset($data['en'])) {
          $_SESSION['lang'] = $data['en'];
          $redirect = $data['redirect'];
          header("Location: $redirect");
      }
      if (isset($data['ua'])) {
          $_SESSION['lang'] = $data['ua'];
          $redirect = $data['redirect'];
          header("Location: $redirect");
      }


      $errors = array();
      if (isset($data['send'])) {
        $name_client = trim($data['name']);
        $email_client = $data['email'];
        $coment_client = $data['coment'];
        $date = $data['date'];
        $time = $data['time'];
        $stan_massage = $data['stan_massage'];

        if (empty($name_client)) {
          $errors = 'Заповніть поле "'.$conctakt_name.'"!';
        }
        elseif (empty($email_client)) {
          $errors = 'Заповніть поле "'.$conctakt_email.'"!';
        }
        elseif (empty($coment_client)) {
          $errors = 'Заповніть поле "'.$conctakt_comment.'"!';
        }

        if (empty($errors)) {
          $query = "INSERT INTO `message_client`(`id`, `name_client`, `email_client`, `coment_client`, `date`, `time`, `stan_massage`) VALUES (null,'$name_client','$email_client','$coment_client','$date','$time','$stan_massage')";
          $result = mysqli_query($db, $query);

        }

    }

    $this->_renderLayout($template);
}
}