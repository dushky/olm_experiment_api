<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ExperimentLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'device_id',
        'input_arguments',
        'output_path',
        'process_pid',
        'software_name',
        'schema_name',
        'started_at',
        'finished_at',
        'timedout_at',
        'stopped_at'
    ];


    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
