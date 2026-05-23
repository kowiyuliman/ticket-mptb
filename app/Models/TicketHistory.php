<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
    protected $fillable = [
        'ticket_id',
        'status',
        'keterangan',
        'updated_by'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
