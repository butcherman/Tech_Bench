<template>
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
</template>

<script setup lang="ts">
import axios from "axios";
import { ref, inject } from "vue";
import { customerKey, toggleManageLoadKey } from "@/SymbolKeys/CustomerKeys";
import { router } from "@inertiajs/vue3";
import { verifyModal } from "@/Modules/verifyModal.module";
import type { Ref } from "vue";

const customer = inject(customerKey) as Ref<customer>;
const toggleLoad = inject(toggleManageLoadKey) as () => void;

const deletedItems = ref<deletedItemsCategory>();

const getDeletedItems = () => {
    toggleLoad();
    axios
        .get(route("customers.deleted-items", customer.value?.slug))
        .then((res) => {
            deletedItems.value = res.data;
            toggleLoad();
        });
};

const restoreDeletedItem = (
    group: keyof deletedItemsCategory,
    item: deletedItem
) => {
    toggleLoad();
    router.get(route(`customers.${group}.restore`, item.item_id), undefined, {
        preserveState: true,
        onSuccess: () => getDeletedItems(),
        onFinish: () => toggleLoad(),
    });
};

const destroyDeletedItem = (
    group: keyof deletedItemsCategory,
    item: deletedItem
) => {
    verifyModal("This Action Cannot Be Undone").then((res) => {
        if (res) {
            toggleLoad();
            router.delete(
                route(`customers.${group}.force-delete`, item.item_id),
                {
                    preserveState: true,
                    onSuccess: () => getDeletedItems(),
                    onFinish: () => toggleLoad(),
                }
            );
        }
    });
};

defineExpose({ getDeletedItems });
</script>
