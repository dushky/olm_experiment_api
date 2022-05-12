<?php

namespace App\Helpers;


class Helpers
{
    public static function getScriptName(string $command, string $directory) {
        $localCommand = $command;
        if ($command == "startLocal") {
            $localCommand = "start";
        }
        $files = array_diff(scandir($directory), array('.', '..'));
        foreach($files as $fileName){
            $fileWithOutPostFix = explode(".", $fileName);
            if ($fileWithOutPostFix[0] == $localCommand) {
                return $fileName;
            }
        }
        return null;
    }

    public static function checkIfInitIsAvailable(string $directory) {
        $files = array_diff(scandir($directory), array('.', '..'));
        foreach($files as $fileName) {
            $fileWithOutPostFix = explode(".", $fileName);
            if ($fileWithOutPostFix[0] == "init") {
                return true;
            }
        }
        return false;
    }


    // In case of testing in other simulation software add another case with schema name
    public static function getSchemaNameForLocalStart(string $scriptName, string $software): string | null {
        switch($software) {
            case 'matlab': 
                if ($scriptName == 'startLocal')
                    return "SARADRC1.slx";
                    break;
            default:
                return null;
        }
    }
}
