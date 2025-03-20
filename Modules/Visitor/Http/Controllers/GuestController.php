<?php

namespace Modules\Visitor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Visitor\Actions\Guest\CreateGuestAction;
use Modules\Visitor\Actions\Guest\DeleteGuestAction;
use Modules\Visitor\Actions\Guest\UpdateGuestAction;
use Modules\Visitor\DTOs\GuestDTO;
use Modules\Visitor\Entities\Guest;
use Modules\Visitor\Http\Requests\CreateGuestRequest;
use Modules\Visitor\Http\Requests\UpdateGuestRequest;

class GuestController extends Controller
{
    public function indexWeb()
    {
        return view('visitor::guest');
    }

    public function getGuests(Request $request)
    {
        $guests = Guest::query()
            ->with(['invites', 'company', 'creator'])
            ->whereGuestName($request->query('guest_name'))
            ->whereCompanyName($request->query('company_name'))
            ->wherePassportNumber($request->query('passport_number'))
//            ->company()
//            ->owner()
            ->latest()
            ->paginate();

        return $guests;
    }

    public function store(CreateGuestRequest $request, CreateGuestAction $createGuestAction)
    {

        $dto = GuestDTO::fromCreateRequest($request);

        $guest = $createGuestAction->handle($dto);

        return new JsonResponse([
            'data' => $guest,
            'success' => true,
            'message' => 'Успешно создан'
        ], 200);
    }

    public function update(Guest $guest, UpdateGuestRequest $request, UpdateGuestAction $updateGuestAction)
    {
        $guest = $updateGuestAction->handle($guest, GuestDTO::fromUpdateRequest($request));

        return new JsonResponse([
            'data' => $guest,
            'success' => true,
            'message' => 'Успешно обновлен'
        ], 200);
    }

    public function destroy(Guest $guest, DeleteGuestAction $deleteGuestAction)
    {
        $status = $deleteGuestAction->handle($guest);

        return new JsonResponse([
            'success' => $status,
            'message' => 'Успешно удален'
        ], 202);
    }

}
