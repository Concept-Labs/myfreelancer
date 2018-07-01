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



      if (isset($data['send'])) {
        $to = $data['email'];
        $subject = 'Здраствуйте '.$data['name'].'!';
        $message = $data['coment'];
        $headers = 'From:'.$to . "\r\n" .
        'Reply-To:'.$to  . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
echo $data['name'];
         if (!empty($_POST['email']) AND !empty($_POST['name']) AND !empty($_POST['coment'])) {
        if ($mail = mail($to, $subject, $message, $headers)) {
            echo "повідомлення відправлено".$mail;

        } else {
            echo "повідомлення не відправлено";
        }
    }else {
        echo "нема даних";
    }
    }

    $this->_renderLayout($template);
}
}