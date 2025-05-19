<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Stadium extends Model
{
    use HasTranslations;

    protected $table = 'stadiums';
    protected $fillable = ['football_ticket_net_id', 'name', 'city', 'country', 'image'];
    public $translatable = ['name', 'city', 'country'];
}
