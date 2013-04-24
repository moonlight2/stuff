<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/api")
 */
class LocationApiController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller {

    private $vkUrl = 'http://vk.com/select_ajax.php';

    /**
     * act=a_get_countries&basic=0 - get all contries
     * act=a_get_country_info&country=3&fields=9 - get one country cities
     * act=a_get_cities&country=18&str=%D1%85%D0%B5%D1%80 - get similar cities
     */

    /**
     * @Route("/country/{country_id}")
     * @Method({"GET"})
     * @param Integer $country_id
     * @return liat of cities
     */
    public function getCities($country_id) {

        header("Content-Type: text/plain; charset=windows-1251");
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->vkUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: */*',
            'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
            'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.3'
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'act=a_get_country_info&country=' . $country_id . '&fields=9');

        $result = curl_exec($ch);
        $data = substr($result, 0, -1);
        echo $data;
        exit();
    }

    /**
     * @Route("/country/{id}/city/{name}")
     * @Method({"GET"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function getSimilarCityAction($id, $name) {

        header("Content-Type: text/html; charset=UTF-8");
        $url = $this->vkUrl.'?act=a_get_cities&country=' . $id . '&str=' . $name;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: */*',
            'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
            'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.3'
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $url);

        $result = curl_exec($ch);
        $data = substr($result, 0, -1);
        echo $data;
        exit();
    }

    /**
     * @Route("/countries")
     * @Method({"GET"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function getCountries() {

        header("Content-Type: text/html; charset=windows-1251");
        $url = 'http://vk.com/select_ajax.php';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: */*',
            'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
            'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.3'
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'act=a_get_countries&basic=0');

        $result = curl_exec($ch);
        $data = substr($result, 0, -1);
        echo $data;
        exit();
    }

}