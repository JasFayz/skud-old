<div>
    @if(!$createForm && !$updateForm)
        <div class="card">
            <div class="card-header bg-transparent d-flex justify-content-between">
                <h4>Гости</h4>

                <div class="d-flex align-items-center ml-auto">
                    <button class="btn btn-primary btn-sm"
                            wire:click="toggleForm('create')">
                        Создать
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th>Фото</th>
                        <th scope="col">
                            <input class="form-control" wire:model="filters_name" placeholder="ФИО"/>
                        </th>
                        <th scope="col">
                            <input type="text" class="form-control" wire:model="filters_company_name"
                                   placeholder="Организация"/>
                        </th>
                        <th scope="col">
                            <input type="text" class="form-control" wire:model="filters_passport_number"
                                   placeholder="Серия паспорта"/>
                        </th>
                        <th scope="col">
                            <input type="text" class="form-control" wire:model="filters_company_id"
                                   placeholder="Компания"/>
                        </th>
                        <th scope="col" class="text-center">Наличие фото</th>
                        <th scope="col" class="text-right">...</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse( $guests as $key => $guest)
                        <tr>
                            <td scope="row">{{ $guests->firstItem() + $key }}</td>
                            <td>
                                @if($guest->photo)
                                    <img src="{{ asset($guest->photo) }}"
                                         class="img img-circle " width="100" height="100"/>
                                @endif
                            </td>
                            <td>{{ $guest->name }}</td>
                            <td>{{ $guest->company_name }}</td>
                            <td>{{ $guest->passport_number }}</td>
                            <td>{{ $guest->company->name }}</td>
                            <td class="text-center">
                                @if($guest->photo)
                                    <em class="icon ni ni-check-circle text-success"></em>
                                @else
                                    <em class="icon ni ni-cross-circle text-danger"></em>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#removeModal_{{$guest->id}}">
                                    Удалить фото
                                </button>

                                <!-- Modal Content Code -->
                                <div class="modal fade" tabindex="-1" id="removeModal_{{$guest->id}}">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <em class="icon ni ni-cross"></em>
                                            </a>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Подтвердите действие</h5>
                                            </div>
                                            <div class="modal-body">
                                                <h6>Вы точно хотите удалить фото гостя - {{ $guest->full_name }}?</h6>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn" data-bs-dismiss="modal">
                                                    Нет
                                                </button>
                                                <button class="btn btn-danger"
                                                        type="button"
                                                        wire:click="removePhoto({{ $guest->id }})">
                                                    Да
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="text-center text-bold">
                            <td colspan="7">Нет записей</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                {{ $guests->links() }}
            </div>
        </div>
    @endif

    @if($createForm)
        <div class="card ">
            <form wire:submit.prevent="submitForm">
                <div class="card-header bg-transparent d-flex justify-content-between">
                    <h4>Создание гостя</h4>
                    <div class="d-flex ml-auto">
                        <button class="btn btn-sm mx-1" wire:click="toggleForm('create')">
                            Отменить
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm mx-1">
                            Сохранить
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="first_name">First Name</label>
                                <input type="text"
                                       class="form-control form-control-outlined @error('first_name') error  @enderror"
                                       name="first_name"
                                       wire:model="first_name"
                                       id="first_name">
                                @error('first_name')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input type="text" class="form-control form-control-outlined"
                                       wire:model="last_name"
                                       name="last_name"
                                       id="last_name">
                                @error('last_name')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="patronymic">Patronymic</label>
                                <input type="text" class="form-control form-control-outlined"
                                       wire:model="patronymic"
                                       name="patronymic"
                                       id="patronymic">
                                @error('patronymic')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="company_name">Company Name</label>
                                <input type="text" class="form-control form-control-outlined"
                                       wire:model="company_name"
                                       name="company_name"
                                       id="company_name">
                                @error('company_name')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="passport_number">Passport Number</label>
                                <input type="text" class="form-control form-control-outlined"
                                       wire:model="passport_number"
                                       name="passport_number"
                                       id="passport_number">
                                @error('passport_number')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="company_id">Company</label>
                                <select class="form-control form-control-outlined"
                                        wire:model="company_id"
                                        name="company_id"
                                        id="company_id">
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="phone_number">Phone Number</label>
                                <input type="text" class="form-control form-control-outlined"
                                       wire:model="phone_number"
                                       name="phone_number"
                                       id="phone_number">
                                @error('phone_number')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">

                            <div class="d-flex align-items-end">
                                <div class="object-fit mr-2">
                                    @if ($photo)
                                        <img src="{{ $photo->temporaryUrl() }}" width="70">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="customFileLabel">Guest Photo</label>
                                    <div class="form-file">
                                        <input type="file" class="form-file-input"
                                               wire:model="photo"
                                               name="photo"
                                               id="photo">
                                        <label class="form-file-label"
                                               for="photo">{{ $photo?->getClientOriginalName() ?? 'Choose photo' }} </label>
                                    </div>
                                    @error('company_id')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>



