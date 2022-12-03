<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <button
                    class="btn btn-sm"
                    title="Refresh Equipment"
                    v-tooltip
                    @click="refreshEquipment"
                >
                    <fa-icon icon="fa-rotate" :spin="loading" />
                </button>
                Equipment:
                <NewEquipment v-if="permission?.equipment.create" />
            </div>
            <Overlay :loading="loading">
                <ShowEquipment />
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
    import Overlay                         from '@/Components/Base/Overlay.vue';
    import NewEquipment                    from '@/Components/Customer/Equipment/NewEquipment.vue';
    import ShowEquipment                   from '@/Components/Customer/Equipment/ShowEquipment.vue';
    import { ref, provide, inject }        from 'vue';
    import { Inertia }                     from '@inertiajs/inertia';
    import type { customerPermissionType } from '@/Types';

    const permission = inject<customerPermissionType>('permission');

    const loading    = ref(false);
    const toggleLoad = () => { loading.value = !loading.value }
    provide('toggleLoad', toggleLoad);

    const refreshEquipment = () => {
        toggleLoad();
        Inertia.get(route('customers.equipment.index'), {
            only: (['flash', 'equipment']),
        });
    }
</script>
