@php
    $authAuth = auth()->user();
    $todos = $authAuth->todos()->simplePaginate(3);
/*
1 min ago
12 mins ago
an hour ago
3 hours ago
5 hours ago, it the time is greater than 5 hours show Today at 10:20am if it's on the same day, or 
Yesterday at 10:20am if it's previous day

*/
@endphp

<div class="cd" ng-controller="TodoController">
    <div class="cd-h">
        <h3 class="cd-t">
            To Do List
        </h3>
        <div class="cd-ts">
            <ul class="pagination pagination-sm">
                <li class="page-item"><a href="#" class="page-link">«</a></li>
                <li class="page-item"><a href="#" class="page-link">1</a></li>
                <li class="page-item"><a href="#" class="page-link">2</a></li>
                <li class="page-item"><a href="#" class="page-link">3</a></li>
                <li class="page-item"><a href="#" class="page-link">»</a></li>
            </ul>
        </div>
    </div>

    <div class="cd-b !overflow-y-visible !py-0">
        <ul class="todo-list" data-widget="todo-list">
          @foreach ($todos as $n => $todo)
            <li class="relative border-b border-zinc-300 last:border-transparent !pb-3 last:!pb-0">
              
                <x-checkbox value="{{$todo->id}}" ng-checked="{{$todo->complete?'true':'false'}}" name="todo{{$todo->id}}" ng-change="check({{$todo->id}})" check="line-through">{{$todo->title}} <i class="badge badge-warning text-xs">{{timeago($todo->created_at)}}</i></x-checkbox>
              
                <div class="tools">
                    <i class="fas fa-edit"></i>
                    <i class="fas fa-trash-o"></i>
                </div>
            </li>
            
            @endforeach
        </ul>
    </div>
    <div class="cd-f clearfix">
    <form action="/todo/add" method="post" class="card-footer clearfix flex"
        ng-init="buttonText='Add item'; buttonType='button';">
        @csrf
        <div class="flex-1">
          <input ng-model="todo" type="text" name="todo" placeholder="Title of Todo" class="input flex-1" />
        </div>

        <button type="submit" ng-click="buttonType='submit'; buttonText='Save';"
            class="btn btn-primary float-right"><i class="fas fa-plus"></i> Add Task</button>

    </form>
  </div>
</div>

{{-- <fieldset class="border-slate-500/50 border p-4 rounded-md my-4" x-data="{todo:null}">

  <legend class="font-bold">
    Todo List 
  </legend>
  <ul>
    @foreach ($todos as $n => $todo)
    <li class="flex items-center gap-2"><input x-on:change="updateTodoList" value="{{$todo->id}}" type="checkbox" class="peer checkbox" id="todo{{$todo->id}}"> <label for="todo{{$todo->id}}" class="flex-1 peer-checked:line-through peer-checked:opacity-45 cursor-pointer">{{$todo->title}}</label></li>
    @endforeach
  </ul>
  
  <div>
    <form action="/todo/add" method="post" class="flex items-center gap-2 w-full justify-between">
      @csrf
      <input x-on:change="todo=$el.value" type="text" name="todo" placeholder="Title of Todo" class="input flex-1"/> <button x-on:click="submitTodo" type="submit" class="btn-primary">Save Todo</button>
    </form>
  </div>
</fieldset> --}}
