
require('./bootstrap');
require('jquery-easing');








$(document).ready(function()
{
    //  Enable any tooltips on the page
    $('[data-toggle="tooltip"]').tooltip();
    
/////////////////////////// Drag and Drop/File Upload Functions ////////////////////////////////////
    
    //  Modify the custom "file" form input with the name of the file
    var inputs = document.querySelectorAll( '#fileselect' );
    Array.prototype.forEach.call( inputs, function( input )
    {
        var     label	 = input.nextElementSibling,
                labelVal = label.innerHTML;

        input.addEventListener( 'change', function(e)
        {
            var fileName = '';
            if( this.files && this.files.length > 1 )
            {
                fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
            }
            else
            {
                fileName = e.target.value.split( '\\' ).pop();
            }
                
            if( fileName )
            {
                $('#dragndrop-notice').html(fileName);
            }
            else
            {
                $('#dragndrop-notice').html(labelVal);
                label.text(files.length > 1 ? (input.attr('data-multiple-caption') || '').replace( '{count}', files.length ) : files[ 0 ].name);
            }  
        });
    });
    
    //  Check for drag and drop functionality
    function fileDrag()
    {
        if(window.File && window.FileList && window.FileReader)
        {
            dragNDropInit();
        }
    }
    
    var droppedFiles = false;
    showFiles = function(files) {
        $('#dragndrop-notice').text(files.length > 1 ? ($('#dragndrop-notice').attr('data-multiple-caption') || '').replace( '{count}', files.length ) : files[ 0 ].name);
    };

    //  Initialize the drag and drop window
    function dragNDropInit()
    {
        var dropBox = $('#box-filedrag');

        $('#box-filedrag').addClass('has-dragndrop');        
        dropBox.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) 
        {
            e.preventDefault();
            e.stopPropagation();
        })
        .on('dragover dragenter', function() {
            dropBox.addClass('is-dragover');
        })
        .on('dragleave dragend drop', function() {
            dropBox.removeClass('is-dragover');
        })
        .on('drop', function(e) {
            droppedFiles = e.originalEvent.dataTransfer.files;
            showFiles(droppedFiles);
        });
    }

    //  Submit a file
    function submitFile(form)
    {   
        var formdata = new FormData($(form)[0]);
        var input	 = form.find( 'input[type="file"]' );
        var xhr;

        if(droppedFiles)
        {
            $.each(droppedFiles, function(i, file)
            {
                formdata.append(input.attr('name'), file);
            });
        }

        $.ajax({
            url : form.attr('action'),
            type: "POST",
            data : formdata,
            contentType: false,
            cache: false,
            processData:false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            xhr: function()
            {
                //upload Progress
                xhr = $.ajaxSettings.xhr();
                if (xhr.upload) 
                {
                    xhr.upload.addEventListener('progress', function(event) 
                    {
                        var percent = 0;
                        var position = event.loaded || event.position;
                        var total = event.total;
                        if (event.lengthComputable) 
                        {
                            percent = Math.ceil(position / total * 100);
                        }
                        $('#forProgressBar').css('display', 'block');
                        //update progressbar
                        $("#progressBar").css("width", percent+"%");
                        $("#progressStatus").text(percent+"%");
                    }, true);
                }
                return xhr;
            }, 
            mimeType:"multipart/form-data",
        })
        .done(function(res)
        {   
            uploadComplete(res);
        }); 
        
        $('.cancel-upload').on('click', function()
        {
            xhr.abort();
        });
    }
    
    //  Reset the progress bar and drag and drop box
    function resetProgressBar()
    {
        $('#dragndrop-notice').text('Or Drag Files Here');
        $('#progressBar').css('width', '0%').attr('aria-valuenow', 0);
        $('#progressStatus').text('');
        $('#forProgressBar').hide();
    }
    
//////////////////////////////////////////////////////////////////////////////////////////
    

    //  Make the functions in this file globally accessable
    window.submitFile = submitFile;
    window.resetProgressBar = resetProgressBar;
    window.fileDrag = fileDrag;
});
