<?php
require_once "BaseTableTwigController.php";

class UserController extends BaseTableTwigController {
    public $template = "user.twig";

    public function getContext(): array
    {
        $context = parent::getContext();   
        $driver_license = isset($_SESSION["driver_license"]) ? $_SESSION["driver_license"] : '';
        $sql = <<<EOL
SELECT * FROM DRIVERS WHERE driver_license = :driver_license
EOL;
        $query = $this->pdo->prepare($sql); 
        $query->bindValue("driver_license", $driver_license);
        $query->execute(); 
        $data =  $query->fetchAll();
        $context['driver'] = $data[0];

        $sql = <<<EOL
SELECT [Cars count] FROM Count_auto WHERE [Driver license] = :driver_license
EOL;
        $query = $this->pdo->prepare($sql); 
        $query->bindValue("driver_license", $driver_license);
        $query->execute(); 
        $context['count_auto'] = $query->fetch();

        $sql = <<<EOL
SELECT [Offenses count] FROM Count_violation WHERE [Driver license] = :driver_license
EOL;
        $query = $this->pdo->prepare($sql); 
        $query->bindValue("driver_license", $driver_license);
        $query->execute();
        $context['count_violations'] = $query->fetch();

        return $context;
    }

    public function post(array $context) {
        $driver_license = isset($_SESSION["driver_license"]) ? $_SESSION["driver_license"] : '';
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

        $context = $this->getContext();

        $this->get($context);
    }
    
}

