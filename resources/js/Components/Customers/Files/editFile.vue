<template>
    <b-button pill variant="light" size="sm" @click="$refs['edit-file-modal'].show()" title="Edit File" v-b-tooltip.hover>
            <i class="fas fa-pencil-alt"></i>
            <b-modal
                ref="edit-file-modal"
                title="Edit File"
                hide-footer
            >
                <b-overlay :show="loading">
                    <template #overlay>
                        <form-loader></form-loader>
                    </template>
                    <ValidationObserver v-slot="{handleSubmit}">
                        <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                            <text-input
                                label="Name"
                                name="name"
                                v-model="form.name"
                                rules="required"
                                placeholder="Enter a Descriptive Name"
                            />
                            <dropdown-input
                                label="File Type"
                                name="type"
                                v-model="form.type"
                                rules="required"
                                :options="file_types"
                                value-field="description"
                                text-field="description"
                                placeholder="What Type of File Is This?"
                            />
                            <b-form-checkbox v-show="allow_share" v-model="form.shared" class="text-center" switch>Share File Across All Sites</b-form-checkbox>
                            <submit-button class="mt-2" button_text="Update File" :submitted="submitted"></submit-button>
                        </b-form>
                    </ValidationObserver>
                </b-overlay>
            </b-modal>
        </b-button>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            file: {
                type:     Object,
                required: true,
            },
            allow_share: {
                type:     Boolean,
                default:  false,
            }
        },
        data() {
            return {
                loading:   false,
                submitted: false,
                form:      {
                    cust_id: this.cust_id,
                    name:    this.file.name,
                    type:    this.file.file_type,
                    shared:  this.file.shared,
                }
            }
        },
        computed: {
            file_types() {
                return this.$page.props.file_types;
            }
        },
        methods: {
            submitForm()
            {
                this.loading   = true;
                this.submitted = true;

                this.$inertia.put(route('customers.files.update', this.file.cust_file_id), this.form, {
                    onFinish: () => {
                        this.loading   = false;
                        this.submitted = false;
                        this.$refs['edit-file-modal'].hide();
                    }
                });
            },
        },
    }
</script>
