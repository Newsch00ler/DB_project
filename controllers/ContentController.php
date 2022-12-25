<?php
require_once "BaseTableTwigController.php";

class ContentController extends BaseTableTwigController {
    public $template = "user_table.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        $type = isset($_GET['type']) ? $_GET['type'] : $_SESSION['type'];
        $driver_license = isset($_SESSION['driver_license']) ? $_SESSION['driver_license'] : '';
        $cure_page = isset($_GET['page']) ? $_GET['page'] : '';
        $limit = 100;
        $start = 0;
        $_SESSION['type'] = $type;

        if($cure_page != ''){
            $start = $limit*$cure_page-$limit; 
        }

        if($_SESSION['role'] == 'admin'){
            $this->template = "admin_table.twig";
        }

        if($type == 'offenses'){

            $context['header'] = 'Штрафы';
            $sql = <<<EOL
SELECT count(*) FROM [Driver_violations]
    WHERE [Driver license] = :driver_license
EOL;
            
            $query = $this->pdo->prepare($sql);
            $query->bindValue("driver_license", $driver_license);
            $query->execute();
            $count_all = $query->fetch();
            $sql = <<<EOL
SELECT * FROM [Driver_violations]
    WHERE [Driver license] = :driver_license
    ORDER BY [Driver license]
    OFFSET cast(:start as int) ROWS FETCH NEXT cast(:limit as int) ROWS ONLY
EOL;            
            $query = $this->pdo->prepare($sql);
            $query->bindValue("driver_license", $driver_license);
            $query->bindValue("start", $start);
            $query->bindValue("limit", $limit);        
            $query->execute(); 

            $context['objects'] = $query->fetchAll(); 
            $context['column_name'] = ['Статья', 'Мера пресечения', 'Дата'];
            $context['count'] = 2;

        }elseif($type == 'cars'){

            $context['header'] = 'Автомобили - штрафы';
            $sql = <<<EOL
SELECT count(*) FROM CARS
EOL;
            $query = $this->pdo->query($sql);
            $count_all = $query->fetch();
            $sql = <<<EOL
SELECT * FROM POSSESSION
ORDER BY date
    OFFSET cast(:start as int) ROWS FETCH NEXT cast(:limit as int) ROWS ONLY
EOL;            
            $query = $this->pdo->prepare($sql); 
            $query->bindValue("start", $start);
            $query->bindValue("limit", $limit);        
            $query->execute(); 

            $context['objects'] = $query->fetchAll();   
            $context['column_name'] = ['Номер кузова', 'Дата', 'Статья'];
            $context['count'] = 2;
            $context['href'] = '/car-edit';

        }elseif($type == 'drivers'){

            $context['header'] = 'Водители - штрафы';
            $sql = <<<EOL
SELECT count(*) FROM [Driver_violations]
EOL;
            $query = $this->pdo->query($sql);
            $count_all = $query->fetch();
            $sql = <<<EOL
SELECT * FROM [Driver_violations]
ORDER BY [Driver license]
    OFFSET cast(:start as int) ROWS FETCH NEXT cast(:limit as int) ROWS ONLY
EOL;
            $query = $this->pdo->prepare($sql); 
            $query->bindValue("start", $start);
            $query->bindValue("limit", $limit);        
            $query->execute(); 

            $context['objects'] = $query->fetchAll();
            $context['column_name'] = ['Удостоверение','Статья', 'Мера пресечения', 'Дата'];
            $context['count'] = 3;
            $context['href'] = '/driver-edit';

        } 

        $navi = new PaginateNavigationBuilder( "/table?page={page}" );
        $navi->tpl = "{page}";
        $nav = $navi->build( $limit, $count_all[0] , $cure_page ); 
        $context['nav'] = $nav;
                

        return $context;
    }
}

