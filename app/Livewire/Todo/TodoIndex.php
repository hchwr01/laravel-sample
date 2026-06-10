<?php

namespace App\Livewire\Todo;

use App\Livewire\Forms\TodoForm;
use App\Models\Todo;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TodoIndex extends Component
{
    public $tasks = [];
    public TodoForm $form;

    public function mount()
    {
        $this->getTasks();
    }

    public function render()
    {
        return view('livewire.todo.todo-index');
    }

    public function storeTask()
    {
        $this->form->id = null;
        $this->form->createOrUpdateTask();
    
        $this->form->reset();
        $this->getTasks();
    }

    public function getTasks()
    {
        $this->tasks = Todo::orderBy('created_at')->get();
    }

    public function editTask(int $id, string $currentTitle)
    {
        $this->form->id = $id;
        $this->form->title = $currentTitle;
    }

}
