<template>
    <b-overlay :show="submitted">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Processing</h4>
        </template>
        <b-form @submit="validateForm" novalidate :validated="validated" ref="category-form">
            <b-form-group label="Category Name:" label-for="name">
                <b-form-input
                    id="name"
                    type="text"
                    v-model="form.name"
                    :state="valid.state.name"
                    required
                ></b-form-input>
                <b-form-invalid-feedback>
                    <div v-for="msg in valid.msg.name" :key="msg">
                        {{msg}}
                    </div>
                </b-form-invalid-feedback>
            </b-form-group>
            <form-submit
                button_text="Submit Category Information"
                :submitted="submitted"
            ></form-submit>
            <b-button variant="danger" block @click="deleteCategory" v-if="cat_info">Delete This Category</b-button>
        </b-form>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            cat_info: {
                type:     Object,
                required: false,
            },

        },
        data() {
            return {
                submitted: false,
                validated: false,
                form: {
                    name:   null,
                },
                valid: {
                    state: {
                        name: null,
                    },
                    msg: {
                        name: ['Please Enter A Name For This Category'],
                    }
                }
            }
        },
        mounted() {
             if(this.cat_info)
             {
                 this.form.name   = this.cat_info.name;
             }
        },
        methods: {
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['category-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                    this.submitted = true;
                    if(!this.cat_info)
                    {
                        axios.post(this.route('admin.equipment.categories.store'), this.form)
                            .then(res => {
                                location.href = this.route('admin.equipment.index');
                            }).catch(error => {
                                this.submitted = false;
                                if(error.response.status === 422)
                                {
                                    this.valid.msg = error.response.data.errors;
                                    this.valid.state.name = false;
                                }
                                else
                                {
                                    this.eventHub.$emit('axiosError', error)
                                }
                            });
                    }
                    else
                    {
                        axios.put(this.route('admin.equipment.categories.update', this.cat_info.cat_id), this.form)
                            .then(res => {
                                location.href = this.route('admin.equipment.index');
                            }).catch(error => {
                                this.submitted = false;
                                if(error.response.status === 422)
                                {
                                    this.valid.msg = error.response.data.errors;
                                    this.valid.state.name = false;
                                }
                                else
                                {
                                    this.eventHub.$emit('axiosError', error)
                                }
                            });
                    }
                }
            },
            deleteCategory()
            {
                this.$bvModal.msgBoxConfirm('Please confirm that you want to delete this category', {
                    title:          'THIS CANNOT BE UNDONE!!!',
                    size:           'sm',
                    buttonSize:     'sm',
                    okVariant:      'danger',
                    okTitle:        'YES',
                    cancelTitle:    'NO',
                    footerClass:    'p-2',
                    hideHeaderClose: false,
                    centered:        true
                })
                .then(value => {
                    if(value)
                    {
                        this.submitted = true;
                        axios.delete(this.route('admin.equipment.categories.destroy', this.cat_info.cat_id))
                            .then(res => {
                                if(res.data.success)
                                {
                                    location.href = this.route('admin.equipment.index');
                                }
                                else
                                {
                                    this.submitted = false;
                                    this.$bvModal.msgBoxOk('Unable to delete at this time.  This Category Has Equipment Assigned to it')
                                }
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                    }
                })
                .catch(error => {
                    this.eventHub.$emit('axiosError', error);
                });
            }
        }
    }
</script>
