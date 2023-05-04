<template>
    <button
        v-if="canDisable"
        class="btn btn-danger w-75 my-1"
        @click="disableCustomer"
    >
        Disable Customer
    </button>
</template>

<script setup lang="ts">
import { computed, inject } from "vue";
import { verifyModal } from "@/Modules/verifyModal.module";
import { router } from "@inertiajs/vue3";
import { customerKey, toggleManageLoadKey } from "@/SymbolKeys/CustomerKeys";
import type { customerType } from "@/Types";
import type { Ref } from "vue";

const customer = inject(customerKey) as Ref<customerType>;
const toggleLoad = inject(toggleManageLoadKey) as (set: boolean) => void;

const emit = defineEmits(['disabling']);

//  If the customer is linked to others, it cannot be disabled
const canDisable = computed(() => {
    return !(
        customer?.value.parent_id ||
        (customer?.value.child_count && customer?.value.child_count > 0)
    );
});

const disableCustomer = () => {
    verifyModal(
        "No one will be able to access this customer or their information"
    ).then((res) => {
        if (res) {
            toggleLoad(true);
            emit('disabling');
            router.delete(route("customers.destroy", customer?.value.slug));
        }
    });
};
</script>
