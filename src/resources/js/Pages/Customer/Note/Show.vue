<template>
    <div>
        <Head :title="note.subject" />
        <div class="border-bottom border-secondary-subtle my-2">
            <CustomerDetails />
        </div>
        <div v-if="siteList.length" class="row justify-content-center my-2">
            <div class="col">
                <CustomerSiteList
                    title-text="This note is for the following sites"
                />
            </div>
        </div>
        <div class="row justify-content-center my-2">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <CustomerNoteDetails :note="note" is-expanded />
                        <div>
                            <div class="float-end">
                                <Link :href="getEditRoute()">
                                    <EditButton small pill />
                                </Link>
                                <DeleteButton small pill @click="deleteNote" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import CustomerNoteDetails from "@/Components/Customer/CustomerNoteDetails.vue";
import CustomerSiteList from "@/Components/Customer/CustomerSiteList.vue";
import EditButton from "@/Components/_Base/Buttons/EditButton.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import { ref, reactive, computed } from "vue";
import { customer, siteList, currentSite } from "@/State/CustomerState";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    note: customerNote;
    equipment?: customerEquipment;
}>();

const getEditRoute = () => {
    if (props.equipment) {
        return route("customers.equipment.notes.edit", [
            customer.value.slug,
            props.equipment.cust_equip_id,
            props.note.note_id,
        ]);
    }

    if (currentSite.value) {
        return route("customers.site.notes.edit", [
            customer.value.slug,
            currentSite.value.site_slug,
            props.note.note_id,
        ]);
    }

    return route("customers.notes.edit", [
        customer.value.slug,
        props.note.note_id,
    ]);
};

const deleteRoute = computed(() => {
    if (props.equipment) {
        return route("customers.equipment.notes.destroy", [
            customer.value.slug,
            props.equipment.cust_equip_id,
            props.note.note_id,
        ]);
    }

    if (currentSite.value) {
        return route("customers.site.notes.destroy", [
            customer.value.slug,
            currentSite.value.site_slug,
            props.note.note_id,
        ]);
    }

    return route("customers.notes.destroy", [
        customer.value.slug,
        props.note.note_id,
    ]);
});

const deleteNote = () => {
    verifyModal("Do you want to delete this note?").then((res) => {
        if (res) {
            router.delete(deleteRoute.value);
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
