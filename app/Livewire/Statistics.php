<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Statistics extends Component
{
    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function render(): View
    {
        return view('livewire.statistics', ['user' => auth()->user()]);
    }
}
