<template>
    <Overlay :loading="loading">
        <div v-for="(group, index) in deletedItems" :key="index">
            <div v-if="group.length">
                <h5 class="text-center">Deleted {{ index }}</h5>
                <ul class="list-group">
                    <li
                        v-for="item in group"
                        class="list-group-item text-center"
                        :key="item.item_id"
                    >
                        <span
                            class="text-success pointer float-start me-2"
                            title="Restore"
                            v-tooltip
                            @click="restoreDeletedItem(index, item)"
                        >
                            <fa-icon icon="fa-trash-restore" />
                        </span>
                        <span
                            class="text-danger pointer float-start me-2"
                            title="Permanently Delete"
                            v-tooltip
                            @click="destroyDeletedItem(index, item)"
                        >
                            <fa-icon icon="fa-trash-can" />
                        </span>
                        {{ item.item_name }}
                        <span class="float-right text-muted">
                            Deleted: {{ item.item_deleted }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </Overlay>
</template>

<script setup lang="ts">
import axios from "axios";
import Overlay from "@/Components/Base/Overlay.vue";
import { ref, inject } from "vue";
import { customerKey } from "@/SymbolKeys/CustomerKeys";
import { router } from "@inertiajs/vue3";
import type { customerType } from "@/Types";
import type { Ref } from "vue";
import { verifyModal } from "@/Modules/verifyModal.module";

interface deletedItemType {
    item_id: number;
    item_name: string;
    item_deleted: string;
}

interface deletedItemsCategoryType {
    equipment: deletedItemType[];
    contacts: deletedItemType[];
}

const customer = inject(customerKey) as Ref<customerType>;
const loading = ref<boolean>(false);
const deletedItems = ref<deletedItemsCategoryType>();

const getDeletedItems = () => {
    loading.value = true;
    axios
        .get(route("customers.deleted-items", customer.value?.slug))
        .then((res) => {
            deletedItems.value = res.data;
            loading.value = false;
        });
};

const restoreDeletedItem = (
    group: keyof deletedItemsCategoryType,
    item: deletedItemType
) => {
    loading.value = true;
    router.get(route(`customers.${group}.restore`, item.item_id), undefined, {
        preserveState: true,
        onSuccess: () => getDeletedItems(),
        onFinish: () => (loading.value = false),
    });
};

const destroyDeletedItem = (
    group: keyof deletedItemsCategoryType,
    item: deletedItemType
) => {
    verifyModal("This Action Cannot Be Undone").then((res) => {
        if (res) {
            loading.value = true;
            router.delete(
                route(`customers.${group}.force-delete`, item.item_id),
                {
                    preserveState: true,
                    onSuccess: () => getDeletedItems(),
                    onFinish: () => (loading.value = false),
                }
            );
        }
    });
};

defineExpose({ getDeletedItems });
</script>
