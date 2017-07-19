<?php
/**
 * Created by PhpStorm.
 * User: gastror
 * Date: 18-7-17
 * Time: 21:04
 */

namespace Amplifier\Package;


interface ConverterInterface
{
    public function getOutput() : string;
    public function setInput(string $sString) : ConverterInterface;
    public function getDOMDocument() : \DOMDocument;

    /**
     * Converts the stored HTML.
     * @return ConverterInterface
     */
    public function convert() : ConverterInterface;
}