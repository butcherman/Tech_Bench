<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <button
                    class="btn btn-sm"
                    title="Refresh Equipment"
                    v-tooltip
                    @click="refreshContacts"
                >
                    <fa-icon icon="fa-rotate" :spin="loading" />
                </button>
                Contacts:
                <NewContact v-if="permission?.contact.create" />
            </div>
            <Overlay :loading="loading">
                Show Contacts
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
    import Overlay                         from '@/Components/Base/Overlay.vue';
    import NewContact                      from './NewContact.vue';
    import { ref, provide, inject }        from 'vue';
    import { router }                     from '@inertiajs/vue3';
    import type { customerPermissionType } from '@/Types';

    const permission = inject<customerPermissionType>('permission');

    const loading    = ref(false);
    const toggleLoad = () => { loading.value = !loading.value }
    provide('toggleLoad', toggleLoad);

    const refreshContacts = () => {
        toggleLoad();
        router.get(route('customers.contacts.index'), {
            only: (['flash', 'contacts']),
        });
    }
</script>
