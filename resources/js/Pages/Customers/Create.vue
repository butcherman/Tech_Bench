<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <CreateCustomerForm
                            :select-id="selectId"
                            :default-state="defaultState"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import CreateCustomerForm from "@/Forms/Customer/CreateCustomerForm.vue";

defineProps<{
    selectId: boolean;
    defaultState: string;
}>();
</script>

<script lang="ts">
export default { layout: App };
</script>
