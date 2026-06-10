<div class="max-w-3xl mx-auto my-12 px-6">

    <div class="flex items-center justify-between mb-10 pb-6 border-b border-gray-100">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                ToDoリスト
                <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
            </h1>
            <p class="text-sm font-medium text-gray-400 mt-1">シングルページアプリケーション</p>
        </div>
        <div class="text-right">
            <span
                class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 tracking-wide">
                {{ $todoCount ? ($notCompletedToDoCount ? "残り {$notCompletedToDoCount} 件のタスクがあります" : "全てのToDoが完了") : "ToDoは登録されていません" }}
            </span>
        </div>
    </div>

    @if ($errors->any())
        <div
            class="mb-6 p-4 bg-rose-50/80 border border-rose-100 text-rose-700 rounded-xl text-sm flex items-start gap-3 shadow-sm backdrop-blur-sm">
            <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <span class="font-bold block mb-0.5">入力エラーが発生しました</span>
                @foreach ($errors->all() as $error)
                    <span class="text-rose-600/90 font-medium">{{ $error }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <div
        class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md/5 transition-shadow duration-300 mb-8">
        <form class="space-y-2" onsubmit="return false;">
            <label class="block text-sm font-bold text-gray-700 tracking-wide pl-1">
                ToDoを追加
            </label>

            <div class="flex gap-3">
                <div class="relative flex-1">
                    <input type="text" placeholder="新しいTodoを入力..."
                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all duration-200 text-sm"
                        wire:model="form.title">
                </div>
                <button type="button"
                    class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition-all duration-200 shadow-sm shadow-indigo-600/20 flex items-center justify-center min-w-[80px]"
                    wire:click="storeToDo">追加
                </button>
            </div>

            <p class="text-xs font-medium text-gray-400 pl-1 pt-0.5">
                <span class="text-indigo-500 font-semibold">例:</span> ミーティングの資料を作成する、 Docker起動環境のセットアップ
            </p>
        </form>
    </div>

    @if($todos->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        @php
            $status = \App\Models\Todo::STATUS_NOT_COMPLETED;
        @endphp
        @if ($todos->where('completed', $status)->isNotEmpty())
            <div
                class="bg-gray-50/80 px-5 py-2.5 border-t border-b border-gray-100 text-xs font-bold text-gray-400 tracking-wider uppercase flex items-center justify-between">
                <span>ToDo</span>
                <span class="bg-gray-200/60 text-gray-500 px-2 py-0.5 rounded-md text-[10px]">{{ $notCompletedToDoCount }}件</span>
            </div>
            <ul class="divide-y divide-gray-50">
                @foreach ($todos->where('completed', $status) as $todo)
                    <li wire:key="todo-uncompleted-{{ $todo->id }}"
                        class="flex items-center justify-between p-5 hover:bg-gray-50/50 transition-colors group {{ $form->id === $todo->id ? 'bg-indigo-50/20' : '' }}">

                        <div class="flex items-center flex-1 min-w-0 gap-4">
                            <input type="checkbox" {{ $form->id === $todo->id ? 'disabled opacity-50' : '' }}
                                wire:click="completeToDo({{ $todo->id }})"
                                class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer transition-colors">

                            @if ($todo->id !== $form->id)
                                <span class="block text-sm font-semibold text-gray-700 truncate tracking-wide">
                                    {{ $todo->title }}
                                </span>
                            @else
                                <div class="flex items-center gap-2 flex-1">
                                    <input type="text"
                                        class="w-full px-3 py-1.5 text-sm font-semibold border border-indigo-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white shadow-inner text-gray-800"
                                        wire:model="form.editTitle">
                                    <button
                                        type="button"
                                        wire:click="updateToDo()"
                                        class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-md shadow-sm flex-shrink-0">
                                        保存
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="$set('form.id', null)"
                                        class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-500 text-xs font-bold rounded-md flex-shrink-0">
                                        取消
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if ($todo->id !== $form->id)
                            <div
                                class="flex items-center gap-1.5 ml-4 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                                <button
                                    class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                    wire:click="editToDo({{ $todo->id }}, '{{ addslashes($todo->title) }}')"
                                    title="編集">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button
                                    class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                    wire:click="deleteToDo({{ $todo->id }})" title="削除">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        @endif

                    </li>
                @endforeach
            </ul>
        @endif

        @php
            $status = \App\Models\Todo::STATUS_COMPLETED;
        @endphp
        @if ($todos->where('completed', $status)->isNotEmpty())
            <div
                class="bg-gray-50/80 px-5 py-2.5 border-t border-b border-gray-100 text-xs font-bold text-gray-400 tracking-wider uppercase flex items-center justify-between">
                <span>完了済みのToDo</span>
                <span class="bg-gray-200/60 text-gray-500 px-2 py-0.5 rounded-md text-[10px]">{{ $completedToDoCount }}件</span>
            </div>

            <ul class="divide-y divide-gray-50 bg-gray-50/30">
                @foreach ($todos->where('completed', $status) as $todo)
                    <li wire:key="todo-completed-{{ $todo->id }}"
                        class="flex items-center justify-between p-5 transition-colors group">
                        <div class="flex items-center flex-1 min-w-0 gap-4">
                            <input type="checkbox" checked
                                class="w-5 h-5 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer opacity-70"
                                wire:click="reStoreToDo({{ $todo->id }})"
                                >
                            <span class="block text-sm font-semibold line-through text-gray-400 truncate tracking-wide">
                                {{ $todo->title }}
                            </span>
                        </div>
                        <div
                            class="flex items-center gap-1.5 ml-4 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            <button
                                class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                wire:click="deleteToDo({{ $todo->id }})" title="削除">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    @endif
</div>
