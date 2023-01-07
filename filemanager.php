<?php
include "DBModel.php";
$currentFolder = null;

session_start();
if(!isset($_SESSION['username']))
    {
        $_SESSION['msg'] = 'You must login first';
        header('location : login.php');
    }
    if(isset($_GET['logout']))
    {
        session_destroy();
        unset($_SESSION['username']);
        header('location:login.php');
    }

define("WEBSITE_BASE_PATH","http://localhost:80/File_Manager/");

// print($_SESSION['userid']);

function get_random_name($num = 6){
  $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
  $string = '';
  $max = strlen($characters) - 1;
  for ($i = 0; $i < $num; $i++) {
    $string .= $characters[mt_rand(0, $max)];
  }
  return $string;
}

function reload_to_page($page){
  header("Location: $page");
  exit();
}

function reload_current_directory(){
  reload_to_page(get_current_url());
}

function get_current_url(){
  if(in_folder()){
    return create_url("filemanager.php",['id'=>get_folder_id()]);
  }else{
    return create_url('filemanager.php');
  }
}

function create_url($path, $arguments=[]){
  $url = WEBSITE_BASE_PATH;
  if($path != "/"){
    $url .= $path;
  }
  if(count($arguments) > 0){
    $url .= "?";
    foreach ($arguments as $key=> $value){
        $url .= $key . "=" . $value;
    }
  }
  return $url;
}



function save_to_file_manager($filename, $realfilename, $type, $size, $folder){
  $model = new DBModel("file_manager");
  $model->insertRecord([
    'file_name' => $filename,
    'real_name'=> $realfilename,
    'file_size' => $size,
    'file_type' => $type,
    'folder'=> $folder,
    'user_id' => $_SESSION['userid']
  ]);
}

function save_a_folder($name, $parent){
  $model = new DBModel("file_manager_folder");
  $model->insertRecord([
    'name' => $name,
    'parent' => $parent,
    'user_id' => $_SESSION['userid']
  ]);
}


function get_all_files(){
  $model = new DBModel("file_manager");
  $id=$_SESSION['userid'];
  if(in_folder()){
    return $model->getAllBySQL("SELECT * from file_manager Where user_id=$id AND folder=?",[get_folder_id()]);
  }else{
    return $model->getAllBySQL("SELECT * from file_manager Where user_id=$id AND (folder=0 OR folder IS NULL)");
  }
}

function get_all_parent_folders(){
  $model = new DBModel("file_manager");
  $id=$_SESSION['userid'];
  if(in_folder()){
    return $model->getAllBySQL("SELECT * from file_manager_folder WHERE user_id=$id AND parent=?",[get_folder_id()]);
  }else{
    return $model->getAllBySQL("SELECT * from file_manager_folder WHERE user_id=$id AND parent IS NULL");
  }
}

function create_file_link($name){
  return WEBSITE_BASE_PATH . "uploads/". $name;
}

function in_folder(){
  if(get_folder_id()){
    return true;
  }
  return false;
}

function get_folder_id(){
  if(isset($_GET['id']) && !empty($_GET['id'])){
    return $_GET['id'];
  }
  return null;
}

function getCurrentFolder($id){
  global $currentFolder;
  if(isset($currentFolder["id"]) && $currentFolder["id"] == $id){
    return $currentFolder;
  }else{
    $model = new DBModel("file_manager_folder");
    $currentFolder = $model->getOneRecordByPk($id);
    return $currentFolder;
  }
}

function get_current_folder_name(){
  if(in_folder()){
    return "(" . getCurrentFolder(get_folder_id())["name"] . ")";
  }
}

function parent_up_url(){
  $folder = getCurrentFolder(get_folder_id());
  if($folder["parent"]){
    return create_url("filemanager.php",['id'=>$folder["parent"]]);
  }else{
    return create_url("filemanager.php");
  }
}

