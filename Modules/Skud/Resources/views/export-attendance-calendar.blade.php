<div class="table-responsive">
    <table class="table  table-striped mt-0 position-relative" style="">
        <thead>
        <tr>
            <th rowspan="3" class="text-center "
                style="vertical-align: middle; position: sticky; top: 0; background: #fff;">
                #
            </th>
            <th rowspan="3" class="text-center "
                style="vertical-align: middle; position: sticky;top: 0; background: #fff;">
                ФИО
            </th>
            <th colspan="{{ count($attendances['date']) }}" class="text-center">Дата</th>
        </tr>
        <tr>
            @foreach($attendances['date'] as $key => $day)
                <th style=" position: sticky;top: 0; background: #fff; text-align: center" colspan="2">
                    {{ \Carbon\Carbon::parse($day)->format('d.m') }}
                </th>
            @endforeach
        </tr>
        <tr>

            @foreach($attendances['date'] as $key => $day)
                <th style="text-align: center">Приход</th>
                <th style="text-align: center">Уход</th>
            @endforeach

        </tr>
        </thead>
        <tbody>

        @foreach($attendances['users'] as $index => $user)

            <tr>
                <td>{{ ++$index }}</td>
                <td>{{ \Illuminate\Support\Str::upper($user->name) }}</td>
                @foreach($attendances['date'] as $day)
                    <td
                        {{--                    @class="{--}}
                        {{--                        'bg-danger': moment(day).isoWeekday() === 6 || moment(day).isoWeekday() === 7--}}
                        {{--                        }"--}}
                    >
                        @if(array_key_exists($day, $attendances['calendar'][$user->id]))
                            <span class="d-block"> {{
                                $attendances['calendar'][$user->id][$day]['came_time']
                            }}</span>
                        @else
                            @if($user->dayOffs()->count())
                                @foreach($user->dayOffs as $day_off)
                                    @if(\Carbon\Carbon::parse($day)->isBetween($day_off->from, $day_off->to))
                                        <div>
                                            {{ $day_off->type_symbol }}
                                        </div>
                                    @else
                                        <div v-else>-</div>
                                    @endif
                                @endforeach
                            @else
                                <span class="d-block">-</span>
                            @endif
                        @endif

                    </td>
                    <td>
                        @if(array_key_exists($day, $attendances['calendar'][$user->id]))
                            {{ $attendances['calendar'][$user->id][$day]['left_time']  }}
                        @else
                            @if($user->dayOffs()->count())
                                @foreach($user->dayOffs as $day_off)
                                    @if(\Carbon\Carbon::parse($day)->isBetween($day_off->from, $day_off->to))
                                        <div>
                                            {{ $day_off->type_symbol }}
                                        </div>
                                    @else
                                        <div v-else>-</div>
                                    @endif
                                @endforeach
                            @else
                                <span class="d-block">-</span>
                            @endif
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
