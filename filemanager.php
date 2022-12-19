<?php

define("WEBSITE_BASE_PATH","http://localhost:80/File_Manager/");

    function in_folder()
    {
        if(get_folder_id())
        {
            return true;
        }
        return false;
    }

    function create_url($path,$arguments=[])
    {
        $url = WEBSITE_BASE_PATH;
        if($path != "/")
        {
            $url .= $path;
        }
        if(count($arguments) > 0)
        {
            $url .= "?";
            foreach ($arguments as $key=> $value){
                $url .= $key . "=" . $value;
            }
          }
          return $url;
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

    function get_folder_id(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
          return $_GET['id'];
        }
        return null;
      }

    function get_current_folder_name()
    {
        if(in_folder())
        {
            return "(" . getCurrentFolder(get_folder_id())["name"] . ")";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FILE MANAGER</title>
</head>
<body>
    <h1>FILE MANAGER</h1>
    <p>Add a file</p>
    <form method="post" enctype="multipart/form-data">
        File: <input type="file" name="file">
        &nbsp; <!-- Non Breaking Space -->
        &nbsp;
        Folder: <input type="text" name="folder">
        &nbsp;
        &nbsp;
        <button type="submit" name="submit">SUBMIT</button>
    </form>
    <br>
    <br>
    <h2><a href="<?php echo create_url("filemanager.php");?>">FILES AND FOLDERS</a><?php echo get_current_folder_name();?></h2>
    <table>
        <th>
            <tr>
                <td>Name</td>
                <td>Date</td>
                <td>Type</td>
                <td>Size</td>
            </tr>
        </th>
    </table>
</body>
</html>