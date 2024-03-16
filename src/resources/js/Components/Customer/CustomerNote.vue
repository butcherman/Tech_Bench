<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Notes:
                <Link :href="addRoute">
                    <AddButton
                        v-if="permissions.notes.create"
                        class="float-end"
                        text="Add Note"
                        small
                        pill
                    />
                </Link>
            </div>
            <div v-if="!notes.length">
                <h6 class="text-center">No Notes</h6>
            </div>
            <div v-for="note in notes">{{ note }}</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AddButton from "../_Base/Buttons/AddButton.vue";
import { ref, computed, onMounted } from "vue";
import {
    customer,
    notes,
    permissions,
    currentSite,
} from "@/State/CustomerState";

// const props = defineProps<{}>();

const addRoute = computed(() =>
    currentSite.value
        ? route("customers.site-note.create", [
              customer.value.slug,
              currentSite.value.site_slug,
          ])
        : route("customers.notes.create", customer.value.slug)
);
</script>
