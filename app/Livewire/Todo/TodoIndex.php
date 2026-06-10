<?php

namespace App\Livewire\Todo;

use App\Livewire\Forms\TodoForm;
use App\Models\Todo;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TodoIndex extends Component
{
    public $todos = [];
    public TodoForm $form;
    public $notCompletedToDoCount = 0;
    public $completedToDoCount = 0;
    public $todoCount = 0;


    public function mount()
    {
        $this->getToDos();
    }

    public function render()
    {
        return view('livewire.todo.todo-index');
    }

    public function storeToDo()
    {
        try {
            $this->form->id = null;
            $this->form->create();

            $this->form->reset();
            $this->getToDos();
        } catch (\Throwable $e) {
            Log::error('ToDoの登録に失敗しました: ' . $e->getMessage(), ['exception' => $e]);
            $this->addError('todo-error', 'ToDoの登録ができませんでした。');
        }
    }

    private function getToDos()
    {
        $this->todos = $todos = Todo::orderBy('created_at')->get();
        $this->notCompletedToDoCount = $todos->where('completed', Todo::STATUS_NOT_COMPLETED)->count();
        $this->completedToDoCount = $todos->where('completed', Todo::STATUS_COMPLETED)->count();
        $this->todoCount = $todos->count();
    }

    public function editToDo(int $id, string $currentTitle)
    {
        $this->form->id = $id;
        $this->form->editTitle = $currentTitle;
    }

    public function updateToDo()
    {
        try {
            $this->form->update();
            $this->form->reset();
            $this->getToDos();
        } catch (\Throwable $e) {
            $id = $this->form->id;
            Log::error("ToDo(ID:{$id})更新に失敗しました: " . $e->getMessage(), ['exception' => $e]);
            $this->addError('todo-error', 'ToDoの更新ができませんでした。');
        }
    }

    public function deleteToDo(int $id)
    {
        try {
            $this->form->delete($id);
            $this->form->reset();
            $this->getToDos();
        } catch (\Throwable $e) {
            Log::error("ToDo(ID:{$id})の削除に失敗しました: " . $e->getMessage(), ['exception' => $e]);
            $this->addError('todo-error', 'ToDoの削除ができませんでした。');
        }
    }

    public function completeToDo(int $id)
    {
        try {
            $this->form->complete($id);
            $this->form->reset();
            $this->getToDos();
        } catch (\Throwable $e) {
            Log::error("ToDo(ID:{$id})の完了処理に失敗しました: " . $e->getMessage(), ['exception' => $e]);
            $this->addError('todo-error', 'ToDoを完了にできませんでした。');
        }
    }

    public function reStoreToDo(int $id)
    {
        try {
            $this->form->reStore($id);
            $this->form->reset();
            $this->getToDos();
        } catch (\Throwable $e) {
            Log::error("ToDo(ID:{$id})の復元処理に失敗しました: " . $e->getMessage(), ['exception' => $e]);
            $this->addError('todo-error', 'ToDoを未完了に戻せませんでした。');
        }
    }
}
