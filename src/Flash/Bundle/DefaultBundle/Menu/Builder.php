<?php

namespace Flash\Bundle\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;


class Builder extends ContainerAware {

    public function basicMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'main_page'));
        $menu->addChild('Registration', array('route' => '_flash_registration'));
        $menu->addChild('Login', array('route' => '_flash_login'));

        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');
        $menu->addChild('Home', array('route' => 'main_page'));
        $menu->addChild('My Group', array('uri' => '#feed'));

        $menu->addChild('Logout', array('route' => '_flash_logout'));

        return $menu;
    }

    public function leaderMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');
        
        $menu->addChild('Home', array('route' => 'main_page'));
        $menu->addChild('My Group', array('route' => '_my_group_page'));
        $menu->addChild('Create group', array('uri' => '#new_group'));
        $menu->addChild('Logout', array('route' => '_flash_logout'));

        return $menu;
    }

    public function adminMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'main_page'));
        $menu->addChild('Registration', array('route' => '_flash_registration'));
        $menu->addChild('Logout', array('route' => '_flash_logout'));
        $menu->addChild('Admin panel', array('route' => '_flash_admin'));

        return $menu;
    }

    public function groupLeaderMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');

        
        $menu->addChild('Create event', array('uri' => '#new_event'));
        $menu->addChild('Users', array('uri' => '#users'));
        $menu->addChild('Administration', array('uri' => '#admin'));

        return $menu;
    }

}