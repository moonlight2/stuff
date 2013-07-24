<?php

namespace Flash\Bundle\DefaultBundle\Lib;

/*
 * File: SimpleImage.php
 * Author: Simon Jarvis
 * Copyright: 2006 Simon Jarvis
 * Date: 08/11/06
 * Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/gpl.html
 *
 */

class SimpleImage {

    private $image;
    private $image_type;
    private $image_info;
    private $filename;

    function load($filename) {

        $this->filename = $filename;
        $this->image_info = getimagesize($filename);
        $this->image_type = $this->image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {

            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {

            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {

            $this->image = imagecreatefrompng($filename);
        }
    }

    function crop($w, $h) {

        $w_orig = $this->image_info[1];
        $h_orig = $this->image_info[0];

        if ($w > $this->getWidth() && $h > $this->getHeight()) {
            $tmp = imagecreatetruecolor($this->getWidth(), $this->getHeight());
        } else if (($h > $this->getHeight()) && $w < $this->getWidth()) {
            $tmp = imagecreatetruecolor($w, $this->getHeight());
        } else if (($w > $this->getWidth()) && $h < $this->getHeight()) {
            $tmp = imagecreatetruecolor($this->getWidth(), $h);
        } else {
            $tmp = imagecreatetruecolor($w, $h);
        }
        imagecopyresampled($tmp, $this->image, 0, 0, 0, 0, $w, $h, $w, $h);
        $this->image = $tmp;
    }

    function cubeCrop($w) {

        $w_orig = $this->image_info[1];
        $h_orig = $this->image_info[0];

        $flag = false;

        if ($this->getWidth() > $this->getHeight()) {
            $flag = true;
        }
        if ($flag) {
            $this->resizeToHeight($w);
        } else {
            $this->resizeToWidth($w);
        }

        if ($w > $this->getWidth() && $w > $this->getHeight()) {

            $tmp = imagecreatetruecolor($this->getWidth(), $this->getHeight());
        } else if (($w > $this->getHeight()) && $w < $this->getWidth()) {

            $tmp = imagecreatetruecolor($w, $this->getHeight());
        } else if (($w > $this->getWidth()) && $h < $this->getHeight()) {

            $tmp = imagecreatetruecolor($this->getWidth(), $w);
        } else {
            
            $tmp = imagecreatetruecolor($w, $w);
        }
        imagecopyresampled($tmp, $this->image, 0, 0, 0, 0, $w, $w, $w, $w);
        $this->image = $tmp;
    }

    function smartCrop($w, $h) {

        $w_orig = $this->image_info[1];
        $h_orig = $this->image_info[0];

        $flag = false;

//        if ($this->getWidth() > $this->getHeight()) {
//            $flag = true;
//        }
//        if ($flag) {
//            $this->resizeToHeight($h);
//        } else {
//            $this->resizeToWidth($w);
//        }

        if ($w > $this->getWidth() && $h > $this->getHeight()) {

            $tmp = imagecreatetruecolor($this->getWidth(), $this->getHeight());
        } else if (($h > $this->getHeight()) && $w < $this->getWidth()) {

            $tmp = imagecreatetruecolor($w, $this->getHeight());
        } else if (($w > $this->getWidth()) && $h < $this->getHeight()) {

            $tmp = imagecreatetruecolor($this->getWidth(), $h);
        } else {
            //print_r($this->getWidth());
            //print_r($this->getHeight());
            //exit();
            if ($h > $w) {
                $this->resizeToHeight($h);
            } else {
                $this->resizeToWidth($w);
            }
            $tmp = imagecreatetruecolor($w, $h);
        }
        imagecopyresampled($tmp, $this->image, 0, 0, 0, 0, $w, $h, $w, $h);
        $this->image = $tmp;
    }

    function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null) {

        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {

            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {

            imagepng($this->image, $filename);
        }
        if ($permissions != null) {

            chmod($filename, $permissions);
        }
    }

    function output($image_type = IMAGETYPE_JPEG) {

        if ($image_type == IMAGETYPE_JPEG) {
            header('Content-Type: image/jpeg');
            imagejpeg($this->image);
        } elseif ($image_type == IMAGETYPE_GIF) {

            imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG) {

            imagepng($this->image);
        }
    }

    function getWidth() {

        return imagesx($this->image);
    }

    function getHeight() {

        return imagesy($this->image);
    }

    function resizeToHeight($height) {

        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    function resize($width, $height) {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

    public function getImage() {
        return $this->image;
    }

}

?>