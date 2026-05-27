
    <!-- TinyMCE Script -->
    <script src="https://cdn.tiny.cloud/1/wj0tnfge5dwt2pfaan81gg68pfs8bqtzmjrn9k5kxmwaqb0e/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    
    <script>
        // Initialize TinyMCE on the 'description' textarea
        tinymce.init({
            selector: 'textarea#description',  // Apply TinyMCE to this selector
            height: 300,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | \
                      alignleft aligncenter alignright alignjustify | \
                      bullist numlist outdent indent | removeformat | help',
        });
    </script>
