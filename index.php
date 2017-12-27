<?php
/**
 * Created by PhpStorm.
 * User: Hitesh
 * Date: 27-Dec-17
 * Time: 9:22 PM
 */
?>
<html>
<head>
    <title>Upload Image</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="js/jquery.imgareaselect.dev.js" type="text/javascript"></script>
    <script src="js/jquery.form.js"></script>
    <link rel="stylesheet" href="css/imgareaselect-animated.css">
    <link rel="stylesheet" href="css/imgareaselect-default.css">
    <link rel="stylesheet" href="css/imgareaselect-deprecated.css">

</head>
<body>
<div class="container" style="padding-top:50px">
    <div class="row">
        <div class="col-sm-2"><img class="img-circle" id="avatar-edit-img" height="128" data-src="default.jpg"  data-holder-rendered="true" style="width: 140px; height: 140px;" src="savi.jpg"/></div>
        <div class="col-sm-10"><a type="button" class="btn btn-primary" id="change-pic">Change Image</a>
            <div id="changePic" class="" style="display:none">
                <form id="cropimage" method="post" enctype="multipart/form-data" action="profile.php">
                    <label>Upload your image</label><input type="file" name="photoimg" id="photoimg" />
                    <input type="hidden" name="hdn-profile-id" id="hdn-profile-id" value="1" />
                    <input type="hidden" name="hdn-x1-axis" id="hdn-x1-axis" value="" />
                    <input type="hidden" name="hdn-y1-axis" id="hdn-y1-axis" value="" />
                    <input type="hidden" name="hdn-x2-axis" value="" id="hdn-x2-axis" />
                    <input type="hidden" name="hdn-y2-axis" value="" id="hdn-y2-axis" />
                    <input type="hidden" name="hdn-thumb-width" id="hdn-thumb-width" value="" />
                    <input type="hidden" name="hdn-thumb-height" id="hdn-thumb-height" value="" />
                    <input type="hidden" name="action" value="" id="action" />
                    <input type="hidden" name="image_name" value="" id="image_name" />

                    <div id='preview-avatar-profile'>
                    </div>
                    <div id="thumbs" style="padding:5px; width:600px"></div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-crop" class="btn btn-primary">Crop & Save</button>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
<script type="text/javascript">
    jQuery(document).ready(function(){

        jQuery('#change-pic').on('click', function(e){
            jQuery('#changePic').show();
            jQuery('#change-pic').hide();

        });

        jQuery('#photoimg').on('change', function()
        {
            jQuery("#preview-avatar-profile").html('');
            jQuery("#preview-avatar-profile").html('Uploading....');
            jQuery("#cropimage").ajaxForm(
                {
                    target: '#preview-avatar-profile',
                    success:    function() {
                        jQuery('img#photo').imgAreaSelect({
                            aspectRatio: '1:1',
                            onSelectEnd: getSizes,
                        });
                        jQuery('#image_name').val(jQuery('#photo').attr('file-name'));
                    }
                }).submit();

        });

        jQuery('#btn-crop').on('click', function(e){
            e.preventDefault();
            params = {
                targetUrl: 'profile.php?action=save',
                action: 'save',
                x_axis: jQuery('#hdn-x1-axis').val(),
                y_axis : jQuery('#hdn-y1-axis').val(),
                x2_axis: jQuery('#hdn-x2-axis').val(),
                y2_axis : jQuery('#hdn-y2-axis').val(),
                thumb_width : jQuery('#hdn-thumb-width').val(),
                thumb_height:jQuery('#hdn-thumb-height').val()
            };

            saveCropImage(params);
        });



        function getSizes(img, obj)
        {
            var x_axis = obj.x1;
            var x2_axis = obj.x2;
            var y_axis = obj.y1;
            var y2_axis = obj.y2;
            var thumb_width = obj.width;
            var thumb_height = obj.height;
            if(thumb_width > 0)
            {

                jQuery('#hdn-x1-axis').val(x_axis);
                jQuery('#hdn-y1-axis').val(y_axis);
                jQuery('#hdn-x2-axis').val(x2_axis);
                jQuery('#hdn-y2-axis').val(y2_axis);
                jQuery('#hdn-thumb-width').val(thumb_width);
                jQuery('#hdn-thumb-height').val(thumb_height);

            }
            else
                alert("Please select portion..!");
        }

        function saveCropImage(params) {
            jQuery.ajax({
                url: params['targetUrl'],
                cache: false,
                dataType: "html",
                data: {
                    action: params['action'],
                    id: jQuery('#hdn-profile-id').val(),
                    t: 'ajax',
                    w1:params['thumb_width'],
                    x1:params['x_axis'],
                    h1:params['thumb_height'],
                    y1:params['y_axis'],
                    x2:params['x2_axis'],
                    y2:params['y2_axis'],
                    image_name :jQuery('#image_name').val()
                },
                type: 'Post',
                // async:false,
                success: function (response) {
                    jQuery('#changePic').hide();
                    jQuery('#change-pic').show();
                    jQuery(".imgareaselect-border1,.imgareaselect-border2,.imgareaselect-border3,.imgareaselect-border4,.imgareaselect-border2,.imgareaselect-outer").css('display', 'none');

                    jQuery("#avatar-edit-img").attr('src', response);
                    jQuery("#preview-avatar-profile").html('');
                    jQuery("#photoimg").val('');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert('status Code:' + xhr.status + 'Error Message :' + thrownError);
                }
            });
        }
    });
</script>
</html>