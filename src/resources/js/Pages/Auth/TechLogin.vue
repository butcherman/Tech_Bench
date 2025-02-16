<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
// import Card from "@/Components/_Base/Card.vue";
import { ref, reactive, onMounted } from "vue";

// TODO - Add Page.
const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex justify-center" style="border: 1px solid red">
        <div class="bg-white w-3/4 text-center">
            <h4 class="text-center">Login Page</h4>
            <BaseButton text="blah" href="/some/route" />
            <AddButton text="add something" href="/another/route" />
        </div>
    </div>
</template>
