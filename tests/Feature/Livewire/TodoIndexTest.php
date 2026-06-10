<?php

use App\Livewire\Todo\TodoIndex;
use App\Models\Todo;
use Livewire\Livewire;
use Illuminate\Support\Facades\Log;

// 毎回テスト実行前にデータベースをまっさらにする
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

/**
 * 1. 初期表示のテスト
 */
it('ToDo一覧ページが正しく表示され、件数がカウントされること', function () {
    // 既存のデータを準備
    Todo::factory()->create(['title' => '未完了タスク', 'completed' => Todo::STATUS_NOT_COMPLETED]);
    Todo::factory()->create(['title' => '完了タスク', 'completed' => Todo::STATUS_COMPLETED]);

    Livewire::test(TodoIndex::class)
        ->assertStatus(200)
        ->assertViewHas('notCompletedToDoCount', 1)
        ->assertViewHas('completedToDoCount', 1)
        ->assertViewHas('todoCount', 2)
        ->assertSee('未完了タスク')
        ->assertSee('完了タスク');
});

/**
 * 2. 新規登録のテスト
 */
it('新しいToDoを登録できること', function () {
    Livewire::test(TodoIndex::class)
        // フォームオブジェクトのプロパティに値をセット
        ->set('form.title', '買い物に行く')
        // 新規登録メソッドを実行
        ->call('storeToDo')
        // 実行後にエラーバッグが空であることを確認
        ->assertHasNoErrors()
        // 実行後に入力欄がリセットされていることを確認
        ->assertSet('form.title', '');

    // 実際にDBに入っているか確認
    $this->assertDatabaseHas('todos', [
        'title' => '買い物に行く',
        'completed' => Todo::STATUS_NOT_COMPLETED
    ]);
});

/**
 * 3. 編集・更新のテスト
 */
it('ToDoを編集モードにして更新できること', function () {
    $todo = Todo::factory()->create(['title' => '古いタイトル']);

    Livewire::test(TodoIndex::class)
        // 1. 編集ボタンを押した時の挙動をテスト
        ->call('editToDo', $todo->id, $todo->title)
        ->assertSet('form.id', $todo->id)
        ->assertSet('form.editTitle', '古いタイトル')
        
        // 2. 値を書き換えて更新ボタンを押す
        ->set('form.editTitle', '新しいタイトル')
        ->call('updateToDo')
        ->assertHasNoErrors();

    // DBが書き換わっているか確認
    expect($todo->refresh()->title)->toBe('新しいタイトル');
});

/**
 * 4. 完了・未完了復元のテスト
 */
it('ToDoを完了にでき、また未完了に戻せること', function () {
    $todo = Todo::factory()->create(['completed' => Todo::STATUS_NOT_COMPLETED]);

    // 完了処理のテスト
    $component = Livewire::test(TodoIndex::class)
        ->call('completeToDo', $todo->id);
    
    expect($todo->refresh()->completed)->toBe(Todo::STATUS_COMPLETED);

    // 復元処理のテスト
    $component->call('reStoreToDo', $todo->id);

    expect($todo->refresh()->completed)->toBe(Todo::STATUS_NOT_COMPLETED);
});

/**
 * 5. 削除のテスト
 */
it('ToDoを削除できること', function () {
    $todo = Todo::factory()->create();

    // 削除メソッドを実行
    Livewire::test(TodoIndex::class)
        ->call('deleteToDo', $todo->id)
        ->assertHasNoErrors();

    // 論理削除（SoftDelete）されていることを検証する専用のアサーション
    $this->assertSoftDeleted('todos', ['id' => $todo->id]);
});

/**
 * 6. 例外処理（try-catch）のテスト
 */
it('更新に失敗したときにログを出力し、画面にエラーを表示すること', function () {
    // 偽のログトラッカーを起動
    Log::shouldReceive('error')
        ->once() // 1回だけ実行されることを期待
        ->withAnyArgs(); // 引数の型や中身がどれだけ複雑でも、メソッドが呼ばれれば確実にパスさせる

    // 存在しないID（99999）を送り込んで、Formクラス内のバリデーション（exists:todos,id）をわざと爆発させる
    Livewire::test(TodoIndex::class)
        ->set('form.id', 99999)
        ->set('form.editTitle', '失敗させる値')
        ->call('updateToDo')
        // 画面側のエラーバッグに独自の「todo-error」が追加されたことを検証
        ->assertHasErrors(['todo-error']);
});