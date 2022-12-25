<?php

class DriverEditController extends BaseTableTwigController{
    public $template = "driver.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        $_SESSION['url'] = $_SERVER['REQUEST_URI'];
        $sql = <<<EOL
SELECT * FROM OFFENSES
EOL;
        $query = $this->pdo->query($sql);
        $context['articles'] = $query->fetchAll();  
        
        $driver_license = isset($_GET['id']) ? $_GET['id'] : '';
        $context['id'] = $driver_license;
        $sql = <<<EOL
SELECT * FROM DRIVERS WHERE driver_license = :driver_license
EOL;
        $query =$this->pdo->prepare($sql);
        $query->bindValue("driver_license", $driver_license);
        $query->execute();
        $context['driver'] = $query->fetch();

        $sql = <<<EOL
SELECT [Cars count] FROM Count_auto WHERE [Driver license] = :driver_license
EOL;
        $query = $this->pdo->prepare($sql); 
        $query->bindValue("driver_license", $driver_license);
        $query->execute(); 
        $context['count_auto'] = $query->fetch();


        $context['count_violations'] = 0;
        $sql = <<<EOL
SELECT [Offenses count] FROM Count_violation WHERE [Driver license] = :driver_license
EOL;
        $query = $this->pdo->prepare($sql); 
        $query->bindValue("driver_license", $driver_license);
        $query->execute();
        $context['count_violations'] = $query->fetch(); 

        $sql = <<<EOL
SELECT [Article], [Punishment], [Date], [ID] FROM [Driver_violations]
WHERE [Driver license] = :driver_license
EOL;            
            $query = $this->pdo->prepare($sql);
            $query->bindValue("driver_license", $driver_license);       
            $query->execute(); 

            $context['objects'] = $query->fetchAll(); 
            $context['column_name'] = ['Статья', 'Мера пресечения', 'Дата'];
            $context['count'] = 2;
        
        return $context;
    }

    public function post(array $context) {                   
        $driver_license = isset($_GET['id']) ? $_GET['id'] : '';
        $name = $_POST['name'];
        $address = $_POST['address'];
    
        $sql = <<<EOL
UPDATE DRIVERS
SET full_name = :name, address = :address
WHERE driver_license = :driver_license 
EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue("driver_license", $driver_license);
        $query->bindValue("name", $name);
        $query->bindValue("address", $address);               
        $query->execute();

        $this->get($context);
    }    
}