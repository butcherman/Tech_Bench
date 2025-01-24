<template>
    <div>
        <Card title="SSL Certificate" class="tb-card">
            <TableStacked :items="data" class="w-full" />
        </Card>
        <div class="w-1/2 mx-auto">
            <BaseButton
                text="Import Certificate"
                class="w-full my-2"
                variant="warning"
                :href="$route('admin.security.create')"
            />
            <BaseButton
                text="Generate CSR"
                class="w-full my-2"
                :href="$route('admin.security.edit', 'Generate-CSR')"
            />
            <DeleteButton
                class="w-full my-2"
                text="Delete Certificate"
                confirm-msg="This operation cannot be undone"
                confirm
                @accepted="deleteCert"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    data:
        | {
              is_valid: boolean;
              issuer: string;
              expires: string;
              signature: string;
              organization: string;
          }
        | boolean;
}>();

const deleteCert = () => {
    router.delete(route("admin.security.destroy", "destroy-cert"));
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
