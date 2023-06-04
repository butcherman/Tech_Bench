<template>
    <Head title="Customers" />
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div
                            v-if="permissions.details.create"
                            class="clearfix mb-2"
                        >
                            <Link
                                as="button"
                                :href="$route('customers.create')"
                                class="btn btn-pill btn-info float-end"
                            >
                                <fa-icon icon="plus" />
                                Add New Customer
                            </Link>
                        </div>
                        <Overlay :loading="loading">
                            <table class="table table-striped table-hover">
                                <TableHead :equipment="equipment" />
                                <TableBodyLoading v-show="loading" />
                                <TableBody v-show="!loading" />
                                <TableFoot />
                            </table>
                        </Overlay>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import Overlay from "@/Components/Base/Overlay.vue";
import TableHead from "@/Components/Customer/SearchPage/TableHead.vue";
import TableFoot from "@/Components/Customer/SearchPage/TableFoot.vue";
import TableBody from '@/Components/Customer/SearchPage/TableBody.vue';
import TableBodyLoading from "@/Components/Customer/SearchPage/TableBodyLoading.vue";
import { onMounted } from "vue";
import { triggerSearch } from '@/State/Customer/SearchState';
import { loading } from '@/State/Customer/SearchState';

onMounted(() => triggerSearch());

const $route = route;
defineProps<{
    permissions: customerPermissions;
    equipment: {
        [key: string]: string[];
    };
}>();
</script>

<script lang="ts">
export default { layout: App };
</script>
