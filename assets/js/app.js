$(function () {

  scanDir('Upload')
    .then(
      files => {
        checkFile(files)
          .then(
            res => {
              $htmlData = '';
              for($i = 0; $i < res.length; $i++){
                $htmlData += buildHtml(res[$i]);
              }

              $('.images-container').html($htmlData).removeClass('display-none');
              $('.loader').addClass('display-none');


              $('#actionButton').click(function(){
                $btn = $(this);
                $btn.attr('disabled', true);
                $checkboxes = $('.CheckboxGroup:checkbox:checked');
                $data = [];
                $.each($checkboxes, function(){
                  $file = $(this).attr('data-file-name');
                  $dir = $(this).attr('data-directory');
                  $(this).parent().remove();
                  $data.push({
                    file: $file,
                    directory: $dir
                  });
                })


                $.ajax({
                  url: 'controller/api.php?action=move',
                  type: 'POST',
                  data: {
                    data: $data
                  },
                  dataType: 'json'
                }).done(function (response) {
                   if(response){
                     alert('Files successfully added.');
                   }else{
                     alert('Something went wrong, please try again.');
                     //location.reload();
                   }
                  $btn.attr('disabled', false);
                });
              });

            }
          )
      }
    );

});


function scanDir($dir) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: 'controller/api.php?action=scan',
      type: 'POST',
      data: {
        dir: $dir
      },
      dataType: 'json'
    }).done(function (response) {
      resolve(response);
    });
  })
}

function checkFile($files) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: 'controller/api.php?action=checkFile',
      type: 'POST',
      data: {
        files: $files
      },
      dataType: 'json'
    }).done(function (response) {
      resolve(response);
    });
  })
}


function buildHtml(file) {
  var directories = '';
  for(var i = 0; i < file.directories.length; i++){
    directories += '<label>' +
      '<input type="checkbox" class="CheckboxGroup" data-file-name="'+file.file+'" data-directory="'+file.directories[i]+'">' +
      file.directories[i]+'</label>' +
      '<br>' +
      '<label>'
  }


  if(file.ext == 'mp4'){
    var thumb = '<video class="video" style="width: 192px; height: 108px; background: #fff" controls>'
      + '<source src="'+file.fileDirectory+'" type="video/mp4">'
      + 'Your browser does not support HTML5 video.'
      + '</video>';
  }else{
    var thumb = '<img src="'+file.fileDirectory+'" alt="" class="thumbnails"/>';
  }

  return '<div style="float: left; width: 330px;">' +
    thumb +'<br>' +
    '<p>' +
      directories +
    '</p>' +
    '</div>';
}
