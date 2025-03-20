<?php

namespace Modules\User\Actions\User;

use Modules\User\Entities\User;

class ImportUserPhotosActions
{

    public function execute(array $photos, int $company_id)
    {

        foreach ($photos as $photo) {
            $name = str_replace('.jpg', '', $photo->getClientOriginalName());

            $user = User::query()
                ->with(['profile'])
                ->where('company_id', $company_id)
                ->where('name', 'ilike', $name)
                ->first();

            if ($user) {
                $filepath = $photo->store(User::USER_PHOTO_PATH);

                $user->profile()->update([
                    'photo' => $filepath
                ]);
            }
        }
    }
}
