<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center">{{ equipment.name }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Customers with {{ equipment.name }}
                        </div>
                        <div
                            v-if="!equipment.customer.length"
                            class="text-center"
                        >
                            No Customer References
                        </div>
                        <ul class="list-group">
                            <li
                                v-for="customer in equipment.customer"
                                :key="customer.cust_id"
                                class="list-group-item"
                            >
                                <Link
                                    :href="
                                        $route('customers.show', customer.slug)
                                    "
                                    class="btn btn-info w-100"
                                    >{{ customer.name }}</Link
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps<{
    equipment: {
        customer: customer[];
        // techTip: techTip[];
    } & equipment;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
