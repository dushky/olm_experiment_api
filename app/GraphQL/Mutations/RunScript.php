<?php

namespace App\GraphQL\Mutations;

use App\Events\DataBroadcaster;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;


class RunScript
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
        $process = new Process(['python', 'test.py'],null, null);
        // $process->run(function ($type, $buffer) {
        //     echo Process::OUT

        // });
        $process->run(function ($buffer) {
            Log::debug("TEST: ", [$buffer]);
        });

        

        if (!$process->isSuccessful()) {
            return [
                'output' => $process->getErrorOutput()
            ];
        }



        // return ['output' => $process->getOutput()];
    }
}
