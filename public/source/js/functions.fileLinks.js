$('#new-file-link').validate(
{
    rules:
    {
        file: {
            filesize: maxFile
        }
    },
    messages:
    {
        file: "File size must be less than "+filesize(maxFile)
    },
    submitHandler: function()
    {
        if($('#file').val() == '')
        {
            $.post('/links/createSubmit', $('#new-file-link').serialize(), function(data)
            {
                uploadComplete(data);
            });
        }
        else
        {
            submitFile('/links/createSubmit', 'new-file-link');
        }
    }
});

function uploadComplete(res)
{
    window.location.replace('/links/details/'+res);
}