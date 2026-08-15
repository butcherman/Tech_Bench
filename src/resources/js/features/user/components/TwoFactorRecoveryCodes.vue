<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Overlay from "@/core/components/Overlay.vue";
import Modal from "@/core/components/Modal.vue";
import { ref } from "vue";
import { secretKey } from "@/wayfinder/routes/two-factor";
import { dataGet, isLoading } from "@/core/services/axiosWrapper";

interface RecoveryCode {
    secretKey: string;
}

const modalOpen = ref(false);
const recoveryCode = ref();
const onGetRecoveryCode = async () => {
    modalOpen.value = true;

    let code = await dataGet<RecoveryCode>(secretKey.url());
    console.log(code);
    recoveryCode.value = code?.secretKey;
};
</script>

<template>
    <div>
        <BaseButton
            text="Show Recovery Code"
            size="sm"
            pill
            @click="onGetRecoveryCode"
        />
        <Modal v-model:show="modalOpen" title="Two Factor Recovery Code">
            <Overlay :loading="isLoading">
                <h3 class="text-center">
                    Store this code in a safe location. It can be used to
                    recover your account if your Authenticator App is lost.
                </h3>
                <div class="flex justify-center">
                    <h5 class="text-center text-danger" v-copy="recoveryCode">
                        {{ recoveryCode }}
                    </h5>
                </div>
            </Overlay>
        </Modal>
    </div>
</template>
