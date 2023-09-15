<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<style>
.chat{
    background: #fff;
    padding: 10px 10px;
}
.chat ul li {
    padding: 6px 0px;
}

</style>
<?php init_head();?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
                <h1>Telegram</h1>
            </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <form action="telegram_chat/addTelegramInfo" method="post"> 
                <div class="form-group">
                    <label for="exampleInputEmail1">Telegram Bot Token</label>
                    <input type="text" class="form-control" name="bot_token" id="bot_token" value="<?php echo $userTeleInfo->bot_token;?>"  placeholder="Enter telegram bot token">
                    <small id="emailHelp" class="form-text text-muted">Please enter telegram Bot token without bot.</small>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Telegram Chat Id</label>
                    <input type="text" class="form-control" name="chat_id" id="chatID" value="<?php echo $userTeleInfo->chat_id;?>"  placeholder="Enter telegram chat id">
                    <small id="emailHelp" class="form-text text-muted">Please enter telegram chat id.</small>
                </div>
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                <button type="submit"  class="btn btn-primary">Submit</button>
                <!-- <button type="button" onclick="submitForm()" class="btn btn-primary">Get Messages</button> -->
            </form>
        </div>
        <!-- <div class="col-md-4 chat" >
                <ul id="chatList">
                    
                </ul>
        </div> -->
        </div>
    </div>
</div>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script>
    function submitForm() {
        var chatID = $('#bot_token').val();

        jQuery.ajax({
          type: 'get',
          url: 'https://api.telegram.org/bot'+chatID+'/getUpdates',
          success: function(data) {
            console.log('commentcomment', data);
            if(data && data.result && data.result.length > 0) {
                $("#chatList li").remove(); 
                for (var i = 0; i < data.result.length; i++) {
                    // text += cars[i] + "<br>";
                    
                    $("#chatList").append('<li>'+ data.result[i].message.from.first_name + '  : '+ data.result[i].message.text +'</li>');
                    console.log('objj', data.result[i]);
                    }
                
            }
          }
        });

        
    }
</script>