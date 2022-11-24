<template>
    <Teleport to="body">
        <div
            ref="myModal"
            id="myModal"
            class="modal fade"
            tabindex="-1"
            aria-labelledby=""
            aria-hidden="true"
        >
            <div
                class="modal-dialog"
                :class="{ 'modal-lg' : size === 'lg',
                          'modal-xl' : size === 'xl',
                          'modal-sm' : size === 'sm' }"
            >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ title }}</h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        />
                    </div>
                    <div class="modal-body">
                        <slot name="default" />
                    </div>
                    <div class="modal-footer">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
    import { onMounted, ref } from "vue";
    import { Modal }          from "bootstrap";

    defineProps<{
        title?: string;
        size ?: 'sm' | 'lg' | 'xl';
    }>();

    const emit = defineEmits(['hide', 'hidden']);

    const myModal = ref<InstanceType<typeof Modal> | null>(null);
    let thisModalObj:Modal;

    onMounted(() => {
        thisModalObj = new Modal(myModal.value);
    });

    function _show():void
    {
        thisModalObj.show();
    }

    function _hide():void
    {
        thisModalObj.hide();
        emit('hide');
    }

    defineExpose({ show: _show, hide: _hide });
</script>
