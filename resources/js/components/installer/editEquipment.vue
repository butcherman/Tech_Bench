<template>
    <b-form @submit="submitForm" novalidate :validated="validated" ref="editEquipmentForm">
        <fieldset>
            <b-form-group label="System Name:" label-for="name" class="mt-2">
                <b-form-input id="name" name="name" v-model="form.name" type="text" required></b-form-input>
                <span class="invalid-feedback">You must enter a valid name</span>
            </b-form-group>
        </fieldset>
        <fieldset>
            <label>Customer Information to Gather</label>
            <div ref="container">


                <draggable
                    :list="form.dataOptions"
                    class="list-group"
                    ghost-class="ghost"
                    @start="dragging = true"
                    @end="dragging = false"
                >
                    <b-form-input
                        v-for="obj in form.dataOptions"
                        :key="obj.field_id" type="text"
                        :value="obj.name"
                        class="mb-2 pointer"
                        disabled
                    ></b-form-input>
                </draggable>
                <v-select
                    v-for="n in range"
                    :key="n"
                    :options="data_list"
                    label="name"
                    v-model="form.newOptions[n]"
                    placeholder="Select An Option"
                    class="mb-3"
                    taggable></v-select>
            </div>
            <span class="pointer float-right" @click="addRow">Add Row</span>
        </fieldset>
        <b-button type="submit" block variant="primary" :disabled="button.disable">
            <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
            {{button.text}}
        </b-button>
        <b-button block variant="danger" @click="deleteSystem">Delete {{sys_data.name}}</b-button>
        <b-modal id="loading-modal" size="sm" ref="loading-modal" hide-footer hide-header hide-backdrop centered>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </b-modal>
    </b-form>
</template>

<script>
export default {
    props: [
        'sys_data',
        'data_list',
    ],
    data() {
        return {
            validated: false,
            range: 1, // this.sys_data.system_data_fields_count + 1,
            dataTypes: [],
            form: {
                sys_id: this.sys_data.sys_id,
                name: this.sys_data.name,
                dataOptions: this.sys_data.system_data_fields,
                newOptions: [],
            },
            button: {
                text: 'Update Equipment',
                disable: false,
            },
            dragOptions: {
                animation: 200,
                ghostClass: "ghost"
            }
        }
    },
    created()
    {
        //
    },
    methods: {
        submitForm(e)
        {
            e.preventDefault();
            if(this.$refs.editEquipmentForm.checkValidity() === false)
            {
                //  TODO - add rule to allow only valid name - no special characters and unique name
                this.validated = true;
            }
            else
            {
                this.button.text = 'Processing...';
                this.button.disable = true;
                axios.put(this.route('admin.systems.update', this.sys_data.sys_id), this.form)
                    .then(res => {
                        window.location.href = this.route('admin.systems.index');
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            }
        },
        addRow()
        {
            this.range += 1;
        },
        deleteSystem()
        {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to delete this equipment. ', {
                title: 'PLEASE CONFIRM - THIS CANNOT BE UNDONE',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
            .then(value => {
                if(value)
                {
                    this.$refs['loading-modal'].show();
                    axios.delete(this.route('admin.systems.destroy', this.sys_data.sys_id))
                        .then(res => {
                            this.$refs['loading-modal'].hide();
                            this.$bvModal.msgBoxOk(res.data.reason)
                                .then(value => {
                                    window.location.href = this.route('admin.systems.index');
                                });
                        }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                }
            })
            .catch(error => {
                alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error);
            });
        }
    }
}
</script>
