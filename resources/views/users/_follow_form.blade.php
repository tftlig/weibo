{{-- 11.5章 在用户个人页面上增加一个关注表单，
  表单以按钮的形式展现，点击按钮即可对用户进行关注。 --}}

  {{-- 当用户访问的是自己的个人页面时，
    关注表单不应该被显示出来，因此我们加了下面这行代码用于判断： --}}
  @can('follow', $user)
  <div class="text-center mt-2 mb-4">
    @if (Auth::user()->isFollowing($user->id))
      <form action="{{ route('followers.destroy', $user->id) }}" method="post">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button type="submit" class="btn btn-sm btn-outline-primary">取消关注</button>
      </form>
    @else
      <form action="{{ route('followers.store', $user->id) }}" method="post">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-sm btn-primary">关注</button>
      </form>
    @endif
  </div>
@endcan
