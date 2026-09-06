<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import GenerateCsrForm from "@/features/administration/forms/GenerateCsrForm.vue";
import { computed } from "vue";

const props = defineProps<{
    csrRequest?: string;
}>();

const cardTitle = computed(() =>
    props.csrRequest ? "CSR Request" : "Generate CSR",
);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex flex-col gap-2 justify-center items-center">
        <Card :title="cardTitle" size="lg">
            <div v-if="csrRequest" class="flex justify-center">
                <div>
                    <pre>{{ csrRequest }}</pre>
                </div>
            </div>
            <GenerateCsrForm v-else />
        </Card>
        <Card size="md">
            <h5 class="text-center text-danger">IMPORTANT NOTE:</h5>
            <h6 class="text-center text-danger">
                Submitting a new CSR will remove the existing private key and
                Generate a new one.
            </h6>
        </Card>
    </div>
</template>
