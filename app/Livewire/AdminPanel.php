<?php

namespace App\Livewire;

use Livewire\Component;

class AdminPanel extends Component
{
    public $activeView = 'products';

    public function render()
    {
        return view('livewire.admin-panel');
    }
    
    public function switchView($view)
    {
        $this->activeView = $view;
    }
}