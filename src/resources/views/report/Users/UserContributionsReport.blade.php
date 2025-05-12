<div
    style="border-bottom: 1px solid #585555"
    class="mb-3 pb-3"
>
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-2">
                User Contributions Report
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

@foreach ($data as $user => $contributions)
    <div class="my-3">
        <x-base.card
            :title="$user"
            class="tb-card"
        >
            <table class="w-full table-fixed border-collapse">
                <tbody>
                    <tr>
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Customer Notes Created:
                        </th>
                        <td>
                            {{ $contributions['CustomerNote@created_by'] }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Customer Notes Updated:
                        </th>
                        <td>
                            {{ $contributions['CustomerNote@updated_by'] }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Customer Files Created:
                        </th>
                        <td>
                            {{ $contributions['CustomerFile@user_id'] }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Tech Tips Created:
                        </th>
                        <td>
                            {{ $contributions['TechTip@user_id'] }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Tech Tips Updated:
                        </th>
                        <td>
                            {{ $contributions['TechTip@updated_id'] }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Tech Tip Comments:
                        </th>
                        <td>
                            {{ $contributions['TechTipComment@user_id'] }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </x-base.card>
    </div>
@endForEach
