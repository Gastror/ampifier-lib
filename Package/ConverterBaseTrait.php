<?php
/**
 * Created by PhpStorm.
 * User: gastror
 * Date: 19-7-17
 * Time: 8:19
 */

namespace Amplifier\Package;

/**
 * Trait ConverterBaseTrait
 * @package Amplifier\Package
 * @mixin ConverterInterface
 */
trait ConverterBaseTrait
{

    protected $Doc = null;

    public function __construct()
    {
        $this->Doc = new \DOMDocument();
    }

    public function getDOMDocument(): \DOMDocument
    {
        return $this->Doc;
    }

    public function getOutput(): string
    {
        $this->convert();
        return $this->Doc->saveXML();
    }

    public function setInput(string $sHTML): ConverterInterface
    {
        $this->Doc->loadHTML($sHTML);
        return $this;
    }
}