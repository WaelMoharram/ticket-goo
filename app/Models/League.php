<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class League extends Model
{
    use HasTranslations;

    protected $table = 'leagues';
    protected $fillable = ['football_ticket_net_id', 'name', 'nice_name', 'image'];
    public $translatable = ['name', 'nice_name'];
}
