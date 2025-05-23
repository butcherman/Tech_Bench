<div
    style="border-bottom: 1px solid #585555"
    class="mb-3 pb-3"
>
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-2">
                User Login Activity Report
            </h1>
            <h3 class="text-center">
                From
                {{ \Carbon\Carbon::parse($params['start_date'])->format('M d, Y') }}
                To
                {{ \Carbon\Carbon::parse($params['end_date'])->format('M d, Y') }}
            </h3>
        </div>
    </div>
</div>

@foreach ($data as $user => $logins)
    <div class="my-3">
        <x-base.card :title="$user">
            <div class="float-end">
                {{ count($logins) }} Entries
            </div>
            <table class="w-full table-fixed border-collapse">
                <thead>
                    <tr class="border">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Date
                        </th>
                        <th class="text-start p-2">
                            From IP Address
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($logins) === 0)
                        <tr class="border">
                            <td
                                colspan="2"
                                class="text-center"
                            >
                                No Login Data
                            </td>
                        </tr>
                    @endif
                    @foreach ($logins as $login)
                        <tr class="border">
                            <td class="border text-end p-2 max-w-1/2 w-1/3">
                                {{ $login['created_at'] }}
                            </td>
                            <td class="border p-2">
                                {{ $login['ip_address'] }}
                            </td>
                        </tr>
                    @endForEach
                </tbody>
            </table>
        </x-base.card>
    </div>
@endForEach