function get_icon($type, $name=""){
  if (strpos($type, 'excel') !== false) {
    return "<img src='ic_excel.png' style='width: 20px'/>";
  }else if(strpos($type, 'audio') !== false){
    return "<img src='ic_music.png' style='width: 20px'/>";
  }else if(strpos($type, 'pdf') !== false){
    return "<img src='file-pdf-solid.svg' style='width: 16px'/>";
  }else if(strpos($type, 'openxmlformats') !== false){
    if(strpos($name, 'doc') !== false){
      return "<img src='ic_word.png' style='width: 20px'/>";
    }else if(strpos($name, 'ppt') !== false){
      return "<img src='ic_power_point.png' style='width: 20px'/>";
    }else if(strpos($name, 'xlsx') !== false){
      return "<img src='ic_excel.png' style='width: 20px'/>";
    }else{
      return "<img src='ic_file.png' style='width: 20px'/>";
    }
  }else if(strpos($type, 'image') !== false){
    return "<img src='ic_picture.png' style='width: 20px'/>";
  }else if(strpos($type, 'video') !== false){
    return "<img src='ic_video.png' style='width: 20px'/>";
  }else{
    return "<img src='ic_file.png' style='width: 20px'/>";
  }
}


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
  $folder = isset($_POST['folder']) ? $_POST['folder'] : null;

  $uploadDir = "./uploads/";

  // file upload section
  if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0){
    $filename = $_FILES["file"]["name"];
    $filetype = $_FILES["file"]["type"];
    $filesize = $_FILES["file"]["size"];
    $random_name = get_random_name()  ."." . pathinfo($filename, PATHINFO_EXTENSION);
    if(file_exists($uploadDir . $random_name)){
      echo $filename . " is already exists.";
    } else{
      move_uploaded_file($_FILES["file"]["tmp_name"], $uploadDir . $random_name);
      //save_to_gallery($random_name, $filename);
      save_to_file_manager($random_name, $filename, $filetype, $filesize, get_folder_id());
      reload_current_directory();
    }
  }else{
    if($folder){
      save_a_folder($folder, get_folder_id());
      reload_current_directory();
    }
  }

}


// renaming the file

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['file'])){

  if(is_numeric($_GET['file']) && isset($_GET['name'])){
    $model = new DBModel("file_manager");
    $record = $model->getOneRecordByPk($_GET['file']);
    $original =  $record["real_name"];
    $orig_extension = pathinfo($original, PATHINFO_EXTENSION);
    $model->updateRecordByPk($_GET['file'],[
      'real_name' => $_GET["name"] . "." . $orig_extension
    ]);
    reload_current_directory();
  }
}

