<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folk extends Model
{
    /** @use HasFactory<\Database\Factories\FolkFactory> */
    use HasFactory;
    protected $fillable=[
        'relationship',
        'contact_id',
        'contact_rel_id'
    ];

    public function contact(){
        return $this->belongsTo(Contact::class);
    }
}
