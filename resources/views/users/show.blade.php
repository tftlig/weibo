{{--用户个人中心视图--}}
{{--6.2章--}}

@extends('layouts.default')
@section('title',$user->name)

@section('content')
<div class="row">
  <div class="offset-md-2 col-md-8">
{{--     <div class="col-md-12">
      <div class="offset-md-2 col-md-8"> --}}
        <section class="user_info">
          @include('shared._user_info', ['user' => $user])
        </section>

        {{-- 为用户个人页面添加关注表单和信息统计视图 --}}
        @if (Auth::check())
          @include('users._follow_form')
        @endif

        {{-- 11.3章  个人页面也需要显示社交统计信息，修改用户个人中心页面视图--}}
        <section class="stats mt-2">
          @include('shared._stats', ['user' => $user])
        </section>
        <hr>

        {{---10.3章 在用户的个人页面使用该局部视图和渲染微博的分页链接了--}}
        <section class="status">
          @if ($statuses->count() > 0)
            <ul class="list-unstyled">
              @foreach ($statuses as $status)
                @include('statuses._status')
              @endforeach
            </ul>
            <div class="mt-5">
              {!! $statuses->render() !!}
            </div>
          @else
            <p>没有数据！</p>
          @endif
        </section>

{{--       </div>
    </div> --}}
  </div>
</div>
@stop