// renaming the folder

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['folder'])){
  if(is_numeric($_GET['folder']) && isset($_GET['name'])){
    $model = new DBModel("file_manager_folder");
    $record = $model->getOneRecordByPk($_GET['folder']);
    $original =  $record["name"];
    $model->updateRecordByPk($_GET['folder'],[
      'name' => $_GET["name"]
    ]);
    reload_current_directory();
  }
}

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'delete'){

  $id = isset($_GET['item']) ? $_GET['item'] : null;
  if(isset($_GET['type']) && $_GET['type'] == 'file'){
    $model = new DBModel("file_manager");
    $model->deleteRecordByPk($id);
  }else if(isset($_GET['type']) && $_GET['type'] == 'folder'){
    $model = new DBModel("file_manager_folder");
    $model->deleteRecordByPk($id);
  }
  reload_current_directory();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <script src="https://use.fontawesome.com/3903c9d7fd.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.css" rel="stylesheet">
  <title>File Manager</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.9.2/jquery.contextMenu.css" integrity="sha512-EF5k2tHv4ShZB7zESroCVlbLaZq2n8t1i8mr32tgX0cyoHc3GfxuP7IoT8w/pD+vyoq7ye//qkFEqQao7Ofrag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css" integrity="sha512-0V10q+b1Iumz67sVDL8LPFZEEavo6H/nBSyghr7mm9JEQkOAm91HNoZQRvQdjennBb/oEuW+8oZHVpIKq+d25g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.9.2/jquery.contextMenu.js" integrity="sha512-2ABKLSEpFs5+UK1Ol+CgAVuqwBCHBA0Im0w4oRCflK/n8PUVbSv5IY7WrKIxMynss9EKLVOn1HZ8U/H2ckimWg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.9.2/jquery.contextMenu.min.js" integrity="sha512-kvg/Lknti7OoAw0GqMBP8B+7cGHvp4M9O9V6nAYG91FZVDMW3Xkkq5qrdMhrXiawahqU7IZ5CNsY/wWy1PpGTQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js" integrity="sha512-zP5W8791v1A6FToy+viyoyUUyjCzx+4K8XZCKzW28AnCoepPNIXecxh9mvGuy3Rt78OzEsU+VCvcObwAMvBAww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<style>
 *, ::after, ::before {
  box-sizing: border-box;
}

body {
  background: rgb(71,102,149);
background: linear-gradient(90deg, rgba(71,102,149,1) 0%, rgba(22,57,69,1) 61%, rgba(18,9,41,1) 100%);
  color: #fff;
  font-family: monospace, serif;
  letter-spacing: 0.05em;
} 
  table {
    width: 100%;
  }
  table, th, td {

  }
 .fileform{
   text-align: center;
   padding-top:100px;
   color:#1BD5E5;
 }
 h1{
  text-align: center;
  /* background: #13CFA3;
background: linear-gradient(to right, #13CFA3 47%, #133766 90%); */
/* -webkit-background-clip: text;
-webkit-text-fill-color: transparent; */
/* font-weight:bold;
font-size:50px; */
  padding-right: 90px;
 }

 .logo{
  text-align: center;
  padding-right: 90px;
 }
 .para1{
  text-align:center;
  padding-top:30px;
  color:#1BD5E5; 
 }
 .form {
  width: 300px;
  /* padding: 150px 15px 24px; */
  padding-top: 20px;
  padding-left: 7px;
  margin: 0 auto;
}
.form .control {
  margin: 0 0 24px;
}
.form .control input {
  width: 100%;
  padding: 14px 16px;
  border: 0;
  background: transparent;
  color: #fff;
  font-family: monospace, serif;
  letter-spacing: 0.05em;
  font-size: 16px;
}
.form .control input:hover, .form .control input:focus {
  outline: none;
  border: 0;
}
.form .btn {
  width: 100%;
  display: block;
  padding: 14px 16px;
  background: transparent;
  outline: none;
  border: 0;
  color: #fff;
  letter-spacing: 0.1em;
  font-weight: bold;
  font-family: monospace;
  font-size: 16px;
}

.block-cube {
  position: relative;
}
.block-cube .bg-top {
  position: absolute;
  height: 10px;
  background: #020024;
  background: linear-gradient(90deg, #020024 0%, #340979 37%, #00d4ff 94%);
  bottom: 100%;
  left: 5px;
  right: -5px;
  transform: skew(-45deg, 0);
  margin: 0;
}
.block-cube .bg-top .bg-inner {
  bottom: 0;
}
.block-cube .bg {
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  background: rgb(71,145,149);
background: linear-gradient(90deg, rgba(71,145,149,1) 0%, rgba(4,25,32,1) 55%, rgba(88,17,77,1) 100%);;
}
.block-cube .bg-right {
  position: absolute;
  background: #020024;
  background: #00d4ff;
  top: -5px;
  z-index: 0;
  bottom: 5px;
  width: 10px;
  left: 100%;
  transform: skew(0, -45deg);
}
.block-cube .bg-right .bg-inner {
  left: 0;
}
.block-cube .bg .bg-inner {
  transition: all 0.2s ease-in-out;
}
.block-cube .bg-inner {
  background: #212121;
  position: absolute;
  left: 2px;
  top: 2px;
  right: 2px;
  bottom: 2px;
}
.block-cube .text {
  position: relative;
  z-index: 2;
}
.block-cube.block-input input {
  position: relative;
  z-index: 2;
}
.block-cube.block-input input:focus ~ .bg-right .bg-inner, .block-cube.block-input input:focus ~ .bg-top .bg-inner, .block-cube.block-input input:focus ~ .bg-inner .bg-inner {
  top: 100%;
  background: rgba(255,0,207,1);
}
.block-cube.block-input .bg-top,
.block-cube.block-input .bg-right,
.block-cube.block-input .bg {
  background: rgba(255,0,207,1);
  transition: background 0.2s ease-in-out;
}
.block-cube.block-input .bg-right .bg-inner,
.block-cube.block-input .bg-top .bg-inner {
  transition: all 0.2s ease-in-out;
}
.block-cube.block-input:focus .bg-top,
.block-cube.block-input:focus .bg-right,
.block-cube.block-input:focus .bg, .block-cube.block-input:hover .bg-top,
.block-cube.block-input:hover .bg-right,
.block-cube.block-input:hover .bg {
  background: rgba(255, 255, 255, 0.8);
}
.block-cube.block-cube-hover:focus .bg .bg-inner, .block-cube.block-cube-hover:hover .bg .bg-inner {
  top: 100%;
}





.Example-btn8{ 
 font-family: Koulen; 
 font-weight: 0;
 font-size: 20px;
 color: #000000;
 background-color: #ffffff;
 padding: 5px 50px;
 border: solid #000000 2px;
 box-shadow: rgba(0, 0, 0, 0.15) 0px 2px 8px;
 border-radius: 1px;
 transition : 1000ms;
 transform: translateY(0);
 text-transform: uppercase;
 }
 
 .Example-btn8:hover{ 
 transition : 1000ms; 
 padding: 10px 44px; 
 transform : translateY(-0px);
 background-color: #000000;
 color: #ffffff;
 border: solid 2px #ffffff;
 }

 .wrapper{
  position: fixed;
  top: 0;
  /*left: -100%;*/
  right: -100%;
  height: 100%;
  width: 100%;
  background: #000;
  /*background: linear-gradient(90deg, #f92c78, #4114a1);*/
  /* background: linear-gradient(375deg, #1cc7d0, #2ede98); */
 /* background: linear-gradient(-45deg, #e3eefe 0%, #efddfb 100%);*/
  transition: all 0.6s ease-in-out;
}
#active:checked ~ .wrapper{
  /*left: 0;*/
  right:0;
}
.menu-btn{
  position: absolute;
  z-index: 2;
  right: 20px;
  /*left: 20px; */
  top: 20px;
  height: 50px;
  width: 50px;
  text-align: center;
  line-height: 50px;
  border-radius: 50%;
  font-size: 20px;
  cursor: pointer;
  /*color: #fff;*/
  /*background: linear-gradient(90deg, #f92c78, #4114a1);*/
  /* background: linear-gradient(375deg, #1cc7d0, #2ede98); */
 /* background: linear-gradient(-45deg, #e3eefe 0%, #efddfb 100%); */
  transition: all 0.3s ease-in-out;
}
.menu-btn span,
.menu-btn:before,
.menu-btn:after{
	content: "";
	position: absolute;
	top: calc(50% - 1px);
	left: 30%;
	width: 40%;
	border-bottom: 2px solid #000;
	transition: transform .6s cubic-bezier(0.215, 0.61, 0.355, 1);
}
.menu-btn:before{
  transform: translateY(-8px);
}
.menu-btn:after{
  transform: translateY(8px);
}


.close {
	z-index: 1;
	width: 100%;
	height: 100%;
	pointer-events: none;
	transition: background .6s;
}

/* closing animation */
#active:checked + .menu-btn span {
	transform: scaleX(0);
}
#active:checked + .menu-btn:before {
	transform: rotate(45deg);
  border-color: #fff;
}
#active:checked + .menu-btn:after {
	transform: rotate(-45deg);
  border-color: #fff;
}
.wrapper ul{
  position: absolute;
  top: 60%;
  left: 50%;
  height: 90%;
  transform: translate(-50%, -50%);
  list-style: none;
  text-align: center;
}
.wrapper ul li{
  height: 10%;
  margin: 15px 0;
}
.wrapper ul li a{
  text-decoration: none;
  font-size: 30px;
  font-weight: 500;
  padding: 5px 30px;
  color: #fff;
  border-radius: 50px;
  position: absolute;
  line-height: 50px;
  margin: 5px 30px;
  opacity: 0;
  transition: all 0.3s ease;
  transition: transform .6s cubic-bezier(0.215, 0.61, 0.355, 1);
}
.wrapper ul li a:after{
  position: absolute;
  content: "";
  background: #fff;
   /*background: linear-gradient(#14ffe9, #ffeb3b, #ff00e0);*/
  /*background: linear-gradient(375deg, #1cc7d0, #2ede98);*/
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
  border-radius: 50px;
  transform: scaleY(0);
  z-index: -1;
  transition: transform 0.3s ease;
}
.wrapper ul li a:hover:after{
  transform: scaleY(1);
}
.wrapper ul li a:hover{
  color: #1a73e8;
}
input[type="checkbox"]{
  display: none;
}
.content{
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: -1;
  /* text-align: center; */
  width: 100%;
  color: #202020;
}
.content .title{
  font-size: 40px;
  font-weight: 700;
}
.content p{
  font-size: 35px;
  font-weight: 600;
}

