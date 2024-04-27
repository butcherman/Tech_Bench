<template>
    <div>
        <h5 class="text-center">Show Customer</h5>
        <p class="text-center">
            All Customer information is shown on this page. Each section is
            explained in detail below.
        </p>
        <ul class="list-group">
            <customerSiteQuickJump />
            <li class="list-group-item">
                <strong>Equipment</strong> -
                <customerSiteEquipmentList />
            </li>
            <customerSiteContacts />
            <li class="list-group-item">
                <strong>Notes</strong> -
                <customerSiteNotes />
            </li>
            <customerSiteFiles />
        </ul>
    </div>
</template>

<script setup lang="ts">
import customerSiteQuickJump from "../Components/customer.site.quickJump.vue";
import customerSiteEquipmentList from "./customer.site.equipmentList.vue";
import customerSiteContacts from "./customer.site.contacts.vue";
import customerSiteNotes from "./customer.site.notes.vue";
import customerSiteFiles from "./customer.site.files.vue";
</script>
