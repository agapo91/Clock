<?php

namespace App\Controllers;

use App\Models\User;
use Sirius\Validation\Validator;

class AuthController extends BaseController {

    public function getLogin() {
        return $this->render('account/login.twig');
    }
    public function getLogout() {
        unset($_SESSION['userId']);
        header('Location: ' . BASE_URL . 'auth/login');
    }
    public function getSignup() {
        return $this->render('account/signup.twig');
    }
    public function postLogin() {
        $validator = new Validator();
        $validator->add('email', 'required | Email');
        $validator->add('password', 'required');

        if ($validator->validate($_POST)) {
            $user = User::where('email', $_POST['email'])->first();
            if ($user) {
                if (password_verify($_POST['password'], $user->password)) {
                    $_SESSION['userId'] = $user->id;
                    header('Location: ' . BASE_URL . '?page=1');
                    return null;
                }
            }
            $validator->addMessage('Login', 'Email and/or Password are incorrect');
        }

        $errors = $validator->getMessages();
        return $this->render('account/login.twig', [
            'errors' => $errors
        ]);
    }
    public  function postSignup() {
        $errors = [];
        $result = false;
        $validator = new Validator();
        $validator->add('name', 'required');
        $validator->add('username', 'required');
        $validator->add('email', 'required | Email');
        $validator->add('password', 'required');
        $validator->add('password:Password', 'minlength', 'min=6', '{label} must have more than {min} characters');
        if ($validator->validate($_POST)) {
            $user = new User();
            $user->name = $_POST['name'];
            $user->username = $_POST['username'];
            $user->email = $_POST['email'];
            $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $user->img_ur = 'https://static.ivanti.com/sites/marketing/media/images/solutions/win-ten/win-ten_block2-icon3.svg?ext=.svg';
            $user->save();
            $result = true;
        } else {
            $errors =  $validator->getMessages();
        }
        return $this->render('account/signup.twig', [
            'result' => $result,
            'errors' => $errors
        ]);
    }
}