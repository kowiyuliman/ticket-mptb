<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTimeline extends Model
{
    protected $fillable = [
        'ticket_id',
        'status',
        'description'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}