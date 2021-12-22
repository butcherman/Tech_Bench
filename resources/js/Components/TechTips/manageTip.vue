<template>
    <b-button class="mt-1" pill block variant="danger" size="sm" title="Manage Tech Tip" v-b-tooltip.hover v-b-modal.manage-tip-modal>
        <i class="fas fa-tasks"></i>
        Manage
        <b-modal id="manage-tip-modal" title="Manage Tech Tip" @show="getDetails" hide-footer>
            <b-overlay :show="loading">
                <template #overlay>
                    <atom-loader></atom-loader>
                </template>
                <div class="row justify-content-center">
                    <div class="col-md-10" v-if="permissions.manage">
                        <h4 class="text-center">Details</h4>
                        <b-table stacked :items="items"></b-table>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4" v-if="permissions.edit">
                        <inertia-link as="b-button" :href="route('tech-tips.edit', tip_id)" block variant="warning">Edit Tip</inertia-link>
                    </div>
                    <div class="col-md-4" v-if="permissions.delete">
                        <b-button block variant="danger" @click="deleteTip">Delete Tip</b-button>
                    </div>
                </div>
            </b-overlay>
        </b-modal>
    </b-button>
</template>

<script>
    export default {
        props: {
            permissions: {
                type:     Object,
                required: true,
            },
            tip_id: {
                type:     Number,
                required: true,
            },
        },
        data() {
            return {
                loading: false,
                items: [{}],
            }
        },
        methods: {
            getDetails()
            {
                this.loading = true;
                axios.get(route('tips.details', this.tip_id))
                    .then(res => {
                        this.items   = [res.data];
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            deleteTip()
            {
                this.$bvModal.msgBoxConfirm('Please Verify',
                    {
                        title:          'Are you sure you want to delete this Tech Tip?',
                        size:           'sm',
                        buttonSize:     'sm',
                        okVariant:      'danger',
                        okTitle:        'YES',
                        cancelTitle:    'NO',
                        footerClass:    'p-2',
                        hideHeaderClose: false,
                        centered:        true
                    }).then(value => {
                        if(value)
                        {
                            this.loading = true;
                            this.$inertia.delete(route('tech-tips.destroy', this.tip_id));
                        }
                    });
            }
        },
    }
</script>
