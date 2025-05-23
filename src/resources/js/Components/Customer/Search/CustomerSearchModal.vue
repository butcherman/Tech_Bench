<script setup lang="ts">
import CustomerSearchForm from "@/Forms/Customer/CustomerSearchForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { onMounted, useTemplateRef } from "vue";
import {
    searchResults,
    searchParams,
} from "@/Composables/Customer/CustomerSearch.module";

const emit = defineEmits<{
    selected: [customer];
}>();

onMounted(() => (searchParams.perPage = 10));

const modal = useTemplateRef("search-modal");

const onSelected = (cust: customer) => {
    emit("selected", cust);
    modal.value?.hide();
};

const show = () => {
    modal.value?.show();
};

defineExpose({
    show,
});
</script>

<template>
    <Modal ref="search-modal" title="Search for Customer">
        <CustomerSearchForm class="md:min-w-96" hide-reset />
        <div v-if="searchResults.length" class="mt-3">
            <ResourceList :list="searchResults">
                <template #list-item="{ item }">
                    <div class="pointer" @click="onSelected(item)">
                        {{ item.name }}
                    </div>
                </template>
            </ResourceList>
        </div>
    </Modal>
</template>
