<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'amount',
        'category',
        'transaction_date',
        'receipt'
    ];

    protected $casts = [
        'transaction_date' => 'datetime', // ✅ otomatis jadi Carbon
    ];
    
    public $timestamps = true;
    
    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
