<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import okModal from "@/core/composables/okModal";
import verifyModal from "@/core/composables/verifyModal";
import AppLayout from "@/layouts/AppLayout.vue";
import { ref } from "vue";

const modalOpen = ref(false);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex gap-2">
        <h2 class="pb-2">Dashboard</h2>
        <BaseButton text="open modal" @click="modalOpen = !modalOpen" />
        {{ modalOpen }}
        <!-- <VerifyModal title="Please Verify" message="Are You Sure?" /> -->

        <BaseButton
            text="verify"
            @click="verifyModal().then((res) => console.log(res))"
        />

        <BaseButton
            text="ok"
            @click="
                okModal('This is a message', { forceOk: true }).then((res) =>
                    console.log(res),
                )
            "
        />
    </div>
</template>
