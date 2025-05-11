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
    <div>
        @foreach ($data as $d)
            {{ var_dump($d) }}
        @endForEach
