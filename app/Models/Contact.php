<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;
    protected $fillable=[
        'name',
        'phone_number',
        'profile_picture',
        'user_id'
    ];

    public function folks(){
        return $this->hasMany(Folk::class)->chaperone();
    }

    public function messages(){
        return $this->hasMany(Message::class)->chaperone();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
