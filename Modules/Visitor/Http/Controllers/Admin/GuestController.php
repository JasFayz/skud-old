<?php

namespace Modules\Visitor\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Skud\Entities\Company;
use Modules\Skud\Entities\Zone;
use Modules\User\Entities\User;
use Modules\Visitor\Actions\Guest\CreateGuestAction;
use Modules\Visitor\Actions\Guest\DeleteGuestAction;
use Modules\Visitor\Actions\Guest\UpdateGuestAction;
use Modules\Visitor\DTOs\GuestDTO;
use Modules\Visitor\Entities\Guest;
use Modules\Visitor\Entities\Invite;
use Modules\Visitor\Http\Requests\CreateGuestRequest;
use Modules\Visitor\Http\Requests\UpdateGuestRequest;
use Modules\Visitor\Transformers\GuestResource;

class GuestController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:visitor.guest.index', ['only' => 'index']);
        $this->middleware('permission:visitor.guest.create', ['only' => 'create', 'store']);
        $this->middleware('permission:visitor.guest.update', ['only' => 'edit', 'update']);
        $this->middleware('permission:visitor.guest.delete', ['only' => 'destroy', 'removePhoto']);
    }

    public function index(Request $request)
    {
        $guests = Guest::query()
            ->select(
                'id',
                'first_name',
                'last_name',
                'full_name',
                'company_name',
                'passport_number',
                'phone_number',
                'photo',
                'company_id',
                'deleted_at',
                'created_by')
            ->with(['invites', 'company', 'creator'])
            ->whereGuestName($request->query('full_name'))
            ->whereCompanyName($request->query('company_name'))
            ->whereCompanyId($request->query('company_id'))
            ->wherePassportNumber($request->query('passport_number'))
            ->latest()
            ->showTrashed($request->query('show_trashed'))
            ->paginate();

        $users = User::query()->select('id', 'name')->get();
        $zones = Zone::query()->with('terminals')->get();

        return view('visitor::admin.guest.index', [
            'guests' => $guests,
            'companies' => Company::query()->get(),
            'users' => $users,
            'zones' => $zones,
            'filters' => $request->query()
        ]);
    }

    public function show(Guest $guest)
    {
        $invites = Invite::query()->where('guest_id', $guest->id)->paginate();

        return view('visitor::admin.guest.show', [
            'guest' => $guest->load('company', 'creator',
                'invites', 'invites.terminals', 'invites.attachedTerminals', 'invites.targetUser', 'invites.responsibleUser'),
            'invites' => $invites,

        ]);
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
            'data' => [],
            'success' => true,
            'message' => 'Успешно удален'
        ], 200);
    }

    public function removePhoto(Guest $guest)
    {
        if ($guest->photo) {
            $guest->update(['photo' => null]);
        }

        return new JsonResponse([
            'data' => $guest,
            'success' => true,
            'message' => 'Успешно удален'
        ], 200);
    }
}
