<script setup lang="ts">
import BaseButton from "../_Base/Buttons/BaseButton.vue";
import ClipboardCopy from "../_Base/ClipboardCopy.vue";
import Modal from "../_Base/Modal.vue";
import { dataGet } from "@/Composables/axiosWrapper.module";
import { ref, useTemplateRef } from "vue";

const twoFaCodes = useTemplateRef("two-fa-recovery-codes");

/**
 * Open the Two FA Recovery Codes modal and fetch the codes.
 */
const recoveryCode = ref<string>();
const onGetRecoveryCodes = () => {
    dataGet(route("two-factor.secret-key")).then((res) => {
        if (res) {
            recoveryCode.value = res.data.secretKey;
            twoFaCodes.value?.show();
        }
    });
};
</script>

<template>
    <div>
        <BaseButton
            text="Show Recovery Codes"
            size="small"
            pill
            @click="onGetRecoveryCodes()"
        />
        <Modal ref="two-fa-recovery-codes">
            <h3 class="text-center">
                Store this code in a safe location, it can be used to recover
                your account if your Authenticator App is lost.
            </h3>
            <h5 class="text-center text-danger">
                {{ recoveryCode }}
                <ClipboardCopy :value="recoveryCode" />
            </h5>
        </Modal>
    </div>
</template>
