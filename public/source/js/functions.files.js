//  submitFile function will process an uploaded file to the system.  The processor input is the AJAX controller that will process the file and the ID of the form element
function submitFile(processor, formID)
{
	var formdata = new FormData($('#'+formID)[0]);
    
    setTimeout(updateSession, 1000*60);//Timeout is 1 min

    function updateSession() 
    {
        $.ajax(
        {
            type: "POST",
            contentType: "application/json; charset=utf-8",
            url: "/home/refresh",
            data: "{}",
            dataType: "json"
        });
        setTimeout(updateSession, 1000 * 60);
    }
    
    $.ajax({
        url : processor,
        type: "POST",
        data : formdata,
        contentType: false,
        cache: false,
        processData:false,
        xhr: function()
        {
            //upload Progress
            var xhr = $.ajaxSettings.xhr();
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
        }, mimeType:"multipart/form-data"
    })
    .done(function(res)
    {   
        uploadComplete(res);
    });    
}