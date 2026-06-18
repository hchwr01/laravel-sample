<?php

namespace App\Livewire\Forms;

use App\Models\Todo;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TodoForm extends Form
{
    public ?int $id = null;
    #[Validate] public string $title = '';
    #[Validate] public string $editTitle = '';
    #[Validate] public string $description = '';
    public int $completed = 0;

    protected function rules(): array
    {
        return [
            'id'          => ['nullable', 'integer', 'exists:todos,id'],
            'title'       => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'completed'   => ['required', 'integer', 'in:0,1'],
            'editTitle'   => ['required_with:id', 'string', 'min:3', 'max:255'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'title'       => 'ToDo名',
            'editTitle'   => '変更後のToDo名',
        ];
    }

    public function messages(): array
    {
        return [
            'editTitle.required_with' => '変更後のToDo名は必須項目です。',
        ];
    }

    public function create()
    {
        $this->validate([
            'title'       => $this->rules()['title'],
            'description' => $this->rules()['description'],
            'completed'   => $this->rules()['completed'],
        ]);

        Todo::create(
            $this->except(['id'])
        );
    }

    public function update()
    {
        $this->validate([
            'id'        => ['required', 'integer', 'exists:todos,id'],
            'editTitle' => $this->rules()['editTitle'],
        ]);

        $this->title = $this->editTitle;

        Todo::find($this->id)->update(
            $this->except(['id'])
        );
    }

    public function delete(int $id): int
    {
        $this->id = $id;
        $this->validate([
            'id' => ['required', 'integer', 'exists:todos,id']
        ]);

        return Todo::find($id)->delete();
    }

    public function complete(int $id): int
    {
        $this->id = $id;
        $this->validate([
            'id' => ['required', 'integer', 'exists:todos,id']
        ]);

        return Todo::find($id)->update(['completed' => TODO::STATUS_COMPLETED]);
    }

    public function reStore(int $id): int
    {
        $this->id = $id;
        $this->validate([
            'id' => ['required', 'integer', 'exists:todos,id']
        ]);

        return Todo::find($id)->update(['completed' => TODO::STATUS_NOT_COMPLETED]);
    }
}
