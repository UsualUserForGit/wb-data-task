<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenType extends Model
{
    protected $table = 'token_types';

    protected $fillable = [
        'name'
    ];

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }
}
