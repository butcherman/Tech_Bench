<script>
    var custID = {{$details->cust_id}}
    //  Call initial functions to load pages
    loadSystems();
    loadContacts();
    loadNotes();
    loadFiles();
    
    $('#edit-modal').on('hide.bs.modal', function()
    {
        $('#edit-modal').find('.modal-title').text('');
        $('#edit-modal').find('.modal-body').text('');
        $('#edit-modal').find('#modal-footer-extra').text('');
    });
    
    /////////////////////// Customer Details Events //////////////////////////
    //  Add/Remove customer bookmark
    $('.fa-bookmark').on('click', function()
    {
        var url = '{{route('customer.toggleFav', ['id' => $details->cust_id, 'action' => ':act'])}}';
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
        @if($current_user->hasAnyRole(['installer', 'admin']))
            $('#edit-modal').find('#modal-footer-extra').prepend('<button type="button" class="btn btn-danger mr-auto" id="deactivate-customer">Deactivate Customer</button>');
            $('#deactivate-customer').on('click', function()
            {
                var url = '{{route('customer.id.destroy', ['id' => ':id'])}}';
                url = url.replace(':id', custID);
                
                $('#edit-modal').find('.modal-title').text('Deactivate Customer');
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
                                window.location.replace('{{route('customer.index')}}');
                            }
                        });
                    });
                });
                $('#edit-modal').find('#modal-footer-extra').text('');
            });
        @endif
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
    //  Confirmation box to delete a system
    $('#edit-modal').on('click', '#delete-system', function()
    {
        var url = '{{route('customer.systems.destroy', ['id' => ':id'])}}';
        url = url.replace(':id', $(this).data('id'));
        $('#edit-modal').find('.modal-title').text('Confirm Delete System');
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
                        loadSystems();
                    }
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
    
    /////////////////////// Notes Events //////////////////////////
    //  Open a note
    $('#customer-notes').on('click', '.card-header', function()
    {
        $(this).next().addClass('bigger-note');
        $('#edit-modal').find('.modal-title').text('Customer Note:')
        $('#edit-modal').find('.modal-body').html($(this).parent().clone());
        $('#edit-modal').find('.modal-dialog').addClass('modal-lg');
        $('#edit-modal').modal('show');
    });
    //  Load the new note form
    $('#customer-notes').on('click', '#new-note-link', function()
    {
        if (typeof tinymce != 'undefined' && tinymce != null) {
            tinymce.remove();
        }
        $('#edit-modal').find('.modal-title').text('New Note');
        $('#edit-modal').find('.modal-body').load('{{route('customer.notes.create')}}', function()
        {
            tinymce.init(
            {
                selector: 'textarea',
                height: '400',
                plugins: 'autolink'
            });
            $('#custID').val(custID);
            $('#new-note-form').on('submit', function(e)
            {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function()
                {
                    resetEditModal();
                    loadNotes();
                });
            });
        });
    });
    //  Load the edit note form
    $(document).on('click', '.edit-note-button', function()
    {
        var url = '{{route('customer.notes.edit', ['id' => ':id'])}}';
        url = url.replace(':id', $(this).data('note'));
        if (typeof tinymce != 'undefined' && tinymce != null) {
            tinymce.remove();
        }
        $('#edit-modal').find('.modal-title').text('Edit Note');
        $('#edit-modal').find('.modal-body').load(url, function()
        {
            tinymce.init(
            {
                selector: 'textarea',
                height: '400',
                plugins: 'autolink'
            });
            $('#edit-note-form').on('submit', function(e)
            {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function()
                {
                    resetEditModal();
                    loadNotes();
                });
            });
        });
    });
    
    /////////////////////// Files Events //////////////////////////
    //  Load add file form
    $('#add-customer-file').on('click', function()
    {
        $('#edit-modal').find('.modal-title').text('Add New File');
        $('#edit-modal').find('.modal-body').load('{{route('customer.files.create')}}', function()
        {
            fileDrop($('#new-file-form'));
            $('#custID').val(custID);
        });
    });
    //  Load edit file form
    $('#customer-files').on('click', '.edit-file', function()
    {
        var url = '{{route('customer.files.edit', ['id' => ':id'])}}';
        url = url.replace(':id', $(this).data('file'));
        $('#edit-modal').find('.modal-title').text('Edit File');
        $('#edit-modal').find('.modal-body').load(url, function()
        {
            $('#edit-file-form').on('submit', function(e)
            {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function()
                {
                    resetEditModal();
                    loadFiles();
                });
            });
        });
    });
    //  Load confirmation box for deleting a file
    $('#customer-files').on('click', '.delete-file', function()
    {
        var fileID = $(this).data('file');
        var url = '{{route('customer.files.destroy', ['file' => ':file'])}}';
        url = url.replace(':file', fileID);
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
                        loadFiles();
                    }
                });
            });
        });
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
    //  Load all customer notes
    function loadNotes()
    {
        var url = '{{route('customer.notes.show', ['id' => ':id'])}}';
        url = url.replace(':id', custID);
        $('#customer-notes').load(url);
    }
    //  Load all the customers files
    function loadFiles()
    {
        var url = '{{route('customer.files.show', ['id' => ':id'])}}';
        url = url.replace(':id', custID);
        $('#customer-files').find('tbody').load(url);
    }
    //  Finish the file upload by resetting form and reloading files
    function uploadComplete(res)
    {
        resetEditModal();
        loadFiles();
    }
    //  If the upload Fails, show the errors
    function uploadFailed(data)
    {
        $('#form-errors').removeClass('d-none');
        var err = $.parseJSON(data.responseText);
        $.each(err.errors, function(key, val)
        {
            $('#form-errors').append('<h5>'+val+'</h5>');
        });
    }
</script>
