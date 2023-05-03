<template>
    <div>
        <VueForm
            ref="customerSearchForm"
            :validation-schema="{}"
            :initial-values="{ search: initialSearch }"
            submit-text="Search"
            @submit="onSubmit"
        >
            <TextInput
                id="search"
                name="search"
                label="Customer Name or ID"
                focus
            />
        </VueForm>
        <div class="mt-3">
            <h6 v-if="isDirty" class="text-center">
                <span v-if="searchResults.length">Search Results</span>
                <span v-else>No Results Found</span>
            </h6>
            <ul class="list-group">
                <li
                    v-for="res in searchResults"
                    key="res.cust_id"
                    class="list-group-item pointer"
                    @click="selectCust(res)"
                >
                    {{ res.name }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup lang="ts">
    import VueForm                         from "@/Components/Base/VueForm.vue";
    import TextInput                       from "@/Components/Base/Input/TextInput.vue";
    import { ref, reactive, onMounted }    from 'vue';
    import { performCustomerSearch }       from "@/Modules/Customers/customers.module";
    import type { customerSearchParamType,
                  customerType }           from "@/Types";

    const emit  = defineEmits(['selected']);
    const props = defineProps<{
        initialSearch?: string;
    }>();

    const isDirty            = ref<boolean>(false);
    const searchResults      = ref<customerType[]>([]);
    const customerSearchForm = ref<InstanceType<typeof VueForm> | null>(null);
    const searchParam        = reactive<customerSearchParamType>({
        //  Search data
        name     : '',
        city     : '',
        equip    : null,
        //  Pagination and sort parameters
        page     : 1,
        perPage  : 10,
        sortField: 'name',
        sortType : 'asc',
    });

    /**
     * If there is an initial value passed to the search box, we will immediately perform search
     */
    onMounted(() => {
        if(props.initialSearch && props.initialSearch.length)
        {
            customerSearchForm.value?.onSubmit({ search: props.initialSearch });
        }
    });

    const onSubmit = async (form:{search:string}) => {
        searchParam.name = form.search;

        const results = await performCustomerSearch(searchParam);
        searchResults.value = results.data;

        customerSearchForm.value?.endSubmit();
    }

    const selectCust = (cust:customerType) => {
        emit('selected', cust);
    }
</script>
