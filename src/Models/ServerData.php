<?php

namespace Maximilian1121\Minegate\Models;

use App\Models\Server;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServerData extends Model
{
    protected $table = 'minegate_server_data';

    protected $fillable = [
        'server_id',
        'subdomain',
    ];
}