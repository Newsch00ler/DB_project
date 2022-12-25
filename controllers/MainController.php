<?php
require_once "BaseTableTwigController.php";

class MainController extends BaseTableTwigController {
    public $template = "home.twig";

    public function getContext(): array
    {
        $context = parent::getContext();  
        return $context;
    }
}