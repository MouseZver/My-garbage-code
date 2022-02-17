<?php

declare ( strict_types = 1 );

error_reporting ( E_ALL );


if (!extension_loaded('imagick')) {
    if (!dl('imagick')) {
        exit;
    }
}

//echo phpinfo();

//echo shell_exec ( 'import -window root png:-' );

$image = new Imagick;

$image -> setImageFormat('png');

$image -> displayImage('1.png');