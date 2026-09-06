<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import BannerAlert from "@/core/components/alerts/BannerAlert.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import TableStacked from "@/core/features/dataResources/TableStacked.vue";
import verifyModal from "@/core/features/verifyModal";
import { router } from "@inertiajs/vue3";
import { create, edit, destroy } from "@/wayfinder/routes/admin/security";

const props = defineProps<{
    certData: SslCertificateData | null;
}>();

const onDeleteCert = () => {
    verifyModal("This operation cannot be undone").then((res) => {
        if (res) {
            router.delete(destroy.url("destroy-cert"));
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex flex-col justify-center items-center gap-2">
        <Card size="md">
            <BannerAlert
                v-if="certData === null"
                variant="danger"
                text="NO SSL CERT FOUND"
            />
            <TableStacked :data="certData" class="w-full" />
        </Card>
        <Card size="md">
            <div class="flex flex-col gap-2">
                <BaseButton
                    text="Import Certificate"
                    variant="warning"
                    :href="create.url()"
                />
                <BaseButton
                    text="Generate CSR"
                    :href="edit.url('Generate-CSR')"
                />
                <BaseButton
                    v-if="certData !== null"
                    text="Delete Certificate"
                    variant="danger"
                    @click="onDeleteCert"
                />
            </div>
        </Card>
    </div>
</template>
