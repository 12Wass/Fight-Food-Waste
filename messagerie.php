<?php
require_once("php/classes/classIncluder.php");
if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] == true){
    $currentUserId = $_SESSION['user']->getId();
    $database = Database::getDatabaseConnection();
    $getConversations = $database->prepare("SELECT * FROM conversation WHERE idSender = ? OR idReceiver = ? ORDER BY lastMessageDate DESC");
    $getConversations->execute(array($currentUserId, $currentUserId));

    if (!isset($_GET['uid'])) {
        $getFirstConversation = $database->prepare("SELECT * FROM conversation WHERE idSender = ? OR idReceiver = ? ORDER BY lastMessageDate DESC LIMIT 1");
        $getFirstConversation->execute([$currentUserId, $currentUserId]);
        $fConv = $getFirstConversation->fetch(PDO::FETCH_ASSOC);
    }
    else{
        $getFirstConversation = $database->prepare("SELECT * FROM conversation WHERE idSender = ? OR idReceiver = ? ORDER BY lastMessageDate DESC LIMIT 1");
        $getFirstConversation->execute([$_GET['uid'], $_GET['uid']]);
        $fConv = $getFirstConversation->fetch(PDO::FETCH_ASSOC);
    }
    ?>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->


<html>
<head>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/messagerie.css">
    <link rel="stylesheet" href="css/uikit.min.css" />
    <script src="js/uikit.min.js"></script>
    <script src="js/messagerie.js"></script>
    <script src="js/uikit-icons.min.js"></script>
</head>
<body onload="selectConversation(<?php echo $fConv['id']; ?>)">
<?php require_once('php/templates/Navbar.php'); ?>

<div class="container">
    <h3 class=" text-center">Messagerie</h3>

    <div class="messaging">
        <div class="inbox_msg">
            <div class="inbox_people">
                <div class="headind_srch">
                    <div class="recent_heading">
                        <h4>Conversations</h4>
                    </div>
                    <div class="srch_bar">
                        <div class="stylish-input-group">
                            <div id="typeSearch" style="visibility: hidden;" class="userMessage"></div>
                            <input type="text" class="search-bar" placeholder="Rechercher" name="userMessage" id="searchBox">
                            <span class="input-group-addon">
                                 <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                             </span>
                            <div id="rewrite"></div>
                        </div>
                    </div>
                </div>
                <div class="inbox_chat">

                    <!-- Conversations récentes, volet de gauche -->
                            <!-- active_chat : conversation sélectionnée -->
                    <?php
                     foreach($getConversations as $conv){
                         $m = new Conversation(NULL, NULL, NULL);
                         insertValuesInConversation($m, $conv);
                         echo '
                        <a onclick="selectConversation('. $m->getId() .')">
                            <div class="chat_list" id="chatNotification_'. $m->getId() .'">
                                   <div class="chat_people">
                                        <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                                            <div class="chat_ib" id="idConversation" value="'. $m->getId() .'">
                                              <h5 id="idReceiver" value="'. $m->getUserInfos()['id'] .'">'. $m->getUserInfos()['firstName']  .'<span class="chat_date">'. $m->getLastMessageDate()['date'] .'</span></h5>
                                                 <p>'.  $m->getLastMessage()['content'] .'</p>
                                         </div>
                                    </div>
                             </div>
                        </a>';
                     }
                    ?>
                </div>
            </div>


            <div class="mesgs">
                <div class="msg_history" id="msg_history"></div>
                <div class="type_msg"> <!-- PERMETS D'ECRIRE UN MESSAGE -->
                    <div class="input_msg_write">
                        <input type="text" id="messageToSend" class="write_msg" placeholder="Type a message" />
                        <button class="msg_send_btn" onclick="sendMessage(<?php echo $currentUserId; ?>)"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                       <!-- <button onclick="launchReload()" uk-icon="refresh"></button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
        function init(){
            setInterval(
                function(){
                    let idConv = document.getElementById("selectedConversation").getAttribute('value');
                    isNewMessages(idConv)
                }, 2000);
        }
        document.addEventListener("DOMContentLoaded", function(event) { init()});
</script>
<script src="js/newSearch.js"></script>
</html>

<?php } else { echo "Vous n'avez pas le droit d'être ici ! "; exit(); } ?>