<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //  Service provider to customize form inputs
        Form::component('bsText', 'components.form.text', ['name', 'label', 'value' => null, 'attributes' => []]);
        Form::component('bsPassword', 'components.form.password', ['name', 'label', 'value' => null, 'attributes' => []]);
        Form::component('bsNumber', 'components.form.number', ['name', 'label', 'value' => null, 'attributes' => []]);
        Form::component('bsEmail', 'components.form.email', ['name', 'label', 'value' => null, 'attributes' => []]);
        Form::component('bsTextarea', 'components.form.textarea', ['name', 'label', 'value' => null, 'attributes' => []]);
        Form::component('bsCheckbox', 'components.form.checkbox', ['name', 'label', 'value' => 'on', 'checked' => false, 'attributes' => []]);
        Form::component('bsSelect', 'components.form.select', ['name', 'label', 'list', 'selected' => null, 'attributes' => []]);
        
        
        //  Custom Submit button
        Form::component('bsSubmit', 'components.form.submit', ['name']);
        
        //  List of all states in a dropdown box
        Form::component('allStates', 'components.form.allStates', ['default' => null]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
