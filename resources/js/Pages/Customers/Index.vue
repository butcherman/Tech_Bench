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
                                <TableBodyLoading v-if="loading" />
                                <TableBody :search-results="searchResults" v-else />
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
import { ref, reactive, onMounted, provide } from "vue";
import { performCustomerSearch } from "@/Modules/Customers/customerSearch.module";
import { customerSearchDataKey } from "@/SymbolKeys/CustomerKeys";

onMounted(() => triggerSearch());

const $route = route;
defineProps<{
    permissions: customerPermissions;
    equipment: {
        [key: string]: string[];
    };
}>();

const loading = ref<boolean>(false);
const searchResults = ref<customer[]>([]);
const searchParam = reactive<customerSearchParam>({
    //  Search data
    name: "",
    city: "",
    equip: null,
    //  Pagination and sort parameters
    page: 1,
    perPage: 25,
    sortField: "name",
    sortType: "asc",
});
const paginationData = reactive<customerPagination>({
    currentPage: 1,
    numPages: 1,
    listFrom: 0,
    listTo: 0,
    listTotal: 0,
    pageArr: [1],
});
const paginationArray = [25, 50, 100];

/**
 * Perform a customer search and parse results
 */
const triggerSearch = async (): Promise<void> => {
    loading.value = true;
    const results = await performCustomerSearch(searchParam);

    searchResults.value = results.data;
    paginationData.listFrom = results.listFrom;
    paginationData.listTo = results.listTo;
    paginationData.listTotal = results.listTotal;
    paginationData.numPages = results.numPages;
    paginationData.currentPage = results.currentPage;
    paginationData.pageArr = results.pageArr;

    loading.value = false;
};

/**
 * Clear search parameters
 */
const resetSearch = () => {
    console.log("reset search");
    searchParam.name = "";
    searchParam.city = "";
    searchParam.equip = null;

    triggerSearch();
};

provide(customerSearchDataKey, {
    searchParam,
    paginationData,
    paginationArray,
    triggerSearch,
    resetSearch,
});
</script>

<script lang="ts">
export default { layout: App };
</script>
