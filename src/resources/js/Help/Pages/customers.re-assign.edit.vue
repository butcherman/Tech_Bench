<template>
    <div class="text-center">
        <h5>Re-Assign Customer Site</h5>
        <p>
            If a Customer Site has been created under the wrong customer
            account, use this page to move that site to the proper customer
            account.
        </p>
        <p>
            Select the Customer and Site to move from, followed by the customer
            account to move to.
        </p>
        <p>
            <strong class="text-danger">Important Note</strong>
        </p>
        <p>
            Data that is shared between multiple sites for the original customer
            will not be moved over to the new customer. However, any data that
            is unique to the selected customer site will be moved over to the
            new customer account.
        </p>
        <p>
            If the original customer account only has a single site, that
            account will be disabled and no longer accessible.
        </p>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
