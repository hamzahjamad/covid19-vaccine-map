<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccineState extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        "date",
        "state",
        "daily_partial",
        "daily_full",
        "daily",
        "cumul_partial",
        "cumul_full",
        "cumul",
        "pfizer1",
        "pfizer2",
        "sinovac1",
        "sinovac2",
        "astra1",
        "astra2",
        "pending",
    ];
}
