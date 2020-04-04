<template>
    <b-button type="submit" block variant="primary" :disabled="button.disable">
        <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
        {{button.text}}
    </b-button>
</template>

<script>
    export default {
        props: {
            button_text: {
                type: String,
                default: 'Submit',
            },
            submitted: {
                type: Boolean,
                default: false,
            }
        },
        data: function () {
            return {
                button: {
                    text: this.button_text,
                    disable: false,
                }
            }
        },
        methods: {

        },
        watch: {
            submitted()
            {
                if(this.submitted)
                {
                    this.button.text = "Loading...";
                    this.button.disable = true;
                }
                else
                {
                    this.button.text = this.button_text;
                    this.button.disable = false;
                }
            },
            button_text()
            {
                this.button.text = this.button_text;
            }
        }
    }
</script>
