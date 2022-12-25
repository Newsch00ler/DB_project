<?php

class RowDeleteController extends BaseController {
    public function post(array $context)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $type = isset($_SESSION['type']) ? $_SESSION['type'] : '';

        if($type == 'drivers'){
            $sql = <<<EOL
exec DeleteReceipt :id
EOL;
        }
        else{
            $sql = <<<EOL
exec DeletePossession :id
EOL;
        }
        $query = $this->pdo->prepare($sql);
        $query->bindValue("id", $id);
        $query->execute();
        $url = $_SERVER['HTTP_REFERER'];
        header("Location: $url");
        exit;
    }
}