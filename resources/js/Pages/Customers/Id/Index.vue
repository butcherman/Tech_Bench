<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Change Customer ID</div>
                        <CustomerBasicSearch
                            @selected="selectCust"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App                   from '@/Layouts/app.vue';
    import CustomerBasicSearch   from '@/Components/Customer/CustomerBasicSearch.vue';
    import { Inertia }           from '@inertiajs/inertia';
    import type { customerType } from '@/Types';

    const selectCust = (cust:customerType) => {
        Inertia.visit(route('admin.cust.change_id.show', cust.slug));
    }
</script>

<script lang="ts">
    export default { layout: App }
</script>
