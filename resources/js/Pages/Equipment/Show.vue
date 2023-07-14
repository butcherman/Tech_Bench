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
        <div class="row justify-content-center">
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
                                v-for="cust in equipment.customer"
                                :key="cust.cust_id"
                                class="list-group-item text-center"
                            >
                                <Link
                                    :href="$route('customers.show', cust.slug)"
                                    class="btn btn-info w-75"
                                >
                                    {{ cust.name }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Tech Tips with {{ equipment.name }}
                        </div>
                        <div
                            v-if="!equipment.tech_tip.length"
                            class="text-center"
                        >
                            No Tech Tip References
                        </div>
                        <ul class="list-group">
                            <li
                                v-for="tip in equipment.tech_tip"
                                :key="tip.tip_id"
                                class="list-group-item text-center"
                            >
                                <Link
                                    :href="$route('tech-tips.show', tip.slug)"
                                    class="btn btn-info w-75"
                                >
                                    {{ tip.subject }}
                                </Link>
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
    equipment: equipWithReferences;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
