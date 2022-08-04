<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  use HasFactory;
  protected $gaurded =  [];

  public function getRouteKeyName()
  {
    return 'slug';
  }

  public function messages()
  {
    return $this->hasMany(Message::class)->orderBy('created_at', 'ASC');
  }
}
