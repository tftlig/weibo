<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Status;
use Illuminate\Auth\Access\HandlesAuthorization;

// 10.6章  删除微博的授权策略
class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    // 如果当前用户的 id 与要删除的微博作者 id 相同时，验证才能通过。
     public function destroy(User $user,Status $status){
        return $user->id === $status->user_id;
     }


}
