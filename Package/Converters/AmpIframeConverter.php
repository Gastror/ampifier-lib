<?php
/**
 * Created by PhpStorm.
 * User: gastror
 * Date: 22-7-17
 * Time: 9:05
 */

namespace Amplifier\Package\Converters;


use Amplifier\Package\ConverterBaseTrait;
use Amplifier\Package\ConverterInterface;

class AmpIframeConverter implements ConverterInterface
{
    use ConverterBaseTrait {
        getOutput as traitGetOutput;
    }

    protected $AttributeMapping = [
        "frameborder" => "/^[0-9]+($|px$)/",
        "allowfullscreen" => "/^$/",
    ];

    private static $bOutputScript = true;

    protected $Attributes = [
        "layout" => "responsive",
        "frameborder" => "0",
        "sandbox" => "allow-scripts allow-same-origin"
    ];

    public function convert(): ConverterInterface
    {
        $oIframe = $this->Doc->firstChild; // Load the element
        $this->_extractAttributes($oIframe);
        $oAmpIframe = new \DOMElement("amp-iframe");
        $oNoScript = new \DOMElement("noscript");
        @$this->Doc->loadHTML("",  LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $this->Doc->appendChild($oAmpIframe);
        foreach ($this->Attributes as $sName => $sValue) {
            $oAmpIframe->setAttribute($sName, $sValue);
        }
        $oAmpIframe->appendChild($oNoScript);
        $oNoScript->setAttribute("fallback", "");
        $oNoScript->appendChild($oIframe);

        return $this;
    }

    public function getOutput(): string
    {
        if($this::$bOutputScript) {
            self::$bOutputScript = false;
            $sScript = "<script async custom-element=\"amp-iframe\" src=\"https://cdn.ampproject.org/v0/amp-iframe-0.1.js\"></script>";
        }
        return ($sScript ?? "") . $this->traitGetOutput();
    }

    /**
     * @param null|bool $bSet
     * @return bool self::$bOutputScript;
     */
    public static function toggleScriptOutput($bSet = null) {
        return self::$bOutputScript = $bSet ?? !self::$bOutputScript;
    }
}