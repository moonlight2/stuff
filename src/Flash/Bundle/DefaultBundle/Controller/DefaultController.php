<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller {

    /**
     * @Route("/upload", name="upload_page")
     * @Template()
     */
    public function uploadAction() {

        return array('name' => 'Upload action');
    }

    /**
     * @Route("/p{acc_id}/gallery", requirements={"id" = "\d+"}, name="_gallery")
     * @Template()
     */
    public function galleryAction($acc_id = null) {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if (NULL != $acc_id) {
            $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);
            if (NULL != $acc) {
                return array('name' => 'My gallery2' . $acc->getEmail(),
                    'acc_id' => $acc->getId(),
                    'own_id' => $user->getId());
            } else {
                throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
            }
        } else {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }
    }

    /**
     * @Route("/test/{id}", name="test_page")
     * @Template()
     */
    public function testAction($id = null) {

        //Создаём новый объект Memcache
        $memcache_obj = new \Memcache();

//Соединяемся с нашим сервером
        $memcache_obj->connect('127.0.0.1', 11211) or die("Could not connect");


//Попытаемся получить объект с ключом image
        $image_bin = @$memcache_obj->get('image');

        var_dump($image_bin);
        exit();
        if (empty($image_bin)) {

//Если в кэше нет картинки, сгенерируем её и закэшируем
            imagepng($this->LoadCPU(), getcwd() . '/tmp.png', 9);
            $image_bin = file_get_contents(getcwd() . '/tmp.png');
            unlink(getcwd() . '/tmp.png');
            $memcache_obj->set('image', $image_bin, false, 30);
        }

//Выведем картинку из кэша
        header('Content-type: image/png');
        echo $image_bin;

//Закрываем соединение с сервером Memcached
        $memcache_obj->close();



//        $em = $this->getDoctrine()->getManager();
//
//        $img = $em->getRepository('FlashDefaultBundle:Photo')->find($id);
//
//        $si = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
//
//        if (null != $img) {
//
//            $si->load($img->getAbsolutePath());
//
//            //echo $si->getHeight();
//            if ($si->getHeight() >= 400) {
//                $si->resizeToHeight(230);
//            }
//            //echo $si->getWidth();
//            $si->output();
//        } else {
//            echo "Image with id " . $id . " not found";
//        }
    }

    function LoadCPU() {

        $image = imagecreate(800, 600);


        $color = imagecolorallocate($image, 255, 255, 255);

        $color2 = imagecolorallocate($image, 0, 0, 0);

        for ($i = 0; $i < 10000; $i++) {
            imagesetpixel($image, rand(0, 800), rand(0, 600), $color2);
        }
        return $image;
    }

    function validateDate($date) {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/', $date, $parts) == true) {
            $time = gmmktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);

            $input_time = strtotime($date);
            if ($input_time === false)
                return false;

            return $input_time == $time;
        } else {
            return false;
        }
    }

    /**
     * @Route("/main", name="main_page")
     * @Template()
     */
    public function mainAction() {

        $acc = $this->get('security.context')->getToken()->getUser();
        return $this->redirect($this->generateUrl('_user_page', array('id' => $acc->getId())), 301);
    }

    /**
     * @Route("/p{id}", name="_user_page")
     * @Template()
     */
    public function userAction($id = null) {

        $em = $this->getDoctrine()->getManager();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($id);
        $loggedAcc = $this->get('security.context')->getToken()->getUser();

        $is_leader = ($loggedAcc->getIsLeader()) ? 1 : 0;

        if (null != $acc) {
            return array(
                'firstName' => $acc->getFirstName(),
                'lastName' => $acc->getLastName(),
                'acc_id' => $acc->getId(),
                'own_id' => $loggedAcc->getId(),
                'is_leader' => $is_leader,
            );
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Page not found');
        }
    }

    /**
     * @Route("/feed", name="_feed_page")
     * @Template()
     */
    public function feedAction() {

        $acc = $this->get('security.context')->getToken()->getUser();
        return array(
            'firstName' => $acc->getFirstName(),
            'lastName' => $acc->getLastName(),
            'own_id' => $acc->getId(),
        );
    }

    /**
     * @Route("/feed2", name="_feed2_page")
     * @Template()
     */
    public function feed2Action() {

        $acc = $this->get('security.context')->getToken()->getUser();
        return array(
            'firstName' => $acc->getFirstName(),
            'lastName' => $acc->getLastName(),
            'own_id' => $acc->getId(),
        );
    }

    /**
     * @Route("/p{id}/profile",  requirements={"id" = "\d+"}, name="_userp_profile__page")
     * @Template()
     */
    public function userProfileAction($id = null) {

        $em = $this->getDoctrine()->getManager();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();

        if ($user->getId() != $id) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException('Access denied');
        }

        if (null != $acc) {
            return array(
                'firstName' => $acc->getFirstName(),
                'lastName' => $acc->getLastName(),
                'acc_id' => $acc->getId(),
                'own_id' => $user->getId()
            );
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Page not found');
        }
    }

}
