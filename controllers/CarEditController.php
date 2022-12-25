<?php

class CarEditController extends BaseTableTwigController{
    public $template = "auto.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        $_SESSION['url'] = $_SERVER['REQUEST_URI'];
        $sql = <<<EOL
SELECT * FROM OFFENSES
EOL;
        $query = $this->pdo->query($sql);
        $context['articles'] = $query->fetchAll();  
        
        $body_number = isset($_GET['id']) ? $_GET['id'] : '';
        $context['id'] = $body_number;
        $sql = <<<EOL
SELECT * FROM CARS WHERE body_number = :body_number
EOL;
        $query =$this->pdo->prepare($sql);
        $query->bindValue("body_number", $body_number);
        $query->execute();
        $context['car'] = $query->fetch();

        $context['count_violations'] = 0;
        $sql = <<<EOL
SELECT [Offenses count] FROM [Count_offenses_auto] WHERE [Body number] = :body_number
EOL;
        $query = $this->pdo->prepare($sql); 
        $query->bindValue("body_number", $body_number);
        $query->execute();
        $context['count_violations'] = $query->fetch();

        $sql = <<<EOL
SELECT * FROM POSSESSION WHERE body_number = :body_number
EOL;            
        $query = $this->pdo->prepare($sql); 
        $query->bindValue("body_number", $body_number);        
        $query->execute(); 

        $context['objects'] = $query->fetchAll();   
        $context['column_name'] = ['Номер кузова', 'Дата', 'Статья'];
        $context['count'] = 2;

        $sql = <<<EOL
SELECT * FROM Drivers_info WHERE [Body number] = :body_number
EOL;            
        $query = $this->pdo->prepare($sql); 
        $query->bindValue("body_number", $body_number);        
        $query->execute(); 

        $context['drivers'] = $query->fetchAll();   
        $context['driver_column_name'] = ['Фамилия', 'Адрес', 'Удостоверение'];
        $context['count'] = 2;

        return $context;
    }

    public function post(array $context) {
        $body_number = isset($_GET['id']) ? $_GET['id'] : '';
        $government_number = $_POST['government_number'];
        $technical_certificate = $_POST['technical_certificate'];
    
        $sql = <<<EOL
UPDATE CARS
SET government_number = :government_number, technical_certificate = :technical_certificate
WHERE body_number = :body_number 
EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue("body_number", $body_number);      
        $query->bindValue("government_number", $government_number);
        $query->bindValue("technical_certificate", $technical_certificate);        
        $query->execute();

        $context = $this->getContext();

        $this->get($context);
        }
        
        
}    

