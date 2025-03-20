<?php


Route::get('/import-image', function () {
    return view('welcome');
})->name('import-image');

Route::post('/import-image-upload', function (\Illuminate\Http\Request $request) {
    $zip = new ZipArchive();

    $status = $zip->open($request->file("file")->getRealPath());
    if ($status !== true) {
        throw new \Exception($status);
    } else {
        $storageDestinationPath = storage_path("app/uploads/unzip/");

        if (!\File::exists($storageDestinationPath)) {
            \File::makeDirectory($storageDestinationPath, 0755, true);
        }
        $zip->extractTo($storageDestinationPath);
        $zip->close();

        $files = \Illuminate\Support\Facades\File::allFiles(storage_path("app/uploads/unzip/"));

        $users = \Modules\User\Entities\User::with('profile')->get();
        if (!\File::exists(storage_path('app/uploads/user/imported-photos/'))) {
            \File::makeDirectory(storage_path('app/uploads/user/imported-photos/', 0755, true));
        }
        foreach ($files as $file) {
            if ($file->getExtension() == 'jpg' || $file->getExtension() === 'JPG' || $file->getExtension() == 'png') {
                $userName = explode('.', $file->getFilename())[0];
                $realPath = $file->getPathname();
                $userImagePhoto = \Illuminate\Support\Str::random(24) . '.' . $file->getExtension();

                $user = $users->where('name', '=', $userName)->first();

                if ($user) {
                    $file = File::copy($realPath, storage_path('app/uploads/user/imported-photos/' . $userImagePhoto));
                    $users->where('name', $userName)->first()->profile()->update([
                        'photo' => 'uploads/user/imported-photos/' . $userImagePhoto
                    ]);
                }
            }
        }


    }
    return redirect()->back()->with('success', "Imported");
})->name('import-image-upload');
