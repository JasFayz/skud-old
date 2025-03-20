<?php

namespace Modules\Skud\Http\Controllers\API;

use App\Exports\TerminalRequestLogExport;
use App\Http\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Modules\Skud\Action\ChangeAllTerminalBackgroundAction;
use Modules\Skud\Action\ChangeAllTerminalLogoAction;
use Modules\Skud\Action\ChangeTerminalBackgroundAction;
use Modules\Skud\Action\ChangeTerminalLogoAction;
use Modules\Skud\Action\DeleteUserFromTerminalAction;
use Modules\Skud\Action\ExportUserFromTerminalAction;
use Modules\Skud\Action\GetTerminalInfoAction;
use Modules\Skud\Action\GetTerminalUserListAction;
use Modules\Skud\Action\SyncTimeTerminalAction;
use Modules\Skud\Action\Terminal\CreateTerminalAction;
use Modules\Skud\Action\Terminal\DeleteTerminalAction;
use Modules\Skud\Action\Terminal\DeleteZoneAction;
use Modules\Skud\Action\Terminal\UpdateTerminalAction;
use Modules\Skud\Action\Terminal\UpdateZoneAction;
use Modules\Skud\DTOs\TerminalDTO;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\TerminalActionLog;
use Modules\Skud\Entities\TerminalRequestLog;
use Modules\Skud\Entities\TerminalUserIdentifier;
use Modules\Skud\FilterData\TerminalFilterData;
use Modules\Skud\Http\Requests\TerminalRequest;
use Modules\Skud\Services\TerminalService;
use Modules\Skud\Transformers\TerminalRequestLogByPinfl;
use Modules\Skud\ViewModels\TerminalViewModel;
use Modules\User\Entities\User;

class TerminalController extends Controller
{

    public function __construct(protected TerminalService $terminalService)
    {
        $this->middleware('permission:terminal.indexWeb', ['only' => 'indexWeb|logsWeb']);
        $this->middleware('permission:terminal.create', ['only' => 'store']);
        $this->middleware('permission:terminal.update', ['only' => 'update|syncTime']);
        $this->middleware('permission:terminal.delete', ['only' => 'delete']);
    }

    public function indexWeb()
    {
        return view('admin::terminal');
    }

    public function logsWeb()
    {
        return view('admin::logs');
    }

    public function getTerminals(Request $request)
    {

        $filter = new TerminalFilterData(
            name: $request->query('name'),
            mode: $request->query('mode') ? (bool)filter_var($request->query('mode'), FILTER_VALIDATE_BOOLEAN) : null,
            ip: $request->query('ip'),
            serial_number: $request->query('serial_number')
        );

        $terminalView = new TerminalViewModel($filter);

        return $terminalView->getPaginate();
    }

    public function getAll()
    {
        $filter = new TerminalFilterData(
            status: Terminal::STATUS_ACTIVE
        );
        $terminalView = new TerminalViewModel($filter);

        return $terminalView->getAll();
    }

    public function show(Terminal $terminal)
    {
        return view('admin::terminal-show', compact('terminal'));
    }

    public function store(TerminalRequest $request, CreateTerminalAction $createTerminalAction)
    {
        $dto = TerminalDTO::fromRequest($request);

        return ResponseHelper::handle($createTerminalAction($dto));
    }

    public function update(TerminalRequest $request, Terminal $terminal, UpdateTerminalAction $updateTerminalAction)
    {
        $dto = TerminalDTO::fromRequest($request);
        return ResponseHelper::handle($updateTerminalAction($terminal, $dto));
    }

    public function destroy(Terminal $terminal, DeleteTerminalAction $deleteTerminalAction)
    {
        return ResponseHelper::handle($deleteTerminalAction($terminal));
    }

    public function syncTime(Terminal $terminal, SyncTimeTerminalAction $syncTimeTerminalAction)
    {
        return $syncTimeTerminalAction->execute($terminal, now());
    }

    public function getRequestLogs(Request $request)
    {
        $terminal_id = $request->get('terminal_id');
        $date = $request->get('date');
        $fio = $request->get('fio');
        $terminalRequestLog = TerminalRequestLog::query()
            ->with(['terminal' => function ($query) {
                $query->select('id', 'name', 'mode', 'deleted_at');
            }, 'identification', 'identification.identifiable'])
            ->filterByTerminalId($terminal_id)
            ->filterByDate($date)
            ->filterByFio($fio)
            ->latest();

        return $terminalRequestLog->paginate();
    }

    public function getActionLogs()
    {
        return TerminalActionLog::query()
            ->with(['terminal', 'identification', 'identification.identifiable'])
            ->latest()
            ->paginate(20);
    }

