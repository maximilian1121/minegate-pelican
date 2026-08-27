<?php

namespace Maximilian1121\Minegate\Models;

use Illuminate\Database\Eloquent\Model;

class ServerData extends Model
{
    protected $table = 'minegate_server_data';

    protected $fillable = [
        'server_id',
        'subdomain',
    ];
}