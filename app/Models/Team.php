<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Team extends Model
{
    use HasTranslations;

    protected $table = 'teams';
    protected $fillable = ['football_ticket_net_id', 'name', 'nice_name', 'image'];
    public $translatable = ['name', 'nice_name'];
}
