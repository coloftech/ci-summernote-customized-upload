    
<style>
/*to disable the upload image from computer uncomment this css code.*/
.note-group-select-from-files {
  display: none;
}

</style> 
<script type="text/javascript">
    

$('#desc').summernote({
    callbacks: {
        onPaste: function (e) {
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            document.execCommand('insertText', false, bufferText);
        }
    },

  toolbar: [
    // [groupName, [list of button]]
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['font', ['strikethrough', 'superscript', 'subscript']],
    ['fontsize', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['height', ['height']],
    ['table', ['table']],
    ['insert', ['picture','link']],
    ['view',['codeview']]
  ],
  height: 250
});
document.getElementsByClassName('note-group-image-url')[0].insertAdjacentHTML('afterend','<p class="sober"><input   type="file" name="note_upload" id="note_upload" class="form-control "/><button type="button" id="btn-summernote" class="btn btn-default">Upload</button><div class="upload_img btn"></div> </p>');


$('#btn-summernote').on('click',function(){

    var data = new FormData();
    data.append('note_upload', $('#note_upload')[0].files[0]);

     var size  =  $('#note_upload')[0].files[0].size;

    // console.log(size);
     if(size <= 1000000){

        //alert('File is ready to upload');
        i_upload(data);

     }else{
        alert('File is to big');
     }

});

            var i = 0;
            var percentComplete;
            var xhr;
        function i_upload(data) {

            $.ajax({


               xhr: function() {
                    

                        xhr = new window.XMLHttpRequest();

                        xhr.upload.addEventListener("progress", function(evt) {
                          if (evt.lengthComputable) {
                            percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            $('.upload_img').html('Upload on progress with '+percentComplete+' % to complete.');
                            //console.log(percentComplete);
                           
                            
                            if (percentComplete < 10) {
                              $('.upload_img').addClass('alert-danger');
                            }
                            if (percentComplete >=10 && percentComplete < 25) {
                              $('.upload_img').removeClass('alert-danger');
                            }
                            if (percentComplete >= 25 && percentComplete < 50) {
                              $('.upload_img').removeClass('alert-danger');
                              $('.upload_img').addClass('alert-warning');
                            }
                            if (percentComplete >= 50 && percentComplete < 75) {
                              $('.upload_img').removeClass('alert-warning');
                              $('.upload_img').addClass('alert-info');
                            }
                            if (percentComplete === 100) {
                              $('.upload_img').removeClass('alert-info');
                              $('.upload_img').addClass('alert-success');
                              $('.upload_img').html('proccessing...');

                            }

                          }
                        }, false);

                        return xhr;
               },

              type: 'post',
              url: '<?=site_url('c=summernote&f=insert_image');?>',
              data: data,
              processData: false,
              contentType: false,
              dataType:'json',
              success: function (resp) {
                    console.clear();
                    console.log(resp);
                    if(resp.stats == true){
                       $('.note-image-url').val(resp.link);

                    $('.note-image-btn').removeAttr("disabled").removeClass("disabled");

                        setTimeout(function () {
                            //$('#uploadModal').modal('hide');
                        },1000);

                    }
              },
                 complete: function() {
                  // setting a timeouti--;
                      if (i <= 0) {
                              $('.upload_img').removeClass('alert-success');
                              $('.upload_img').removeClass('btn');
                                $('.upload_img').html('');                          

                      }
                  }
            });


            return false;
        }
</script>