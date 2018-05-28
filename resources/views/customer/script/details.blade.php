<script>
    var custID = {{$details->cust_id}}
    //  Call initial functions to load pages
    loadSystems();
    loadContacts();
    //  Add/Remove customer bookmark
    $('.fa-bookmark').on('click', function()
    {
        var url = '{{route('customer.toggleFav', ['id' => $details->cust_id, 'action' => ':act'])}}';
//        url = url.replace(':id', custID);
        if($(this).hasClass('bookmark-unchecked'))
        {
            url = url.replace(':act', 'add');
            $.get(url);
            $(this).removeClass('bookmark-unchecked');
            $(this).addClass('bookmark-checked');
        }
        else
        {
            url = url.replace(':act', 'remove');
            $.get(url);
            $(this).removeClass('bookmark-checked');
            $(this).addClass('bookmark-unchecked');
        }
    });
    //  Open the edit customer form
    $('#edit-customer').on('click', function()
    {
        $('#edit-modal').find('.modal-title').text('Edit Customer Details');
        $('#edit-modal').find('.modal-body').load('{{route('customer.id.edit', ['id' => $details->cust_id])}}');
    });
    /////////////////////// Systems Events //////////////////////////
    //  Load New System Form
    $('#system-information').on('click', '.add-system', function()
    {
        $('#edit-modal').find('.modal-title').text('Add System');
        $('#edit-modal').find('.modal-body').load('{{route('customer.systems.create')}}', function()
        {
            $('#cust_id').val('{{$details->cust_id}}');
            $('#sysType').on('change', function()
            {
                var sysVal = $(this).val();
                if(sysVal)
                {
                    //  Check if the customer already has the selected system
                    var url = '{{route('customer.checkSystem', ['custID' => $details->cust_id, 'sys' => ':sys'])}}';
                    url = url.replace(':sys', sysVal);
                    $.get(url, function(data)
                    {
                        if(data > 0)
                        {
                            //  Block out info to not allow duplicates
                            $('#show-duplicates').show();
                            $('#system-details').text('');
                            $('#new-system-form').find('input[type="submit"]').prop('disabled', true);
                        }
                        else
                        {
                            //  Populate the remaining New System form
                            var url2 = '{{route('system.sysFields', ['sys' => ':sys'])}}';
                            url2 = url2.replace(':sys', sysVal);
                            $('#show-duplicates').hide();
                            $('#new-system-form').find('input[type="submit"]').prop('disabled', false);
                            $('#system-details').load(url2, function()
                            {
                                $('#new-system-form').on('submit', function(e)
                                {
                                    e.preventDefault();
                                    $.post($(this).attr('action'), $(this).serialize(), function(res)
                                    {
                                        resetEditModal();
                                        loadSystems();
                                    });
                                });
                            });
                        }
                    });
                }
                else
                {
                    $('#system-details').text('');
                }
            });
        });
    });
    //  Load the edit system form
    $('#system-information').on('click', '.edit-system', function()
    {
        var url = '{{route('customer.systems.edit', ['sys' => ':sys'])}}';
        url = url.replace(':sys', $(this).data('sysid'));
        $('#edit-modal').find('.modal-title').text('Edit System');
        $('#edit-modal').find('.modal-body').load(url, function()
        {
            $('#edit-system-form').on('submit', function(e)
            {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function()
                {
                    resetEditModal();
                    loadSystems();
                });
            });
        });
    });
    /////////////////////// Contacts Events //////////////////////////
    //  Load the add contact form
    $('#contact-information').on('click', '#add-contact-btn', function()
    {
        var url = '{{route('customer.contacts.create')}}';
        $('#edit-modal').find('.modal-title').text('New Contact');
        $('#edit-modal').find('.modal-body').load(url, function()
        {
            $('#custID').val(custID);
            $('#new-contact-form').on('submit', function(e)
            {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function()
                {
                    resetEditModal();
                    loadContacts();
                });
            });
        });
    });
    //  Load the edit contact form
    $('#contact-information').on('click', '.edit-contact', function()
    {
        var url = '{{route('customer.contacts.edit', ['cont' => ':cont'])}}';
        url = url.replace(':cont', $(this).parent().data('contact'));
        $('#edit-modal').find('.modal-title').text('Edit Contact>');
        $('#edit-modal').find('.modal-body').load(url, function()
        {
            $('#edit-contact-form').on('submit', function(e)
            {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function(res)
                {
                    resetEditModal();
                    loadContacts();
                });
            });
        });
    });
    //  Load confirm box for deleting a contact
    $('#contact-information').on('click', '.delete-contact', function()
    {
        var contID = $(this).parent().data('contact');
        var url = '{{route('customer.contacts.destroy', ['cont' => ':cont'])}}';
        url = url.replace(':cont', contID);
        $('#edit-modal').find('.modal-title').text('Delete Contact');
        $('#edit-modal').find('.modal-body').load('{{route('confirm')}}', function()
        {
            $('.select-yes').on('click', function()
            {
                $.ajax(
                {
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res)
                    {
                        resetEditModal();
                        loadContacts();
                    }
                });
            });
        });
    });
    //  Add a new number type to the Contact Form
    $('#edit-modal').on('click', '#add-number', function(e)
    {
        e.preventDefault();
        $('#number-type-div').clone().appendTo('#phone-numbers');
    });
    
    
    /////////////////////// Functions //////////////////////////
    //  Load all customer systems
    function loadSystems()
    {
        var url = '{{route('customer.systems.show', ['id' => ':id'])}}';
        url = url.replace(':id', custID);
        $('#system-information').load(url, function()
        {
            var systemType = $('#myTab').find('.active').data('sys');
            //  Load event handlers to handle customers with multiple systems
            $('#edit-system').data('sysid', systemType);
            $('#system-information').find('.nav-link').on('click', function()
            {
                systemType = $(this).data('sys');
                $('#edit-system').data('sysid', systemType);
            });
        });
    }
    //  Load all customer contacts
    function loadContacts()
    {
        var url = '{{route('customer.contacts.show', ['id' => ':id'])}}';
        url = url.replace(':id', custID);
        $('#contact-information').find('tbody').load(url, function()
        {
            //  Re-initialize the tooltips
            $('[data-tooltip="tooltip"]').tooltip();
        });
    }
    
</script>
