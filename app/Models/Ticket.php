<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_code',
        'user_id',
        'nama',
        'nomor_meja',
        'nomor_ruangan',
        'ip_address',
        'kategori',
        'deskripsi',
        'screenshot',
        'status',
        'assigned_to',
        'started_at',
        'created_by',
        'resolved_at',
        'no_whatsapp'
    ];

    protected $casts = [
    'started_at' => 'datetime',
    'resolved_at' => 'datetime',
    ];

    protected $dates = [
    'started_at',
    'resolved_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function histories()
    {
        return $this->hasMany(TicketHistory::class);
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class,'assigned_to');
    }

    public function getSlaAttribute()
    {
        if ($this->started_at && $this->resolved_at) {

        $minutes = $this->started_at->diffInMinutes($this->resolved_at);

        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        return $hours.' jam '.$mins.' menit';
        
    }

    // 🔥 kalau belum selesai → hitung realtime
    if ($this->started_at && !$this->resolved_at) {

        $minutes = $this->started_at->diffInMinutes(now());

        return $minutes . ' menit (berjalan)';
    }

    return '-';

    if ($this->started_at) {
        $hours = $this->started_at->diffInHours(now());
        return 'On Progress (' . $hours . ' jam)';
    }

    return 'Belum mulai';
    }

    public function getDurationAttribute()
    {
        if($this->started_at && $this->resolved_at){
            return $this->started_at->diffForHumans($this->resolved_at, true);
        }

        if($this->started_at){
            return 'Sedang berjalan: '.$this->started_at->diffForHumans(now(), true);
        }

        return 'Belum ada aktivitas';
    }

    public function getDurasiMenitAttribute()
    {
        if (!$this->started_at) {
            return null;
        }

        $end = $this->resolved_at ?? now();

        $totalMinutes = $this->started_at->diffInMinutes($end);

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return $hours . ' jam ' . $minutes . ' menit';
        } elseif ($hours > 0) {
            return $hours . ' jam';
        } else {
            return $minutes . ' menit';
        }
    }

    public function timelines()
    {
        return $this->hasMany(TicketTimeline::class);
    }

}