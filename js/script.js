//Find current link in navbar and make it active
$(document).ready(function() {
    var url = this.location.pathname;
    var filename = url.substring(url.lastIndexOf('/')+1);
    $('a[href="' + filename + '"]').parent().addClass('active');
    
    //ajax call to check if username exists
    $('#signupUsername').change(function () {
        //Assign chosen username to a variable
        var user = $('#signupUsername').val();

        //Send username selection to PHP script for SQL call
        $.get('usernameCheck.php', {
            'user': user
        }, function (return_data) {
            if (return_data.data.length > 0) {
                $('#msg').html('Username taken');
                $("#signupButton").attr("disabled", true);
            } else {
                $("#signupButton").removeAttr("disabled");
                $('#msg').html('');
            }
        }, "json");
    });
    
});

$(document).ready( function() {
    $(document).on('change', '.btn-file :file', function() {
    var input = $(this),
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [label]);
    });

    $('.btn-file :file').on('fileselect', function(event, label) {
        
        var input = $(this).parents('.input-group').find(':text'),
            log = label;
        
        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }
    
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#img-upload').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#fileToUpload").change(function(){
        readURL(this);
    }); 	
});