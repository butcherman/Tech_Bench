<template>
    <div>
        <Card v-if="csrRequest" title="CSR Request" class="tb-card">
            <pre>{{ csrRequest }}</pre>
        </Card>
        <Card v-else title="Generate CSR" class="tb-card">
            <GenerateCsrForm />
        </Card>
        <Card class="tb-card mt-3">
            <h5 class="text-center text-danger">IMPORTANT NOTE:</h5>
            <h6 class="text-center text-danger">
                Submitting a new CSR will remove the existing private key and
                Generate a new one.
            </h6>
        </Card>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import GenerateCsrForm from "@/Forms/Admin/Config/GenerateCsrForm.vue";

defineProps<{
    csrRequest?: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
