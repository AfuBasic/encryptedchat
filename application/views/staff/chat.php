<style type="text/css">
  .img-holder
  {
    position: relative;
    height: 500px;
    width: 100%;
    margin: auto;
    padding: 20px;
  }

  div.file-name {
    color: #ccc;
    padding: 10px 0;
  }

  .img-holder img
  {
    position: absolute;
    margin: auto;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    max-width: 100%;
    max-height: 100%;
  }

  .media-text {
    box-shadow: none;
    border:none;
    border-bottom: 1px solid #e1e1e1;
    margin-top: 20px;
    padding: 8px 15px;
  }

  input.media-text:focus {
    box-shadow: none;
  }
</style>
<div class="modal fade" id="key">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="<?=site_url('home/regkey')?>">
        <div class="modal-header">
          <h4 class="modal-title">Register Key with Session</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <div class="cc" style="padding: 20px;">
            <div class="form-group">
              <label>Enter Key</label>
              <input class="form-control" name="key" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Register Key</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="container-fluid">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?=site_url('admin')?>">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Chat</li>
  </ol>
  
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-comments"></i>
      Chat with <?=$user->name?>
      <?php if($this->session->userdata('mykey') == ""): ?>
        <a href="#key" data-toggle="modal" class="btn btn-primary pull-right">Supply Key</a>
      <?php endif; ?>
    </div>
    <div class="card-body">
      <ol class="chat">
        <?php foreach($chat as $row): ?>
          <?php if($row->sender_id == user()->id): ?>
            <?php if($row->type == "text"): ?>
              <li class="self">
                <div class="msg">
                  <p><?=$this->home_model->decryptMine($row->message)?></p>
                  <time><?=$this->home_model->getTime($row->datesent)?></time> <sender>Me</sender>
                </div>
              </li>
              <?php elseif($row->type == "image"): ?>
                <li class="self">
                  <div class="msg">
                    <img src="<?=base_url('asset/uploads/'.$row->media)?>" draggable="false"/>
                    <?php if($row->message != ""): ?>
                      <p><?=$this->home_model->decryptMine($row->message)?></p>
                    <?php endif; ?>
                    <time><?=$this->home_model->getTime($row->datesent)?></time> <sender>Me</sender>
                  </div>
                </li>
                <?php else: ?>
                  <li class="self">
                    <div class="msg">
                      <a href="<?=base_url('asset/uploads/'.$row->media)?>" target="_blank">Download File(<?=$row->media?>)</a>
                      <?php if($row->message != ""): ?>
                        <p><?=$this->home_model->decryptMine($row->message)?></p>
                      <?php endif; ?>
                      <time><?=$this->home_model->getTime($row->datesent)?></time> <sender>Me</sender>
                    </div>
                  </li>
                <?php endif; ?>
                <?php else: ?>
                  <?php if($row->type == "text"): ?>
                    <li class="other">
                      <div class="msg">
                        <p><?=$this->home_model->decryptOther($row->receiver_id, $row->message)?></p>
                        <time><?=$this->home_model->getTime($row->datesent)?></time> <sender><?=$user->name?></sender>
                      </div>
                    </li>
                    <?php elseif($row->type == "image"): ?>
                     <li class="other">
                      <div class="msg">
                        <img src="<?=base_url('asset/uploads/'.$row->media)?>" draggable="false"/>
                        <?php if($row->message != ""): ?>
                          <p><?=$this->home_model->decryptOther($row->receiver_id,$row->message)?></p>
                        <?php endif; ?>
                        <time><?=$this->home_model->getTime($row->datesent)?></time> <sender><?=$user->name?></sender>
                      </div>
                    </li>
                    <?php else: ?>
                      <li class="other">
                        <div class="msg">
                          <a href="<?=base_url('asset/uploads/'.$row->media)?>" target="_blank">Download File(<?=$row->media?>)</a>
                          <?php if($row->message != ""): ?>
                            <p><?=$this->home_model->decryptMine($row->message)?></p>
                          <?php endif; ?>
                          <time><?=$this->home_model->getTime($row->datesent)?></time> <sender><?=$user->name?></sender>
                        </div>
                      </li>
                    <?php endif; ?>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ol>
              <input class="textarea" type="text" placeholder="Type here!"/>
              <label for="inputfile"><div class="emojis"><i class="fa fa-paperclip fa-2x"></i></div>
                <input id="inputfile" type="file" name="file">
              </label>
            </div>
          </div>

        </div>

        <div class="modal fade" id="prv">
          <div class="modal-dialog" style="width: 60%;">
            <div class="modal-content">
              <div class="modal-body">
                <div class="file-name"></div>
                <div class="img-holder">
                  <img src="" class="preview">
                </div>
                <input type="text" name="message" class="form-control media-text" placeholder="Enter Message">
              </div>
              <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-primary send">Send</button>
              </div>
            </div>
          </div>
        </div>
        <script src="https://js.pusher.com/4.4/pusher.min.js"></script>
        <script type="text/javascript">
         var pusher = new Pusher('245bfd3ff4237d986e5d', {
          cluster: 'ap1',
          forceTLS: true
        });

         var pusher = new Pusher('245bfd3ff4237d986e5d', {
          cluster: 'ap1',
          forceTLS: true
        });


         var channel = pusher.subscribe('abosede');
         channel.bind('new-message', data => {

          $.post("<?=site_url('home/decrypt')?>",data).then(data => {
            data = JSON.parse(data);
            cl = data.sender_id == '<?=user()->id?>' ? "self":"other";
            c = "<li class='"+cl+"'>";
            if(data.type == "text") {
              c += "<div class='msg'><p>"+data.message+"</p><time>"+data.tt+"</time>";
              c += "<sender>"+data.sender+"</sender></div>"; 
            }else if(data.type == "image") {
              c += "<div class='msg'>"
              c += "<img src='<?=base_url('asset/uploads/')?>"+data.media+"' />";
              if(data.message != "") {
                c += "<p>"+data.message+"</p>";
              }

              c += '<time>'+data.tt+'</time><sender>'+data.sender+'</sender></div>'
            } else {
              c += " <div class='msg'>";
              c += '<a href="<?=base_url('asset/uploads/')?>'+data.media+'" target="_blank">';
              c += ' Download File('+data.media+')</a>';
              if(data.message != "") {
                c += "<p>"+data.message+"</p>";
              }
              c += '<time>'+data.tt+'</time><sender>'+data.sender+'</sender></div>'
            }

            c += "</li>"
            $("ol.chat").append(c);

          }).catch(err => {
            toastr.error("Error: "+err.statusText)
          })
        });
         $(document).on("keydown",".textarea", (e) => {
          var key = e.which || e.keyCode;

          if(key == 13) {

            data = e.target.value

            payload = {
              sender_id: '<?=user()->id?>',
              receiver_id: '<?=$user->id?>',
              message: data,
              type: 'text'
            }

            if(data != "") {

              e.target.setAttribute('disabled',true)

              $.post("<?=site_url('home/storeMessage')?>", payload).then(d => {
                e.target.value = "";
                e.target.removeAttribute("disabled")
              }).catch(err => {
                toastr.error("Message sending failed, Error: "+err.statusText)
              }) 
            }
            return false;
          }
        })
         function readURL(input) {

          if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
              $('.preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
          }
        }


        $("#inputfile").change( e => {
          const f = e.target.files[0].name;
          const lastDot = f.lastIndexOf('.');

          const fileName = f.substring(0, lastDot);
          const ext = f.substring(lastDot + 1);

          const allowd = ['jpg','jpeg','gif','png','bmp'];
          if(allowd.includes(ext)) {
            readURL(e.target);
          } else {
            $(".preview").attr("src", "https://mlr.in/fileadmin/templates/mlr/images/nopreview-available.jpg");
          }
          $("div.file-name").text(fileName+"."+ext)
          $("#prv").modal("show");
        });


        el = document.querySelectorAll("button.send")[0];
        el.addEventListener("click", e => {
          d = new FormData();
          txt = document.querySelectorAll("input.media-text")[0].value;

          file = document.getElementById("inputfile").files;
          if(file.length < 1) {
            $("#prv").modal("hide");
          } else {
            d.append("file",file[0]);
            d.append("message",txt);
            d.append("sender_id",'<?=user()->id?>')
            d.append("receiver_id",'<?=$user->id?>')
            d.append('type','media');


            $.ajax({
              type: 'POST',
              url: "<?=site_url('home/storeMultipartMessage')?>",
              contentType: false,
              processData: false,
              data: d
            }).then(a => {
              a = JSON.parse(a);
              if(a.status == "error") {
                toastr.error("Error: "+a.msg)
              } else {
                $("#prv").modal("hide");
              }
            }).catch(err => {
              toaster.error("An error occurred: "+err.statusText)
            })
          }
          e.preventDefault()
        })
      </script>