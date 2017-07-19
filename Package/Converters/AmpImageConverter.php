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

class AmpImageConverter implements ConverterInterface
{
    use ConverterBaseTrait;

    protected static $aAttributeMapping = [
        "src" => "/^(\/\/|https?:\/\/|\/)/",
        "alt" => "/.*/",
    ];

    public function setAttributes(array $aInput): ConverterInterface
    {
        foreach ($aInput as $sKey => $sValue) {
            if(empty($sValue) || !is_scalar($sValue) || is_array($sValue)){
                throw new ConverterException("An invalid value has been given for key {$sKey}!");
            }

            if(array_key_exists($sKey, self::$aAttributeMapping)) {
                $mCheck = self::$aAttributeMapping[$sKey];
                if(is_array($mCheck) && !in_array($sValue, $mCheck)) {
                    throw new ConverterException("Attribute {$sKey} was given a value of {$sValue}, while only the ".
                        "following values were allowed: ".implode(", ", $mCheck));
                } elseif (!preg_match($mCheck, $sValue)) {
                    throw new ConverterException("Attribute {$sKey} was given a value of {$sValue}, which did not ".
                        "match the following regular expression: \"{$mCheck}\"");
                }
            }


        }
    }

    public function convert(): ConverterInterface
    {
        $oNoScript = new \DOMElement("noscript");
        $oNoScript->set

        return $this;
    }

}