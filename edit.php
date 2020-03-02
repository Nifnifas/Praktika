<html>  
      <head>  
           <title>Dynamically Add or Remove input fields in PHP with JQuery</title>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
      </head>  
      <body>
      <?php
        if (isset($_GET['id']) && isset($_GET['name'])) {
            $id = $_GET['id'];
            $name = $_GET['name'];
            include('connect-db.php');
            $ds = connectToAD();
            $result = bindAD($ds);
      ?>
                
                <div class="container">  
                <br />  
                <br />  
                <h2 align="center">*<?php echo $id; ?>* iraso redagavimas</h2>  
                <div class="form-group">  
                     <form action="proc-edit.php" method="post">
                         <?php 
                                if($result){
                                    $basedn = "OU=$id,OU=$name,OU=Registrai,OU=TableSet,DC=mycompany,DC=local";
                                    $filter = array("ou", "description");
                                    $sr = ldap_list($ds, $basedn, "ou=*", $filter);
                                    $info = ldap_get_entries($ds, $sr);
                                    ?>
                                    <input type="text" name="<?= 0 ?>" value="<?=$info[0]["description"][0]?>" class="form-control" readonly /> 
                                    <?php
                                    for($i = 1; $i < $info["count"]; $i++){
                                        ?>
                                            <input type="text" name="<?= $i ?>" value="<?=$info[$i]["description"][0]?>" class="form-control" /> 
                                        <?php
                                        //turetum id padaryt auto-increment, jis bus nekeiciamas.
                                    }
                                    ?>
                                        <input type="hidden" name="count" class="form-control" value="<?=$info["count"]?>" />
                                        <input type="hidden" name="name" class="form-control" value="<?=$name?>" />
                                    <?php
                                }
                            
                         ?>
                        
                          <div class="table-responsive">  
                               <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" />  
                          </div>  
                     </form>  
                </div>  
           </div>
           <?php
            }
          ?>  
           
      </body>  
 </html>