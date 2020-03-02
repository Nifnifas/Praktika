<html>  
      <head>  
           <title>Dynamically Add or Remove input fields in PHP with JQuery</title>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
      </head>  
      <body>  
           <div class="container">  
                <br />  
                <br />  
                <h2 align="center">Naujas irasas</h2>  
                <div class="form-group">  
                     <form action="proc-newRegistry.php" method="post">
                         <?php 
                            if (isset($_GET['name'])) {
                                $name = $_GET['name'];
                                include('connect-db.php');
                                $ds = connectToAD();
                                $result = bindAD($ds);
                                if($result){
                                    $basedn = 'OU=Registrai,OU=TableSet,DC=mycompany,DC=com';
                                    $filter = array("ou", "description");
                                    $sr = ldap_list($ds, $basedn, "ou=$name", $filter);
                                    $info = ldap_get_entries($ds, $sr);
                                    $column = "";
                                    $column = explode(";", $info[0]["description"][0]);
                                    ?>
                                   <input type="text" name="<?=0?>" placeholder="<?=$column[0]?>" class="form-control" readonly />
                                    <?php
                                    for($i = 1; $i < count($column)-1; $i++){
                                        ?>
                                            <input type="text" name="<?=$i?>" placeholder="<?=$column[$i]?>" class="form-control" /> 
                                        <?php
                                    }
                                    ?>
                                        <input type="hidden" name="count" class="form-control" value="<?=count($column)-1?>" />
                                        <input type="hidden" name="name" class="form-control" value="<?=$name?>" />
                                    <?php
                                }
                            }
                         ?>
                        
                          <div class="table-responsive">  
                               <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" />  
                          </div>  
                     </form>  
                </div>  
           </div>  
      </body>  
 </html>