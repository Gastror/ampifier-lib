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

    /**
     * Attribute mapping relevant to all instances of this Trait
     *
     * @var array
     */
    protected static $aDefaultAttributeMapping = [
        "src" => "/^(\/\/|https?:\/\/|\/)/",
        "alt" => "/.*/",
    ];

    /**
     * Required attribute mapping relevant to all instances of this Trait
     *
     * @var array
     */
    protected static $aDefaultRequiredAttributes = [

    ];

    /**
     * The DOMDocument for HTML manipulation.
     * Instantiated in ConverterBaseTrait::__construct();
     *
     * @var \DOMDocument
     */
    protected $Doc;

    /**
     * Storage for defined attributes.
     *
     * @var array
     */
    protected $Attributes = [];

    /**
     * Non-default mapping for attributes.
     * Should not be set in trait, only here for convenience.
     *
     * @var array
     */
    protected $AttributeMapping;

    /**
     * ConverterBaseTrait constructor. Should not be overridden.
     */
    public function __construct()
    {
        $this->Doc = new \DOMDocument();
        if(!isset($this->AttributeMapping)) {
            $this->AttributeMapping = [];
        }

        if(!isset($this->RequiredAttributes)) {
            $this->RequiredAttributes = [];
        }

        foreach (self::$aDefaultRequiredAttributes as $sName => $sCheck)
        {
            $this->RequiredAttributes[$sName] = $sCheck;
        }

        foreach (self::$aDefaultAttributeMapping as $sName => $sCheck)
        {
            if(!in_array($sName, $this->AttributeMapping)){
                $this->AttributeMapping[$sName] = $sCheck;
            }
        }
    }

    /**
     * Returns the DOMDocument instance used to storage/manipulationof HTML.
     * @return \DOMDocument
     */
    public function getDOMDocument(): \DOMDocument
    {
        return $this->Doc;
    }

    /**
     * Returns the current output.
     * @return string
     */
    public function getOutput(): string
    {
        $this->convert();
        return preg_replace("/<\?.+?\?>/", "", $this->Doc->saveXML());
    }

    /**
     * Consume HTMl into the DOMDocument.
     *
     * @param string $sHTML
     * @return ConverterInterface
     */
    public function setInput(string $sHTML): ConverterInterface
    {
        $this->Doc->loadHTML($sHTML,  LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        return $this;
    }

    /**
     * Set attributes in bulk.
     *
     * @param array $aInput
     * @return ConverterInterface
     * @throws ConverterException
     */
    public function setAttributes(array $aInput): ConverterInterface
    {
        foreach ($aInput as $sKey => $sValue) {
            if(empty($sValue) || !is_scalar($sValue) || is_array($sValue)){
                throw new ConverterException("An invalid value has been given for key {$sKey}!");
            }

            if(array_key_exists($sKey, $this->AttributeMapping)) {
                $mCheck = $this->AttributeMapping[$sKey];
                if(is_array($mCheck) && !in_array($sValue, $mCheck)) {
                    throw new ConverterException("Attribute {$sKey} was given a value of {$sValue}, while only the ".
                        "following values were allowed: ".implode(", ", $mCheck));
                } elseif (is_string($mCheck) && !preg_match($mCheck, $sValue)) {
                    throw new ConverterException("Attribute {$sKey} was given a value of {$sValue}, which did not ".
                        "match the following regular expression: \"{$mCheck}\"");
                }
            }

            $this->Attributes[$sKey] = $sValue;
        }

        return $this;
    }

    /**
     * Set extract attributes from an element and set it to the buffer.
     * Returns count of elements set.
     *
     * @param \DOMElement $oElement
     * @return int
     */
    private function _extractAttributes(\DOMNode $oElement) : int {
        $nC = 0;
        if($oElement->hasAttributes()) {
            foreach($oElement->attributes as $sKey => $sValue) {
                $this->setAttributes([$sKey => $sValue]);
                $nC++;
            }
        }
        return $nC;
    }
}