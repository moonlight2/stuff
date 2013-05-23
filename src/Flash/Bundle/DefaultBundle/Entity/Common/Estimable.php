<?php

namespace Flash\Bundle\DefaultBundle\Entity\Common;

interface Estimable {
    
    public function addRating(\Symfony\Component\Security\Core\User\UserInterface $rating);

    public function getRating();

    public function removeRating(\Symfony\Component\Security\Core\User\UserInterface $rating);

    public function getRatingCount();
}