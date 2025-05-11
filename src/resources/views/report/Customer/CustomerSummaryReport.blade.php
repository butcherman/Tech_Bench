<div
    style="border-bottom: 1px solid #585555"
    class="mb-3 pb-3"
>
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-2">
                Customer Summary Report
            </h1>
        </div>
    </div>
</div>

@foreach ($data as $customer)
    <div class="my-3">
        <x-base.card :title="$customer['name']">
            <table class="w-full table-fixed border-collapse">
                <tbody>
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Customer ID:
                        </th>
                        <td class="p-2">
                            {{ $customer['cust_id'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Total Sites:
                        </th>
                        <td class="p-2">
                            {{ $customer['sites'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Equipment Assigned:
                        </th>
                        <td class="p-2">
                            {{ $customer['equipment'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            # of Notes:
                        </th>
                        <td class="p-2">
                            {{ $customer['notes'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            # of Contacts:
                        </th>
                        <td class="p-2">
                            {{ $customer['contacts'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            # of Uploaded Files:
                        </th>
                        <td class="p-2">
                            {{ $customer['files'] }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </x-base.card>
    </div>
@endForEach
