<template>
    <Teleport to="body">
        <div
            ref="myModal"
            id="myModal"
            class="modal fade"
            tabindex="-1"
            aria-labelledby=""
            aria-hidden="true"
        >
            <div
                class="modal-dialog"
            >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Search for Customer</h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        />
                    </div>
                    <div class="modal-body">
                        <VueForm
                            ref="customerSearchForm"
                            :validation-schema="{}"
                            :initial-values="{ search:initialSearch }"
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
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
    import VueForm                         from "@/Components/Base/VueForm.vue";
    import TextInput                       from "@/Components/Base/Input/TextInput.vue";
    import { Modal }                       from "bootstrap";
    import { performCustomerSearch }       from "@/Modules/Customers/customers.module";
    import { onMounted,
             onUnmounted,
             ref,
             reactive }                    from "vue";
    import type { customerSearchParamType,
                  customerType }           from "@/Types";

    const emit  = defineEmits(['hide', 'hidden', 'selected']);
    const props = defineProps<{
        initialSearch?: string;
    }>();

    let thisModalObj:Modal;

    const isDirty            = ref<boolean>(false);
    const searchResults      = ref<customerType[]>([]);
    const myModal            = ref<InstanceType<typeof Modal>   | null>(null);
    const customerSearchForm = ref<InstanceType<typeof VueForm> | null>(null);
    const searchParam        = reactive<customerSearchParamType>({
        //  Search data
        name     : '',
        city     : '',
        equip    : null,
        //  Pagination and sort paramaters
        page     : 1,
        perPage  : 10,
        sortField: 'name',
        sortType : 'asc',
    });

    onMounted(() => {
        window.addEventListener('hide.bs.modal',   () => emit('hide'));
        window.addEventListener('hidden.bs.modal', () => emit('hidden'));

        thisModalObj = new Modal(myModal.value);

        thisModalObj.show();
        customerSearchForm.value?.onSubmit({ search: props.initialSearch });
    });

    onUnmounted(() => {
        window.removeEventListener('hide.bs.modal',   () => emit('hide'));
        window.removeEventListener('hidden.bs.modal', () => emit('hidden'));
    });

    const onSubmit = async (form:{search:string}) => {
        searchParam.name = form.search;

        const results = await performCustomerSearch(searchParam);
        searchResults.value = results.data;

        customerSearchForm.value?.endSubmit();
    }

    const selectCust = (cust:customerType) => {
        emit('selected', cust);
        thisModalObj.hide();
    }
</script>
