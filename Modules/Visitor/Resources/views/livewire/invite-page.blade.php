<div>
    <div class="card">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h4>Приглашение</h4>


        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">QR</th>
                    <th scope="col">
                        <input class="form-control form-control-sm" type="text" placeholder="ФИО Гостя"
                               wire:model="filter_guest_name"/>
                    </th>
                    <th scope="col">
                        <input class="form-control form-control-sm" type="text" placeholder="Серия паспорта"
                               wire:model="filter_passport_number"/>
                    </th>
                    <th scope="col">
                        <input class="form-control form-control-sm" type="text" placeholder="Создатель"
                               wire:model="filter_creator"/>
                    </th>
                    <th scope="col">Начало</th>
                    <th scope="col">Конец</th>
                    <th scope="col">Ответственное лицо</th>
                    <th scope="col">Цель визита</th>
                    <th scope="col">Фото</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                @forelse($invites as $key => $invite)
                    <tr>
                        <th scope="row">{{ $invites->firstItem() + $key }}</th>
                        <th scope="row">
                            <a href="#" style="width: 50px; height: 50px">
                                {!! $invite->qr_code !!}
                            </a>
                        </th>
                        <td>{{ $invite->guest->full_name }}</td>
                        <td>{{ $invite->guest->passport_number }}</td>
                        <td>{{ $invite->creator->name }}</td>
                        <td>{{ $invite->start }}</td>
                        <td>{{ $invite->end }}</td>
                        <td>{{ $invite->responsibleUser->name }}</td>
                        <td>{{ $invite->targetUser->name }}</td>
                        <td>{{ $invite->hasPhoto }}</td>
                        <td>
                            @if($invite->status === 1)
                                <span class="badge bg-success text-white">Подтвержден</span>
                            @elseif($invite->status === 2)
                                <span class="badge bg-info text-white">Отклонен</span>
                            @elseif($invite->status === 3)
                                <span class="badge bg-danger text-white">Истек срок</span>
                            @elseif($invite->status === 4)
                                <span class="badge bg-primary text-white">Истек срок</span>
                            @else
                                <span class="badge bg-warning text-white">В ожидании</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="dropdown">
                                <a class="btn btn-primary btn-sm dropdown-toggle" href="#" type="button"
                                   data-bs-toggle="dropdown">...</a>
                                <div class="dropdown-menu">
                                    <ul class="link-list-opt">
                                        <li><a href="#"><span>Загрузить фото</span></a></li>
                                    </ul>
                                </div>
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11">
                            No Data
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>
