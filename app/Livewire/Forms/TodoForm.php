<?php

namespace App\Livewire\Forms;

use App\Models\Todo;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TodoForm extends Form
{
    public ?int $id = null;
    #[Validate] public string $title = '';
    #[Validate] public string $description = '';
    public int $completed = 0;

    protected function rules(): array
    {
        return [
            'id'          => ['nullable', 'integer', 'exists:todos,id'],
            'title'       => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'completed'   => ['required', 'integer', 'in:0,1'],
        ];
    }

    public function createOrUpdateTask()
    {
        $this->validate(); 

        Todo::updateOrCreate(
            ['id' => $this->id],
            $this->except(['id'])
        );
    }
}
