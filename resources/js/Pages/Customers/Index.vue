<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div v-if="permissions.details.create" class="clearfix mb-2">
                                <Link
                                    as="button"
                                    :href="route('customers.create')"
                                    class="btn btn-pill btn-info float-end"
                                >
                                    <fa-icon icon="plus" />
                                    Add New Customer
                                </Link>
                            </div>
                            <Overlay :loading="loading">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>City</th>
                                            <th>Equipment</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input
                                                    id="cust-name"
                                                    v-model="searchParam.name"
                                                    type="search"
                                                    class="form-control"
                                                    placeholder="Search by Customer Name or ID"
                                                    @change="onSearch"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    id="cust-city"
                                                    v-model="searchParam.city"
                                                    type="search"
                                                    class="form-control"
                                                    placeholder="Search by City"
                                                    @change="onSearch"
                                                />
                                            </td>
                                            <td>
                                                <div class="input-group flex-nowrap">
                                                    <select
                                                        id="cust-equip"
                                                        v-model="searchParam.equip"
                                                        class="form-select"
                                                        @change="onSearch"
                                                    >
                                                        <option
                                                            value="null"
                                                            disabled
                                                            selected
                                                            hidden
                                                        >
                                                            Search by Equipment
                                                        </option>
                                                        <optgroup
                                                            v-for="(equipList, cat) in equipment"
                                                            :key="cat"
                                                            :label="cat.toString()"
                                                        >
                                                            <option v-for="equip in equipList">
                                                                {{ equip }}
                                                            </option>
                                                        </optgroup>
                                                    </select>
                                                    <span
                                                        class="input-group-text pointer"
                                                        title="Search"
                                                        v-tooltip
                                                        @click="onSearch"
                                                    >
                                                        <fa-icon icon="fa-brands fa-searchengin" />
                                                    </span>
                                                    <span
                                                        class="input-group-text pointer"
                                                        title="Clear Search"
                                                        v-tooltip
                                                        @click="resetSearch"
                                                    >
                                                        <fa-icon icon="fa-xmark" />
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="loading">
                                            <tr v-for="i in searchParam.perPage" :key="i">
                                                <td class="placeholder-glow">
                                                    <span
                                                        class="placeholder"
                                                        :class="`col-${Math.floor(Math.random() * (10 - 3 + 1) + 3)}`"
                                                    />
                                                </td>
                                                <td class="placeholder-glow">
                                                    <span
                                                        class="placeholder"
                                                        :class="`col-${Math.floor(Math.random() * (10 - 3 + 1) + 3)}`"
                                                    />
                                                </td>
                                                <td class="placeholder-glow">
                                                    <span
                                                        class="placeholder"
                                                        :class="`col-${Math.floor(Math.random() * (10 - 3 + 1) + 3)}`"
                                                    />
                                                </td>
                                            </tr>
                                        </template>
                                        <template v-else-if="searchResults.length === 0">
                                            <tr>
                                                <td colspan="3" class="text-center">
                                                    No Results Found
                                                </td>
                                            </tr>
                                        </template>
                                        <template v-else>
                                            <tr
                                                v-for="customer in searchResults"
                                                :key="customer.cust_id"
                                                class="pointer"
                                            >
                                                <td>
                                                    <Link
                                                        :href="route('customers.show', customer.slug)"
                                                        class="d-block text-decoration-none text-muted"
                                                    >
                                                        {{ customer.name }}
                                                        <span v-if="customer.dba_name">
                                                            {{ customer.dba_name }}
                                                        </span>
                                                    </Link>
                                                </td>
                                                <td>
                                                    <Link
                                                        :href="route('customers.show', customer.slug)"
                                                        class="d-block text-decoration-none text-muted"
                                                    >
                                                        {{ customer.city }}
                                                    </Link>
                                                </td>
                                                <td>
                                                    <Link
                                                        :href="route('customers.show', customer.slug)"
                                                        class="d-block text-decoration-none text-muted"
                                                    >
                                                        <template v-if="customer.customer_equipment.length > 0">
                                                            <div v-for="equip in customer.customer_equipment">
                                                                {{ equip.name }}
                                                            </div>
                                                        </template>
                                                        <template v-if="customer.parent_equipment.length > 0">
                                                            <div v-for="equip in customer.parent_equipment">
                                                                <fa-icon icon="share" />
                                                                {{ equip.name }}
                                                            </div>
                                                        </template>
                                                    </Link>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3">
                                                <div class="row m-0">
                                                    <div class="col-auto">
                                                        Per Page:
                                                        <select
                                                            id="res-per-page"
                                                            @change="onSearch"
                                                            v-model="searchParam.perPage"
                                                        >
                                                            <option v-for="num in paginationArr">{{ num }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <ul class="pagination justify-content-center">
                                                            <li
                                                                class="page-item"
                                                                :class="{ disabled : paginationData.currentPage === 1 }"
                                                                @click="onGoToPage(1)"
                                                            >
                                                                <span class="page-link pointer" title="First Page" v-tooltip>
                                                                    <fa-icon icon="fa-angles-left" />
                                                                </span>
                                                            </li>
                                                            <li
                                                                class="page-item"
                                                                :class="{ disabled : paginationData.currentPage === 1 }"
                                                                @click="onGoToPage(paginationData.currentPage - 1)"
                                                            >
                                                                <span class="page-link pointer" title="Next Page" v-tooltip>
                                                                    <fa-icon icon="fa-angle-left" />
                                                                </span>
                                                            </li>
                                                            <li
                                                                v-for="page in paginationData.pageArr"
                                                                class="page-item"
                                                                :class="{ active : paginationData.currentPage === page }"
                                                                @click="onGoToPage(page)"
                                                            >
                                                                <span class="page-link pointer">
                                                                    {{ page }}
                                                                </span>
                                                            </li>
                                                            <li
                                                                class="page-item"
                                                                :class="{ disabled : paginationData.currentPage === paginationData.numPages }"
                                                                @click="onGoToPage(paginationData.currentPage + 1)"
                                                            >
                                                                <span class="page-link pointer" title="Next Page" v-tooltip>
                                                                    <fa-icon icon="fa-angle-right" />
                                                                </span>
                                                            </li>
                                                            <li
                                                                class="page-item"
                                                                :class="{ disabled : paginationData.currentPage === paginationData.numPages }"
                                                                @click="onGoToPage(paginationData.numPages)"
                                                            >
                                                                <span class="page-link pointer" title="Last Page" v-tooltip>
                                                                    <fa-icon icon="fa-angles-right" />
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-auto">
                                                        Showing
                                                        {{ paginationData.listFrom }} -
                                                        {{ paginationData.listTo }} of
                                                        {{ paginationData.listTotal }}
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </Overlay>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import axios                        from 'axios';
    import App                          from '@/Layouts/app.vue';
    import Overlay                      from '@/Components/Base/Overlay.vue';
    import { ref, reactive, onMounted } from 'vue';
    import { performCustomerSearch }    from '@/Modules/Customers/customers.module';
    import { customerPaginationType,
             customerPermissionType,
             customerSearchParamType,
             customerType }             from '@/Types';

    const props = defineProps<{
        perPage      : number;
        paginationArr: number[];
        permissions  : customerPermissionType;
        equipment    : {
            [key:string]:string[];
        };
    }>();

    onMounted(() => onSearch());

    const loading       = ref<boolean>(false);
    const searchResults = ref<customerType[]>([]);
    const searchParam   = reactive<customerSearchParamType>({
        //  Search data
        name     : '',
        city     : '',
        equip    : null,
        //  Pagination and sort paramaters
        page     : 1,
        perPage  : props.perPage,
        sortField: 'name',
        sortType : 'asc',
    });
    const paginationData = reactive<customerPaginationType>({
        currentPage: 0,
        numPages   : 0,
        listFrom   : 0,
        listTo     : 0,
        listTotal  : 0,
        pageArr    : [1],
    });

    /**
     * Search for a customer or group of customers
     */
    const onSearch = async () => {
        loading.value = true;

        const results = await performCustomerSearch(searchParam);

        searchResults.value        = results.data;
        paginationData.listFrom    = results.listFrom;
        paginationData.listTo      = results.listTo;
        paginationData.listTotal   = results.listTotal;
        paginationData.numPages    = results.numPages;
        paginationData.currentPage = results.currentPage;
        paginationData.pageArr     = results.pageArr;

        loading.value = false;
    }

    /**
     * Clear the search paramaters
     */
    const resetSearch = () => {
        searchParam.name  = '';
        searchParam.city  = '';
        searchParam.equip = null;
        onSearch();
    }

    /**
     * Move to another page in the search results
     */
    const onGoToPage = (page:number) => {
        searchParam.page = page;
        onSearch();
    }
</script>

<script lang="ts">
    export default { layout: App }
</script>

<style scoped lang="scss">
    thead, tfoot {
        tr {
            background-color: rgb(233, 225, 225);
        }
    }
</style>
