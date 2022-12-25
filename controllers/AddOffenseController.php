<?php

class AddOffenseController extends BaseTableTwigController{
    public $template = "add_offense.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        $sql = <<<EOL
SELECT * FROM OFFENSES
EOL;
        $query = $this->pdo->query($sql);
        $context['articles'] = $query->fetchAll();
        
        if($_GET['type'] == 'cars'){
            $context['header1'] = 'Автомобиль';
            $context['header2'] = 'Удостоверение';
        }else{
            $context['header1'] = 'Водитель';
            $context['header2'] = 'VIN автомобиля';
        }
        

        return $context;
    }

    public function post(array $context){
        $id = isset($_GET["id"]) ? $_GET["id"] : '';
        $article = isset($_POST["article"]) ? $_POST["article"] : '';

        if($_GET['type'] == 'cars'){
            $sql = <<<EOL
EXEC CreatePossession :id, :article
EOL;
            $query = $this->pdo->prepare($sql);
            $query->bindValue("id", $id);      
            $query->bindValue("article", $article); 
        }elseif($_GET['type'] == 'driver'){
            $sql = <<<EOL
EXEC CreateReceipt :id, :article
EOL;
            $query = $this->pdo->prepare($sql);
            $query->bindValue("id", $id);      
            $query->bindValue("article", $article); 
        }
                  
        $query->execute();
        $url = $_SESSION['url'];
        header("Location: $url");
        $this->get($context);
    }
        
        
}    


