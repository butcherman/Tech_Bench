<template>
    <b-button variant="danger" size="sm" block pill title="Manage This Tech Tip" v-b-tooltip.hover v-b-modal.manage-tip-modal>
        <i class="fas fa-toolbox"></i>
        Manage
        <b-modal id="manage-tip-modal" title="Manage Tech Tip" @show="getDetails">
            <b-overlay :show="loading">
                <template #overlay>
                    <atom-loader></atom-loader>
                </template>
                <div class="row justify-content-center">
                    <div class="col-md-10" v-if="permissions.manage">
                        <h4 class="text-center">Details</h4>
                        <b-table stacked :items="table.items"></b-table>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4" v-if="permissions.edit">
                        <inertia-link as="b-button" :href="route('tech-tips.edit', tipId)" block variant="warning">Edit Tip</inertia-link>
                    </div>
                    <div class="col-md-4" v-if="permissions.delete">
                        <b-button block variant="danger" @click="verifyDelete">Delete Tip</b-button>
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
                type: Object,
                required: true,
            },
            tipId: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                loading: false,
                table: {
                    items: [{}]
                }
            }
        },
        methods: {
            getDetails()
            {
                this.loading = true;
                axios.get(this.route('tips.get-details', this.tipId))
                    .then(res => {
                        this.table.items = [res.data];
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            verifyDelete()
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
                            axios.delete(this.route('tech-tips.destroy', this.tipId))
                                .then(res => {
                                    this.$inertia.get(route('tech-tips.index'));
                                }).catch(error => this.eventHub.$emit('axiosError', error));
                        }
                    });
            }
        },
    }
</script>
