<?php

namespace App\Livewire;

use App\Support\Contact;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Контакты')]
class Contacts extends Component
{
    public function render()
    {
        return view('livewire.contacts', [
            'messengers' => Contact::messengers(),
            'map' => Contact::mapEmbedUrl(),
        ]);
    }
}
