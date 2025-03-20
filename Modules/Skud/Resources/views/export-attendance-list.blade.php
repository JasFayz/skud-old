<div class="table-responsive">
    <table class="table  table-striped mt-0 position-relative" style="">
        <thead>
        <tr class="text-center m-2" >
            <td colspan="4"  style="border: 1px solid #000000; padding: 1px">
                Дата: {{\Carbon\Carbon::parse($attendances[0]['date'])->format('d-m-Y')}}
            </td>
        </tr>
        <tr>
            <th  class="text-center "
                 style="vertical-align: middle; position: sticky; top: 0; background: #fff;">
                #
            </th>
            <th  class="text-center "
                 style="vertical-align: middle; position: sticky;top: 0; background: #fff;">
                ФИО
            </th>
            <th  class="text-center "
                 style="vertical-align: middle; position: sticky;top: 0; background: #fff;">
                Прибывшее время
            </th>
            <th class="text-center "
                style="vertical-align: middle; position: sticky;top: 0; background: #fff;">
                Время ухода
            </th>

        </tr>
        </thead>
        <tbody>
        @foreach($attendances as $key => $user )
            <tr>
                <td>{{$loop->index + 1}}</td>
                <td>{{$user['name']}}</td>
                <td>{{$user['came_time']}}</td>
                <td style="border: 1px solid #00000">{{$user['left_time']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

