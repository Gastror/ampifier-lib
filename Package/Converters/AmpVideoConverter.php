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
 * Class AmpVideoConverter
 * @package Amplifier\Package\Converters
 * @property \DOMDocument $Doc
 * @mixin ConverterBaseTrait
 */
class AmpVideoConverter implements ConverterInterface
{
    protected $Attributes = [
        "layout" => "container"
    ];

    use ConverterBaseTrait;
    /** {@inheritdoc} */
    public function convert(): ConverterInterface
    {
        $oVideo = $this->Doc->firstChild; // Load the element
        $this->_extractAttributes($oVideo);
        $oAmpVideo = new \DOMElement("amp-video");
        $oNoScript = new \DOMElement("noscript");
        @$this->Doc->loadHTML("",  LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $oPLaceholderNoscript = new \DOMElement("p");
        $oPLaceholderNoscript->appendChild(new \DOMText("Your browser either does not support JavaScript or HTML5 Video."));
        $this->Doc->appendChild($oPLaceholderNoscript);
        foreach ($this->Attributes as $sName => $sValue) {
            $oAmpVideo->setAttribute($sName, $sValue);
        }
        $oAmpVideo->appendChild($oNoScript);
        $oNoScript->setAttribute("fallback", "");
        $oNoScript->appendChild($oVideo);

        return $this;
    }

}