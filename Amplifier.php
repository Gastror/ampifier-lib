<?php
/**
 * Created by PhpStorm.
 * User: gastror
 * Date: 18-7-17
 * Time: 20:44
 */

namespace Amplifier;

use Amplifier\Package\ConverterException;
use Amplifier\Package\ConverterInterface;

class Amp
{
    const CONVERTER_CLASS_DIR = "Converters";
    const PACKAGE_DIR = "Package";

    public function __construct()
    {
        spl_autoload_register(function($sClassName){
            $sClassName = preg_replace("/^Amplifier\\\/", "", $sClassName);
            $sClassName = str_replace("\\", DIRECTORY_SEPARATOR, $sClassName);
            require_once $sClassName . ".php";
        });

        $sPackageDir = __DIR__ . DIRECTORY_SEPARATOR . self::PACKAGE_DIR . DIRECTORY_SEPARATOR;
        $sConvDir = $sPackageDir . self::CONVERTER_CLASS_DIR . DIRECTORY_SEPARATOR;
        $aDir = scandir($sConvDir);
        foreach ($aDir as $sFile) {
            if (!preg_match("/Amp[a-zA-Z]+Converter\.php$/", $sFile))
                continue;

            $sBasename = basename($sFile, ".php");
            $sPropertyName = preg_replace("/(^Amp|Converter$)/", "", basename($sFile));
            $sNamespacedName = "\\Amplifier\\Package\\Converters\\{$sBasename}";
            $this->{$sPropertyName} = new $sNamespacedName();
            $oConverter =& $this->{$sPropertyName};

            if (!$oConverter instanceof ConverterInterface) {
                unset($oConverter);
                unset($this->{$sPropertyName});
                throw new ConverterException("{$sBasename} was not properly implemented!");
            }
        }
    }
}