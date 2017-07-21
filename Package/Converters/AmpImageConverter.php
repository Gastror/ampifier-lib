<?php
/**
 * Created by PhpStorm.
 * User: gastror
 * Date: 18-7-17
 * Time: 21:11
 */

namespace Amplifier\Package\Converters;


use Amplifier\Package\ConverterBaseTrait;
use Amplifier\Package\ConverterInterface;

/**
 * Class AmpImageConverter
 * @package Amplifier\Package\Converters
 * @property \DOMDocument $Doc
 * @mixin ConverterBaseTrait
 */
class AmpImageConverter implements ConverterInterface
{
    use ConverterBaseTrait;
    /** {@inheritdoc} */
    public function convert(): ConverterInterface
    {
        $oImg = $this->Doc->firstChild; // Load the element
        $this->_extractAttributes($oImg);
        $oAmpImg = new \DOMElement("amp-img");
        $oNoScript = new \DOMElement("noscript");
        @$this->Doc->loadHTML("",  LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $this->Doc->appendChild($oAmpImg);
        foreach ($this->Attributes as $sName => $sValue) {
            $oAmpImg->setAttribute($sName, $sValue);
        }
        $oAmpImg->appendChild($oNoScript);
        $oNoScript->setAttribute("fallback", "");
        $oNoScript->appendChild($oImg);

        return $this;
    }

}