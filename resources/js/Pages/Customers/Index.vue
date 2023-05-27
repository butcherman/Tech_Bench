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
import { ref, reactive, onMounted, provide } from "vue";
import {performCustomerSearch} from '@/Modules/Customers/customerSearch.module';
import { customerSearchDataKey } from '@/SymbolKeys/CustomerKeys';

const $route = route;
const props = defineProps<{
    perPage: number;
    paginationArr: number[];
    permissions: customerPermissions;
    equipment: {
        [key: string]: string[];
    };
}>();

const searchParam = reactive<customerSearchParam>({
    //  Search data
    name: "",
    city: "",
    equip: null,
    //  Pagination and sort parameters
    page: 1,
    perPage: props.perPage,
    sortField: "name",
    sortType: "asc",
});
const paginationData = reactive<customerPagination>({
    currentPage: 0,
    numPages: 0,
    listFrom: 0,
    listTo: 0,
    listTotal: 0,
    pageArr: [1],
});

const triggerSearch = () => {
    console.log('trigger search', searchParam);
}

const resetSearch = () => {
    console.log('reset search');
}

provide(customerSearchDataKey, { searchParam, paginationData, triggerSearch, resetSearch });



</script>

<script lang="ts">
export default { layout: App };
</script>

<style scoped lang="scss">


tbody {
    tr {
        td {
            padding: 0;
            a {
                display: block;
                height: 100%;
                padding: 8px 0;
                text-decoration: none;
                color: #000000;
            }
        }
    }
}
</style>
