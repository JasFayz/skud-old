<?php

namespace Modules\Visitor\Actions\Invite;

use Modules\Visitor\Entities\Invite;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DownloadQrCode
{
    public function __invoke(Invite $invite)
    {
        return QrCode::format('png')->margin(5)->size(600)->generate($invite->url);
    }
}
