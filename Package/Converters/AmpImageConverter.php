<?php
/**
 * Created by PhpStorm.
 * User: gastror
 * Date: 18-7-17
 * Time: 21:11
 */

namespace Amplifier\Package\Converters;


use Amplifier\Package\ConverterBaseTrait;
use Amplifier\Package\ConverterException;
use Amplifier\Package\ConverterInterface;

/**
 * Class AmpImageConverter
 * @package Amplifier\Package\Converters
 * @property array[]|string[] $AttributeMapping
 * @property \DOMDocument $Doc
 * @mixin ConverterBaseTrait
 */
class AmpImageConverter implements ConverterInterface
{
    use ConverterBaseTrait;
    /** {@inheritdoc} */
    public function convert(): ConverterInterface
    {
        $oImg = $this->Doc->firstChild;

        $oNoScript = new \DOMElement("noscript");
        $oNoScript->setAttribute("fallback", "");
        $oNoScript->appendChild($oImg);

        $oAmpImg = new \DOMElement("amp-img");
        foreach ($this->Attributes as $sName => $sValue) {
            $oAmpImg->setAttribute($sName, $sValue);
        }

        $this->Doc->loadHTML("");
        $this->Doc->appendChild($oAmpImg);
        return $this;
    }

}