<?php

class AddCarDriverController extends BaseTableTwigController{
    public $template = "add_car_driver.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        if($_GET['type'] == 'cars'){
            $context['header1'] = 'Автомобиль';
            $context['header2'] = 'водителя';
            $context['header3'] = 'Удостоверение';

        }else{
            $context['header1'] = 'Водитель';
            $context['header2'] = 'автомобиль';
            $context['header3'] = 'VIN автомобиля';
        } 
        return $context;
    }

    public function post(array $context){
        $id = isset($_GET["id"]) ? $_GET["id"] : '';
        $number = isset($_POST["number"]) ? $_POST["number"] : '';

        if($_GET['type'] == 'cars'){
            $sql = <<<EOL
EXEC CreateControl :number, :id
EOL;
            $query = $this->pdo->prepare($sql);
            $query->bindValue("id", $id);      
            $query->bindValue("number", $number); 
        }else{
            $sql = <<<EOL
EXEC CreateControl :id, :number
EOL;
            $query = $this->pdo->prepare($sql);
            $query->bindValue("id", $id);      
            $query->bindValue("number", $number); 
        }
                  
        $query->execute();
        $url = $_SESSION['url'];
        header("Location: $url");
        $this->get($context);
    }
        
        
}    


