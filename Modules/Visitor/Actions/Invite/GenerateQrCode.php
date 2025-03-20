<?php

namespace Modules\Visitor\Actions\Invite;

use Illuminate\Support\Facades\URL;
use Modules\Visitor\Entities\Invite;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateQrCode
{
    public function __invoke(Invite $invite): void
    {
        $url = $this->generateUrl($invite);

        $qr_code = QrCode::size(100)->generate($url);

        $invite->update([
            'url' => $url,
            'qr_code' => $qr_code
        ]);
    }

    private function generateUrl($invite)
    {
        return URL::route('inviteDetailPage', ['invite' => $invite]);
    }
}
