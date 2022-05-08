<?php

namespace App\Helpers;


class Helpers
{
    public static function getScriptName(string $command, string $directory) {
        $files = array_diff(scandir($directory), array('.', '..'));
        foreach($files as $fileName){
            $fileWithOutPostFix = explode(".", $fileName);
            if ($fileWithOutPostFix[0] == $command) {
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
}
