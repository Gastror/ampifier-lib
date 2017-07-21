<?php
/**
 * Created by PhpStorm.
 * User: gastror
 * Date: 18-7-17
 * Time: 21:08
 */

include "Amplifier.php";
$oAmp = new \Amplifier\Amp();
echo $oAmp->Image->setInput("<img src='http://lorempixel.nl/480/320/' alt='Lorem pixel plaatje'/>")->getOutput();