#active:checked ~ .wrapper ul li a{
  opacity: 1;
}
.wrapper ul li a{
  transition: opacity 1.2s, transform 1.2s cubic-bezier(0.215, 0.61, 0.355, 1);
  transform: translateX(100px);
}
#active:checked ~ .wrapper ul li a{
	transform: none;
	transition-timing-function: ease, cubic-bezier(.1,1.3,.3,1); /* easeOutBackを緩めた感じ */
   transition-delay: .6s;
  transform: translateX(-100px);
}
 
</style>
<body>
<input type="checkbox" id="active">
    <label for="active" class="menu-btn"><span></span></label>
    <label for="active" class="close"></label>
    <div class="wrapper">
      <ul>
<li><a href="#">Home</a></li>
<li><a href="#">About</a></li>
<li><a href="home.php">Profile</a></li>
<!-- <li><a href="#">Gallery</a></li> -->
<li><a href="logout.php">Logout</a></li>
</ul>
</div>
<div class="content">
    <!-- <h1>
  <video width-"250px" height="250px" controls loop>
    <source src="FileCloud.mp4">
    NOT SUPPORTED
  </video> 
  <img src="FileCloud.png" style="width: 200px; height: 200px" />
</h1> -->
<div class="logo">
  <img src="FileCloud.png" style="width: 200px;" />
