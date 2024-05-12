<template>
    <div>
        <Head title="Generate CSR" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Generate CSR</div>
                        <div v-if="csrRequest">
                            <p class="text-center">
                                Copy and Paste the below CSR Request to a text
                                file
                            </p>
                            <pre>{{ csrRequest }}</pre>
                        </div>
                        <GenerateCsrForm v-else />
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center text-danger">
                            IMPORTANT NOTE: Submitting a new CSR will remove the
                            existing private key and Generate a new one.
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import GenerateCsrForm from "@/Forms/Admin/Config/GenerateCsrForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    csrRequest?: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
