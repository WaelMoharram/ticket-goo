<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Event extends Model
{
    use HasTranslations;

    protected $table = 'events';
    protected $fillable = [
        'football_ticket_net_id', 'name', 'date', 'full_date',
        'link', 'currency_code', 'stadium_id', 'league_id',
        'team1_id', 'team2_id'
    ];

    public $translatable = ['name'];


    public function stadium() {
        return $this->belongsTo(Stadium::class);
    }

    public function league() {
        return $this->belongsTo(League::class);
    }

    public function team1() {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2() {
        return $this->belongsTo(Team::class, 'team2_id');
    }
}
