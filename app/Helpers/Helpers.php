<?php

namespace App\Helpers;


class Helpers
{
    public static function getScriptName(string $command, string $directory) {
        $myfiles = array_diff(scandir($directory), array('.', '..'));
        foreach($myfiles as $fileName){
            $fileWithOutPostFix = explode(".", $fileName);
            if ($fileWithOutPostFix[0] == $command) {
                return $fileName;
            }
        }
        return null;
    }
}
