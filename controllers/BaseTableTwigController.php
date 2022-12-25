<?php

class BaseTableTwigController extends TwigBaseController{
    
    public function getContext(): array
    {
        $context = parent::getContext();  
        if($_SESSION['role'] == 'admin'){
            $context['dropdown_header'] = 'Администратор';
            $context['first_card_header'] = 'Автомобили';
            $context['second_card_header'] = 'Водители';
            $context['first_card_text'] = 'Информация о штрафах - автомобили';
            $context['second_card_text'] = 'Информация о штрафах - водители';
            $context['first_url'] = '/table?type=cars';
            $context['second_url'] = '/table?type=drivers';
        }else{
            $context['dropdown_header'] = 'Водитель';
            $context['first_card_header'] = 'Профиль';
            $context['second_card_header'] = 'Штрафы';
            $context['first_card_text'] = 'Просмотреть всю личную информацию';
            $context['second_card_text'] = 'Просмотреть всю информацию о штрафах';
            $context['first_url'] = '/user?type=profile';
            $context['second_url'] = '/table?type=offenses';
        }
        return $context;
    }
}