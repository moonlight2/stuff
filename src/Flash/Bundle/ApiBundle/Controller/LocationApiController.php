<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("")
 */
class LocationApiController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller {

    private $vkUrl = 'http://vk.com/select_ajax.php';

    /**
     * act=a_get_countries&basic=0 - get all contries
     * act=a_get_country_info&country=3&fields=9 - get one country cities
     * act=a_get_cities&country=18&str=%D1%85%D0%B5%D1%80 - get similar cities
     */

    /**
     * @Route("/ip")
     * @Method({"GET"})
     */
    public function getIp() {

        $ip = $this->get('request')->server->get("R    urlRoot: function() {
//        return '15/urlRoot';
//    },EMOTE_ADDR");
        return new \Symfony\Component\HttpFoundation\Response($ip);
    }

    /**
     * @Route("/api/country/{country_id}")
     * @Route("{acc_id}/api/country/{country_id}")
     * @Method({"GET"})
     * @param Integer $country_id
     * @return liat of cities
     */
    public function getCities($country_id) {

        header("Content-Type: text/plain; charset=windows-1251");

        $url = $this->vkUrl . '?act=a_get_country_info&country=' . $country_id . '&fields=9';

        $meminstance = new \Memcache();
        $meminstance->pconnect('localhost', 11211);

        $query = $url;
        $querykey = "KEY" . md5($query);
        $result = $meminstance->get($querykey);
        
        if (!$result) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: */*',
                'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
                'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.3'
            ));

            $result = curl_exec($ch);
            $meminstance->set($querykey, $result, 0, 864000);
        }
        echo $result;

        exit();
    }

    /**
     * @Route("/api/country/{id}/city/{name}")
     * @Route("/{acc_id}/api/country/{id}/city/{name}")
     * @Method({"GET"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function getSimilarCityAction($id, $name) {
//
        header("Content-Type: text/plain; charset=UTF-8");
        $url = $this->vkUrl . '?act=a_get_cities&country=' . $id . '&str=' . $name;

        $meminstance = new \Memcache();
        $meminstance->pconnect('localhost', 11211);

        $query = $url;
        $querykey = "KEY" . md5($query);

        $result = $meminstance->get($querykey);

        if (!$result) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: */*',
                'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
                'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.3'
            ));
            $result = str_replace("'", '"', curl_exec($ch));
            $meminstance->set($querykey, $result, 0, 864000);
        }
        echo $result;
        exit();
    }

    /**
     * @Route("/api/countries/{all}")
     * @Route("/{acc_id}/api/countries/{all}")
     * @Method({"GET"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function getCountries($all) {

        header("Content-Type: text/plain; charset=windows-1251");
        $url = 'http://vk.com/select_ajax.php';

        $meminstance = new \Memcache();
        $meminstance->pconnect('localhost', 11211);

        $querykey = "KEY_countries";
        $result = $meminstance->get($querykey);
        
        
        if (!$result) {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: */*',
                'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
                'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.3'
            ));
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'act=a_get_countries&basic=' . $all);

            $result = curl_exec($ch);
             $meminstance->set($querykey, $result, 0, 864000);
        }
        echo $result;
        exit();
    }

}