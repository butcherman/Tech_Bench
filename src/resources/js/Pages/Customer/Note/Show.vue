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
                                <Link
                                    :href="
                                        $route('customers.notes.edit', [
                                            customer.slug,
                                            note.note_id,
                                        ])
                                    "
                                >
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
import { ref, reactive, onMounted } from "vue";
import { customer, siteList } from "@/State/CustomerState";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    note: customerNote;
}>();

const deleteNote = () => {
    verifyModal("Do you want to delete this note?").then((res) => {
        if (res) {
            router.delete(
                route("customers.notes.destroy", [
                    customer.value.slug,
                    props.note.note_id,
                ])
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
