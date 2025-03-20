<?php


namespace Modules\Visitor\Actions\Invite;


use Modules\Visitor\Entities\Invite;

class SetInviteStatusAction
{
    public function handle(Invite $invite, $data)
    {

        return $invite->update([
            'status' => $data['status'],
            'comment' => $data['comment'],
            'approved_by' => \Auth::id(),
            'is_editable' => false
        ]);
    }
}
