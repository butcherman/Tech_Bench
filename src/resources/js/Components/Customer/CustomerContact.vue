<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Contacts:
                <CustomerContactCreate
                    v-if="permissions.contact.create"
                    class="float-end"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import CustomerContactCreate from "./CustomerContactCreate.vue";
import { permissions } from "@/State/CustomerState";
import { ref, reactive, onMounted } from "vue";

// const props = defineProps<{}>();
</script>
