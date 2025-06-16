<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tickets;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Storage;
use App\Events\NewTicketCreated; // Add this import

class Helpdesk extends Component
{
    use WithFileUploads;

    #[Rule('required|string|max:255')]
    public $subject = '';

    #[Rule('required|string')]
    public $description = '';

    #[Rule('nullable|sometimes|array')]
    public $attachments = [];

    public $tickets = [];
    public $selectedTicket = null;
    public $confirmingDeletion = false;
    public $ticketToDelete = null;

    public function mount()
    {
        $this->loadTickets();
    }

    public function loadTickets()
    {
        $this->tickets = Tickets::where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    public function createTicket()
    {
        $this->validate();

        $ticket = Tickets::create([
            'ticket_id' => 'TKT-' . Str::random(8),
            'user_id' => auth()->id(),
            'subject' => $this->subject,
            'description' => $this->description,
            'status' => 'open',
        ]);

        if ($this->attachments) {
            foreach ($this->attachments as $attachment) {
                $path = $attachment->store('ticket-attachments', 'public');
                $ticket->update(['attachment' => $path]);
            }
        }

        // Dispatch the NewTicketCreated event
        event(new NewTicketCreated($ticket));

        $this->reset(['subject', 'description', 'attachments']);
        $this->loadTickets();
        $this->dispatch('close-modal', id: 'createTicketModal');
        $this->dispatch('notify', type: 'success', message: 'Ticket created successfully!');
    }

    public function viewTicket($ticketId)
    {
        $this->selectedTicket = Tickets::where('user_id', auth()->id())
                                    ->findOrFail($ticketId);

        $this->dispatch('open-modal', id: 'viewTicketModal');
    }

    public function confirmDelete($ticketId)
    {
        $this->ticketToDelete = $ticketId;
        $this->confirmingDeletion = true;
        $this->dispatch('open-modal', id: 'confirmDeleteModal');
    }

    public function deleteTicket()
    {
        $ticket = Tickets::where('user_id', auth()->id())
                        ->findOrFail($this->ticketToDelete);

        // Delete attachment if exists
        if ($ticket->attachment) {
            Storage::disk('public')->delete($ticket->attachment);
        }

        $ticket->delete();

        $this->confirmingDeletion = false;
        $this->ticketToDelete = null;
        $this->loadTickets();
        $this->dispatch('close-modal', id: 'confirmDeleteModal');
        $this->dispatch('notify', type: 'success', message: 'Ticket deleted successfully!');
    }

    #[Layout('layouts.app')]
    #[Title('Help Desk')]
    public function render()
    {
        return view('livewire.helpdesk');
    }
}
