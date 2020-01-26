<template>
    <b-form @submit="submitForm" novalidate :validated="validated" ref="newEquipmentForm">
        <fieldset>
            <b-form-group label="System Name:" label-for="name" class="mt-2">
                <b-form-input id="name" name="name" v-model="form.name" type="text" required></b-form-input>
                <span class="invalid-feedback">You must enter a valid name</span>
            </b-form-group>
        </fieldset>
        <fieldset>
            <label>Customer Information to Gather</label>
            <div ref="container">
                <v-select v-for="n in range" :key="n" :options="data_list" label="name" v-model="form.dataOptions[n]" placeholder="Select An Option" class="mb-3" taggable></v-select>
            </div>
            <span class="pointer float-right" @click="addRow">Add Row</span>
        </fieldset>
        <b-button type="submit" block variant="primary" :disabled="button.disable">
            <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
            {{button.text}}
        </b-button>
    </b-form>
</template>

<script>
export default {
    props: [
        'cat_id',
        'data_list',
    ],
    data() {
        return {
            validated: false,
            range: 5,
            dataTypes: [],
            form: {
                category: this.cat_id,
                name: '',
                dataOptions: {},
            },
            button: {
                text: 'Create System',
                disable: false,
            }
        }
    },
    created()
    {

    },
    methods: {
        submitForm(e)
        {
            e.preventDefault();
            console.log(this.form);
            if(this.$refs.newEquipmentForm.checkValidity() === false)
            {
                //  TODO - add rule to allow only valid name - no special characters and unique name
                this.validated = true;
            }
            else
            {
                this.button.text = 'Processing...';
                this.button.disable = true;
                console.log('ready to go');
                axios.post(this.route('admin.systems.store'), this.form)
                    .then(res => {
                        console.log(res);
                        window.location.href = this.route('admin.index');
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            }
        },
        addRow()
        {
            this.range += 1;
        }
    }
}
</script>
