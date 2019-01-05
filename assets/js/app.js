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

function checkFile($files, $dir = '') {
  if($dir == ''){
    var action = 'checkFile';
  }else{
    var action = 'checkFileSingle';
  }
  return new Promise((resolve, reject) => {
    $.ajax({
      url: 'controller/api.php?action='+action,
      type: 'POST',
      data: {
        files: $files,
        dir: $dir
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
      '<input type="checkbox" class="CheckboxGroup" data-file-name="'+file.file+'" data-directory="'+file.directories[i].directory+'">' +
      file.directories[i].name+'</label>' +
      '<br>' +
      '<label>'
  }


  if(file.ext == 'mp4'){
    var thumb = '<video class="video" style=" width: 192px; height: 108px; background: #fff" controls>'
      + '<source src="'+file.fileDirectory+'" type="video/mp4">'
      + 'Your browser does not support HTML5 video.'
      + '</video>';
  }else{
    var thumb = '<img src="'+file.fileDirectory+'" alt="" class="thumbnails"/>';
  }

  return '<div style="padding-left: 50px; float: left; width: 300px; min-height: 330px;">' +
      '<div style="position: relative; width: 192px;">' +
      '<div class="delete-image-container"><button data-file-name="'+file.file+'" class="btn btn-danger btn-sm delete-image" title="Delete Image">X</button></div>' +
    thumb +'<br>' +
    '</div>' +
    '<p>' +
      directories +
    '</p>' +
    '</div>';
}


function buildHtmlSingle(file) {

  if(file.ext == 'mp4'){
    var thumb = '<video class="video" style=" width: 192px; height: 108px; background: #fff" controls>'
      + '<source src="'+file.fileDirectory+'" type="video/mp4">'
      + 'Your browser does not support HTML5 video.'
      + '</video>';
  }else{
    var thumb = '<img src="'+file.fileDirectory+'" alt="" class="thumbnails"/>';
  }

  return '<div style="padding-left: 50px; float: left; width: 300px; min-height: 200px;">' +
    '<div style="position: relative; width: 192px;">' +
    '<div class="delete-image-container"><button data-directory="'+file.directory+'" data-file-name="'+file.file+'" class="btn btn-danger btn-sm delete-image" title="Delete Image">X</button></div>' +
    thumb +'<br>' +
    '</div>' +
    '<p>' +
    '<label>' +
    '<input type="checkbox" class="CheckboxGroup" data-file-name="'+file.file+'" data-directory="'+file.directory+'">Move to UPLOAD folder' +
    '</label>' +
    '</p>' +
    '</div>';
}
