{{--显示用户列表的视图  8.4章--}}

@extends('layouts.default')
@section('title', '所有用户')

@section('content')
<div class="offset-md-2 col-md-8">
  <h2 class="mb-4 text-center">所有用户</h2>
  <div class="list-group list-group-flush">
    @foreach ($users as $user)
      {{-- 单独抽出来，作为局部视图，然后再引入。8.4章
      <div class="list-group-item">
        <img class="mr-3" src="{{ $user->gravatar() }}" alt="{{ $user->name }}" width=32>
        <a href="{{ route('users.show', $user) }}">
          {{ $user->name }}
        </a>
      </div> --}}
      @include('users._user')
    @endforeach
  </div>
    {{--渲染分页视图的代码--}}
    <div class="mt-3">
    {!! $users->render() !!}
    </div>
</div>
@stop
