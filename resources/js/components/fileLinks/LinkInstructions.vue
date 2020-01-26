<template>
    <div class="row pad-top">
        <div class="col-12">
            <div class="card w-100">
                <div class="card-header"><h5 class="card-title"><i class="fa fa-pencil pointer" id="edit-link-note" title="Edit Instructions for Customer" @click="openEdit" data-tooltip="tooltip" v-b-tooltip.hover></i> Instructions:</h5></div>
                <div class="card-body">
                    <div v-if="instructions == null">
                        <h4 class="text-center">No Instructions</h4>
                    </div>
                    <div v-else v-html=instructions></div>
                </div>
            </div>
        </div>
        <b-modal id="instructions-edit-modal" title="Edit Instructions" ref="instructionsModal" size="xl" hide-footer centered>
             <b-form id="edit-cust-form" @submit="updateInstructions" method="post" :action=instructions_route>
                <input type="hidden" name="_token" :value=token />
                <div class="pt-2 pb-2">
                    <editor :init="{plugins: 'autolink', height: 500}" v-model=instructions></editor>
                </div>
                <b-button type="submit" block variant="primary" :disabled="button.dis">{{button.text}}</b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'instructions_route',
        ],
        data() {
            return {
                link_id:      '',
                instructions: '',
                token:        window.techBench.csrfToken,
                button: {
                    text: 'Update Instructions',
                    dis:   false
                }
            }
        },
        created() 
        {
            this.getInstruction();
        },
        methods: {
            getInstruction()
            {
                axios.get(this.instructions_route)
                    .then(res => {
                        this.instructions = res.data.note;
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            openEdit()
            {
                this.$refs.instructionsModal.show();
            },
            updateInstructions(e)
            {
                e.preventDefault();
                this.button.text = 'Loading...';
                this.button.dis  = true;
                
                axios.post(this.instructions_route, {
                    note: this.instructions
                })
                .then(res => {
                    this.$refs.instructionsModal.hide();
                    this.button.text = 'Update Instructions';
                    this.button.dis  = false;
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            }
        }
    }
</script>
