<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TicketTimeline extends Model
{
    protected $fillable = [
        'ticket_id',
        'status',
        'description',
        'user_id'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}