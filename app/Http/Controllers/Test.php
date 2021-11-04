<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Test extends Controller
{
    public function index() : void {
//        $test = "";
//        $test = shell_exec("python test.py");
//        dd($test);
//        $process = new Process(['python']);
//        $process = new Process(["python.exe", "test.py"]);

//        $command = "C:\Python38\python.exe"." "."test.py";
//        $process = Process::fromShellCommandline($command);
//        $process->run();
//        $process = new Process(['python', 'test.py'],null, null);
//        $process->run();

        // executes after the command finishes
//        if (!$process->isSuccessful()) {
//            echo $process->getErrorOutput();
//        }
//        $process = Process::fromShellCommandline('echo "!MESSAGE!"');
//        $process->run();
//        dd($process->getOutput());
    }

}
