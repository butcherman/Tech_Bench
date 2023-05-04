<template>
    <div>
        <button
            v-if="canLink"
            class="btn btn-info w-75 my-1"
            @click="createLink"
        >
            Link Customer
        </button>
        <button
            v-if="isLinked"
            class="btn btn-info w-75 my-1"
            @click="dissolveLink"
        >
            Unlink Customer
        </button>
    </div>
</template>

<script setup lang="ts">
import { computed, inject } from "vue";
import { customerSearchBox } from "@/Modules/customerSearchBox.module";
import { useForm } from "@inertiajs/vue3";
import { verifyModal } from "@/Modules/verifyModal.module";
import { customerKey, toggleManageLoadKey } from "@/SymbolKeys/CustomerKeys";
import type { Ref } from "vue";
import type { customerType } from "@/Types";
import type { InertiaForm } from "@inertiajs/vue3";

type linkFormType = {
    cust_id: number | undefined;
    parent_id: number | null;
    add: boolean;
};

const customer = inject(customerKey) as Ref<customerType>;
const toggleLoad = inject(toggleManageLoadKey) as (set: boolean) => void;

//  Determine if the customer can be linked to a parent or not
const canLink = computed(() => {
    return !(
        customer?.value.parent_id ||
        (customer?.value.child_count && customer?.value.child_count > 0)
    );
});
//  Is the customer already linked to a parent?
const isLinked = computed(() => {
    return customer?.value.parent_id !== null;
});

/**
 * Create a link between this customer and a "parent" customer
 */
const createLink = () => {
    customerSearchBox().then((res: customerType) => {
        const formData = useForm({
            cust_id: customer?.value.cust_id,
            parent_id: (res as customerType).cust_id,
            add: true,
        });

        processLink(formData);
    });
};

/**
 * Remove the link between this customer and a parent customer
 */
const dissolveLink = () => {
    verifyModal(
        "All shared data will no longer be attached to this customer"
    ).then((res) => {
        if (res) {
            const formData = useForm({
                cust_id: customer?.value.cust_id,
                parent_id: null,
                add: false,
            });

            processLink(formData);
        }
    });
};

/**
 * Process the add/remove link
 */
const processLink = (formData: InertiaForm<linkFormType>) => {
    toggleLoad(true);
    formData.post(route("customers.set-link"), {
        only: ["customer", "flash"],
        onFinish: () => {
            toggleLoad(false);
        },
    });
};
</script>