</div>
<!-- <p class="para1">Add a file</p> -->
<form method="post" enctype="multipart/form-data" class="fileform" style="padding-right:100px;">
  <input type="file" name="file"/>
  OR &nbsp;&nbsp;&nbsp;&nbsp;
  Folder: <input type="text" name="folder">
  <br>
  <br>
  <br>
  <button class='Example-btn8' type="submit" name="submit">Submit</button>
  <!-- <a href="logout.php">
    <button class='Example-btn8' type='submit' name="logout">
      <div class='bg-top'>
        <div class='bg-inner'></div>
      </div>
      <div class='bg-right'>
        <div class='bg-inner'></div>
      </div>
      <div class='bg'>
        <div class='bg-inner'></div>
      </div>
      <div class='text'>
        Logout
      </div>
    </button>    
  </a> -->
</form>
<!-- <form action="logout.php" method="get" class="form">
  <button class='Example-btn8' type='submit' name="logout">
      <div class='bg-top'>
        <div class='bg-inner'></div>
      </div>
      <div class='bg-right'>
        <div class='bg-inner'></div>
      </div>
      <div class='bg'>
        <div class='bg-inner'></div>
      </div>
      <div class='text'>
        Logout
      </div>
    </button>   
       <input type="submit" value="LOGOUT" name="logout"> 
</form> -->

<br><br>
<h2><a href="<?Php echo create_url("filemanager.php");?>"><img src="house-user-solid.svg" style="width : 20px"></a> Files And Folders <?php echo get_current_folder_name();?></h2>

