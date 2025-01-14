<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TicketType extends Model
{
    const INTERNAL = 4;

    protected $langId;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->langId = Auth::user() ? Auth::user()->language_id : 1;
    }

    public function getNameAttribute()
    {
        $translationsArray = Language::find($this->langId)->lang_map;
        $name = $this->attributes['name'];
        return $translationsArray->ticket_types->$name ?? $this->attributes['name'];
    }
}
