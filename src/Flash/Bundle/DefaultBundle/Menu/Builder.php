<?php
namespace Flash\Bundle\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function basicMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'main_page'));
        $menu->addChild('Registration', array('route' => '_flash_registration'));
        $menu->addChild('Login', array('route' => '_flash_login'));

        return $menu;
    }
    
    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        
        $menu->addChild('Home', array('route' => 'main_page'));
        $menu->addChild('Registration', array('route' => '_flash_registration'));
        $menu->addChild('Logout', array('route' => '_flash_logout'));

        return $menu;
    }
    
    public function leaderMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        
        $menu->addChild('Home', array('uri' => '#'));
        $menu->addChild('Group events', array('uri' => '#feed'));
        $menu->addChild('Create group', array('uri' => '#new_group'));
        $menu->addChild('Create event', array('uri' => '#new_event'));
        $menu->addChild('Logout', array('route' => '_flash_logout'));

        return $menu;
    }
    
    public function adminMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'main_page'));
        $menu->addChild('Registration', array('route' => '_flash_registration'));
        $menu->addChild('Logout', array('route' => '_flash_logout'));
        $menu->addChild('Admin panel', array('route' => '_flash_admin'));

        return $menu;
    }
}