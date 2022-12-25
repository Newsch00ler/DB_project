<?php

class LoginController extends TwigbaseController{//проверка пароля
    public $template = "login.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        return $context;
    }

    public function post(array $context) {
        $driver_license = $_POST['login'];
        $password = $_POST['password'];
        
        if($password != ''){
             if($driver_license == 'admin'){
                if($password == 'admin'){
                    $_SESSION["is_logged"] = true;
                    $_SESSION["role"] = 'admin';
                    header("Location: /");
                    exit;
                }
                else{
                    header("Location: /login");
                    exit;
                }
            }else{
            $sql = <<<EOL
    SELECT full_name FROM DRIVERS
        WHERE driver_license = :driver_license
    EOL;
                $query =$this->pdo->prepare($sql);
                $query->bindValue("driver_license", $driver_license);
                $query->execute();
                $data = $query->fetch(); 
                $valid_password = $data['full_name']; 
                if($valid_password == $password){
                    $_SESSION["is_logged"] = true;
                    $_SESSION["role"] = 'driver';
                    $_SESSION["driver_license"] = $driver_license;
                    header("Location: /");
                    exit;
                }
                else{
                    header("Location: /login");
                    exit;
                }
            }
        }
        else{
            header("Location: /login");
            exit;
        }
    }    

}