<table>
  <thead>
    <tr>
      <td>Name</td>
      <td>Date</td>
      <td>Type</td>
      <td>Size</td>
    </tr>
  </thead>
  <tbody>
      <?php if(in_folder()):?>
        <tr>
          <td colspan="4"><img src='folder-open-solid.svg' style='width: 20px'/> &nbsp;
            <a href="<?php echo parent_up_url();?>">...</a></td>
        </tr>
      <?php endif;?>
      <?php foreach(get_all_parent_folders() as $folderObject):?>
        <tr>
          <td><img src='folder-open-solid.svg' style='width: 20px'/> &nbsp;
            <a data-id="<?php echo $folderObject["id"];?>" data-type="folder" data-filename="<?php echo $folderObject["name"];?>" class="item" href="<?php echo create_url('filemanager.php',['id'=>$folderObject["id"]]);?>">
              <?php echo $folderObject["name"];?></a>
          </td>
          <td><?php  echo $folderObject["createdOn"];?></td>
          <td>File Folder</td>
          <td></td>
        </tr>
      <?php endforeach ;?>
      <?php foreach (get_all_files() as $fObject):?>
          <tr>
              <td><?php echo get_icon($fObject["file_type"],$fObject["real_name"]);?> &nbsp;
                <a data-id="<?php echo $fObject["id"];?>" data-type="file" data-filename="<?php echo $fObject["real_name"];?>" class="item" target="_blank" href="<?php echo create_file_link($fObject["file_name"]);?>">
                  <?php echo $fObject["real_name"];?></a>
              </td>
              <td><?php  echo $fObject["createdOn"];?></td>
              <td><?php  echo $fObject["file_type"];?></td>
              <td><?php  echo $fObject["file_size"];?></td>
          </tr>
      <?php endforeach; ?>
  </tbody>
</table>
<script>

  var CURRENT_BASE_PATH = "<?php echo get_current_url();?>";
  var IS_DIRECTORY = "<?Php echo in_folder()?>";
  $(document).ready(function(){

    rightClickEvents();

    function rightClickEvents(){
      $.contextMenu({
        // define which elements trigger this menu
        selector: ".item",
        // define the elements of the menu
        items: {
          "rename": {name: "Rename",  icon:'add', callback: function(key, opt){
              var id = $(this).attr('data-id');
              var ftype = $(this).attr('data-type');
              var fname = $(this).attr('data-filename');
              renamePrompt(id, ftype, fname);
            }},
          "delete": {name: "Delete", icon:'delete', callback: function(key, opt){
              var id = $(this).attr('data-id');
              var ftype = $(this).attr('data-type');
              var fname = $(this).attr('data-filename');
               deleteItem(id, ftype);
            }},
          // "download": {name: "Download",icon:'remove'}
        }
      });
    }

    function renamePrompt(id, filetype, filename){
      if(IS_DIRECTORY){
        var formAction = CURRENT_BASE_PATH + "&"+filetype+"=" + id;
      }else{
        var formAction = CURRENT_BASE_PATH + "?"+filetype+"=" + id;
      }
      $.confirm({
        useBootstrap: false,
        title: 'Rename '+ filename,
        content: '' +
          '<form action="'+formAction+'" class="formName">' +
          '<div class="form-group">' +
          '<label>Rename '+filetype+' to</label>' +
          '<input type="text" placeholder="name" class="name form-control" required />' +
          '</div>' +
          '</form>',
        buttons: {
          formSubmit: {
            text: 'Submit',
            btnClass: 'btn-blue',
            action: function () {
              var name = this.$content.find('.name').val();
              if(!name){
                $.alert('provide a valid name');
                return false;
              }else{
                formAction += "&name=" + name;
                window.location.href = formAction;
              }
            }
          },
          cancel: function () {
            //close
          },
        },
      });
    }

    function deleteItem(item, itemtype){
      if(confirm("Are you sure you want to delete this item")){
        if(IS_DIRECTORY){
          var deleteAction = CURRENT_BASE_PATH + "&type="+itemtype+"&item="+item+'&action=delete';
        }else{
          var deleteAction = CURRENT_BASE_PATH + "?type="+itemtype+"&item=" + item+'&action=delete';
        }
        window.location.href = deleteAction;
      }
    }

  });
</script>
</div>

</body>
</html>