    public function getUserListFromTerminal(Terminal                  $terminal,
                                            GetTerminalUserListAction $getTerminalUserListAction, Request $request)
    {
        return $getTerminalUserListAction->execute($terminal, $request->query('page'), $request->query('user_name'));
    }

    public function deleteUserFromTerminal(Terminal $terminal, $terminalUserID, Request $request, DeleteUserFromTerminalAction $deleteUserFromTerminalAction)
    {
        $uuid = $request->get('uuid');
        $identifier = TerminalUserIdentifier::where('identifier_number', '=', $uuid)->first();

        $response = $deleteUserFromTerminalAction->execute($terminal, $terminalUserID);

        return $response;
    }


    public function storeLog(Request $request)
    {
        return $this->terminalService->storeLog($request->all());
    }

    public function exportTerminalUsers(Terminal                     $terminal,
                                        GetTerminalInfoAction        $getTerminalInfoAction,
                                        ExportUserFromTerminalAction $exportUserFromTerminalAction)
    {
        $terminalStatus = $getTerminalInfoAction->handle($terminal);

        if (!$terminalStatus->success) {
            throw new \Exception('Не получилось соединиться с терминалом');
        }

        $filename = 'users_ft-' . $terminal->ip . '_' . now()->toDateString() . '.zip';

        return response()->streamDownload(function () use ($exportUserFromTerminalAction, $terminal) {
            $response = $exportUserFromTerminalAction->handle($terminal);
            echo $response->body();
        }, $filename, ['Content-Type' => 'application/zip', 'FileName' => $filename]);

    }

    public function exportRequestLog(Request $request)
    {
        $date = Carbon::parse($request->get('date'));

        $terminalRequestLogs = TerminalRequestLog::query()
            ->select('id', 'date', 'terminal_id', 'identifier_number', 'terminal_mode')
            ->with(['identification', 'identification.identifiable' => function ($q) {
                return $q->select('id', 'name', 'email');
            }])
            ->whereHas('identification', function ($q) {
                return $q->where('model_type', '=', User::class);
            })
            ->filterByDate($date)
            ->latest()
            ->get();

        return (new TerminalRequestLogExport($date))->download('terminal_log_' . $request->get('date') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);

    }


    public function getRequestLogByPinfl(Request $request)
    {
        if (!$request->hasHeader('TOKEN')) {
            abort(403, 'Token is missing');
        }


        if ($request->header('TOKEN') !== env('ATTENDANCE_REQUEST_CLIENT')) {
            abort(403, 'Token doesn\'t match');
        }

        $data = $request->validate([
            'pinfl' => 'required',
            'date_from' => ['required', 'date_format:Y-m-d H:i:s'],
            'date_to' => ['required', 'date_format:Y-m-d H:i:s', 'after:date_from']
        ]);

//        $date_from = Carbon::createFromFormat('Y-m-d', $data['date_from']);
//        $date_to = Carbon::createFromFormat('Y-m-d', $data['date_to']);
//        dd($date_from);
        return TerminalRequestLogByPinfl::collection(TerminalRequestLog::query()
            ->with(['identification', 'identification.identifiable'])
            ->wherePinfl($data['pinfl'])
//            ->whereBetween('date', [$date_from, $date_to])
            ->where('date', '>=', $data['date_from'])
            ->where('date', '<=', $data['date_to'] )
            ->latest()
            ->paginate());
    }

    public function changeTerminalBackground(Terminal                       $terminal,
                                             ChangeTerminalBackgroundAction $changeTerminalBackgroundAction,
                                             Request                        $request)
    {
        $request->validate([
            'background' => 'required|image'
        ]);
        return $changeTerminalBackgroundAction->execute($terminal, $request);
    }


    public function changeTerminalLogo(Terminal                 $terminal,
                                       ChangeTerminalLogoAction $changeTerminalLogoAction,
                                       Request                  $request)
    {
        $request->validate([
            'logo' => 'required|image'
        ]);
        return $changeTerminalLogoAction->execute($terminal, $request);
    }

    public function changeAllTerminalBackground(
        ChangeAllTerminalBackgroundAction $changeAllTerminalBackgroundAction,
        Request                           $request)
    {
        $request->validate([
            'background' => 'required|image'
        ]);
        return $changeAllTerminalBackgroundAction->execute($request);
    }

    public function changeAllTerminalLogo(
        ChangeAllTerminalLogoAction $changeAllTerminalLogoAction,
        Request                     $request)
    {
        $request->validate([
            'logo' => 'required|image'
        ]);
        return $changeAllTerminalLogoAction->execute($request);
    }


}
