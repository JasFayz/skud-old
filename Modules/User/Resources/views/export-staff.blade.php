<style>
    table td {
        border: 1px solid black;
    }
</style>
<div class="table-responsive">
    <table class="table table-striped">
        <tr>
            <th>
                #
            </th>
            <th>Фио</th>
            <th>Фото</th>
            <th>ПИНФЛ</th>
            <th>Роль</th>
            <th colspan="{{ ++$maxAncestorCount }}">Департаменты</th>
            <th>Должность</th>
            <th>Почта</th>
            <th>Путь фото</th>
        </tr>

        @foreach($users as $key => $user)

            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->profile?->photo ? 1 : 0 }}</td>
                <td>{{ $user->pinfl }}</td>
                @if($user->role)
                    <td>{{ $user->role->name }}</td>
                @endif
                @if($user->profile?->department?->ancestors)
                    @foreach($user->profile->department?->ancestors as $node)
                        <td colspan="">
                            {{ $node->name }}
                        </td>
                    @endforeach
                    @for($i = 0; $i < ($maxAncestorCount - $user->profile?->department?->ancestors->count()-1); $i++)
                        <td>
                        </td>
                    @endfor
                    <td>
                        {{ $user->profile->department->name }}
                    </td>
                @else
                    @for($i = 0; $i < $maxAncestorCount; $i++)
                        <td>
                        </td>
                    @endfor
                @endif

                <td>
                    {{ $user->profile?->position?->name }}
                </td>
                <td>
                    {{ $user->email }}
                </td>
                <td>{{ $user->profile?->photo }}</td>

            </tr>

        @endforeach
    </table>
</div>
