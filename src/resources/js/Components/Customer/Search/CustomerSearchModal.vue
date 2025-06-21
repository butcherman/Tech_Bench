<script setup lang="ts">
import CustomerSearchForm from "@/Forms/Customer/CustomerSearchForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { computed, onMounted, useTemplateRef } from "vue";
import {
    searchResults,
    searchParams,
} from "@/Composables/Customer/CustomerSearch.module";

const emit = defineEmits<{
    selected: [customer];
}>();

const props = defineProps<{
    title?: string;
}>();

const modal = useTemplateRef("search-modal");
const modalTitle = computed(() => props.title ?? "Search for Customer");

onMounted(() => (searchParams.perPage = 10));

/**
 * Emit the customer profile that was selected
 */
const onSelected = (cust: customer): void => {
    emit("selected", cust);
    modal.value?.hide();
};

/**
 * Open Search Modal
 */
const show = (): void => {
    modal.value?.show();
};

defineExpose({
    show,
});
</script>

<template>
    <Modal ref="search-modal" :title="modalTitle">
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
