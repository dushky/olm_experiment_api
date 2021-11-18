<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Events\DataBroadcaster;
use Illuminate\Support\Facades\Log;

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
    //    $process = new Process(['python', 'test.py'],null, null);
    //    $process->run();
    //    dd($process->);
    //    echo $process->getOutput();
        // executes after the command finishes
    //    if (!$process->isSuccessful()) {
    //        echo $process->getErrorOutput();
    //    }
       
// //        $process = Process::fromShellCommandline('echo "!MESSAGE!"');
// //        $process->run();
    //    dd($process->getOutput());
        // $out = fopen('php://stdin', 'r');
        // stream_set_blocking($out, false);
        // dd(stream_get_contents($out));
        // fclose($out);
        // ini_set('output_buffering', 0);
        // $process = Process::fromShellCommandline("python3 test.py", null, null,null);
        // $process = new Process(['python3', 'test.py'], null, null, "test");
        // $process->setOptions([
        //     'blocking_pipes' => false,
        //     'create_new_console' => true
        // ]);
        
        // $process = new Process(['ls', '-lsa']);
        // $process->run(function ($type, $buffer) {
        //     if (Process::ERR === $type) {
        //         // echo 'ERR > '.$buffer;
        //     } else {
        //         Log::debug("OUT: ", [$buffer]);
        //         // echo 'OUT > '.$buffer;
        //     }
        // });
        // $process = new Process(['ls', '-lsa']);
        // $process->start();
        
        // $process->wait(function ($type, $buffer) {
        //     if (Process::ERR === $type) {
        //         echo 'ERR > '.$buffer;
        //     } else {
        //         Log::debug("OUT: ", [$buffer]);
        //         // echo 'OUT > '.$buffer;
        //     }
        // });
        $process = new Process(['python3', 'test.py']);
        // $process->setTimeout(null);
        $process->start(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERR > '.$buffer;
            } else {
                Log::debug("OUT", [$buffer]);
            }
        });


        // $iterator = $process->getIterator($process::ITER_SKIP_ERR | $process::ITER_KEEP_OUTPUT);
        // foreach ($iterator as $data) {
        //     Log::debug("DATA: ", [$data]);
        // }

        // while ($process->isRunning()) {
        //     // dd($process);
        //     // $process
        //     // foreach($process as $type => $data) {
        //     $data = $process->getIncrementalOutput();
        //     if ($data !== "")
        //         Log::debug("PROCESS:", [$data]);
        //     // }
        //     // foreach($data as &$process->getOutput())
        // }


        // if (!$process->isSuccessful()) 
        //     dd($process);

        // $readProcess = new Process(['sudo', 'test.py', '2>&1', 'r' ]);
        // $readProcess->run(function ($type, $buffer) {
        //     Log::debug("BUFFER: " , [$buffer]);
        // });
        
        
        // function($type, $buffer) {
        //     if (Process::ERR === $type) {
        //         dd("ERROR: ", $buffer);
        //     } else {
        //         Log::debug("SUCCESS: " . $buffer);
        //     }
        //     // dd($buffer);
        // }

        // while ($process->isRunning()) {
        //     if ($process->getIncrementalOutput() !== "")
        //         Log::debug("DATA: ", [$process->getIncrementalOutput()]);
        //     // if ($data = $process->getIncrementalOutput()) {
        //     //     // broadcast(new DataBroadcaster($data));
        //     //     // usleep(500000);

        //     // }
        // }
        
        // $process->wait(function ($type, $buffer) {
        //     // foreach ($process as $type => $data) {
        //         if (Process::OUT === $type) {
        //             Log::debug("DATA: ", [$buffer]);   //output store in log file..
        //             broadcast(new DataBroadcaster($buffer));
        //             // $this->info($data);  //show output in console..
        //             //       $this->info(print_r($data,true)) // if output is array or object then used
        //         } else {
        //             // $this->warn("error :- ".$data);
        //         }
        //     }

        //     // Log::debug("TEST", [$buffer]);
        //     // broadcast(new DataBroadcaster($buffer));
        //     // dd($buffer);
        // );        
    }


    public function test($type, $buffer) {
        echo $buffer;
    }
}
