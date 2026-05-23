<?php

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;

class TicketExport implements FromCollection
{

public function collection()
{

return Ticket::select(

'ticket_code',
'nama',
'kategori',
'status',
'assigned_to',
'created_at'

)->get();

}

}
