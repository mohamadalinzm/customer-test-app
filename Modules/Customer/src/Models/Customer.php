<?php

namespace Customer\Models;

use Customer\database\factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'ulid',
        'firstname',
        'lastname',
        'dateOfBirth',
        'phoneNumber',
        'email',
        'bankAccountNumber',
    ];

    protected static function newFactory(): Factory
    {
        return CustomerFactory::new();
    }
}
