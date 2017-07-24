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
class AmpAudioConverter implements ConverterInterface
{
    protected $Attributes = [
        "height" => "50",
        "width" => "auto"
    ];

    use ConverterBaseTrait;
    /** {@inheritdoc} */
    public function convert(): ConverterInterface
    {
        $oAudio = $this->Doc->firstChild; // Load the element
        $this->_extractAttributes($oAudio);
        $oAmpVideo = new \DOMElement("amp-audio");
        $oNoScript = new \DOMElement("noscript");
        @$this->Doc->loadHTML("",  LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $oPLaceholderNoscript = new \DOMElement("p");
        $oPLaceholderNoscript->appendChild(new \DOMText("Your browser either does not support JavaScript or HTML5 Audio."));
        $this->Doc->appendChild($oPLaceholderNoscript);
        foreach ($this->Attributes as $sName => $sValue) {
            $oAmpVideo->setAttribute($sName, $sValue);
        }
        $oAmpVideo->appendChild($oNoScript);
        $oNoScript->setAttribute("fallback", "");
        $oNoScript->appendChild($oAudio);

        return $this;
    }

}