<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-center">Edit System Category</h1>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="alert alert-success text-center hidden" id="alert-notification"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <form id="edit-category-form">
                <div class="form-group">
                    <label for="selectCategory">Select Category to Edit:</label>
                    <select name="selectCategory" id="selectCategory" class="form-control">
                        <option></option>
                        <?= $data['categories']; ?>
                    </select>
                </div>
                <div class="edit-category-wrapper hidden">
                    <div class="form-group" id="edit-category-name">
                        <label for="category">Category Name:</label>
                        <input type="text" class="form-control" name="category" id="category" required autofocus />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="form-control btn btn-default" value="Update Category" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-6 col-md-offset-3">
            <h3 class="text-center">Instructions:</h3>
            <p>
                Editing a system category will allow you to rename an existing category.
            </p>
            <p>
                Examples of System Categories for an Information Technology company would be:  "Routers," "Servers," and "Switches."
            </p>
        </div>
    </div>
</div>

<script>
    $('#selectCategory').on('change', function()
    {
        var value = $(this).val();
        $('#category').val(value);
        $('.edit-category-wrapper').removeClass('hidden');
    });
    $('#edit-category-form').validate(
    {
        rules:
        {
            category:
            {
                required: true,
                minlength: 3
            }
        },
        messages:
        {
            category:
            {
                required: "Please enter a descriptive name for the category",
                minlength: "Category name must be at least three characters long"
            }
        },
        submitHandler: function()
        {
            $.post('/site-administration/submitUpdateCategory', $('#edit-category-form').serialize(), function(data)
            {
                $('#alert-notification').removeClass('hidden');
                if(data === 'success')
                {
                    $('#alert-notification').html('Category Successfully Updated.');
                    $('#systemNavLinks').load('/site-administration/reloadSysLinks');
                }
                else
                {
                    alert(data);
                    $('#alert-notification').html('There Was A Problem Creating The Category.<br />View Logs For More Infomation.');
                }
            });
        }
    });
</script>