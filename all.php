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
                <h2 align="center">Registru aplankalu sarasas</h2>
                <a href="newList.php">Prideti aplankala</a> | 
                <a href="all.php">Atnaujinti</a>
                <div class="form-group">  
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Pavadinimas</th>
                                <th scope="col">Stulpeliai</th>
                                <th scope="col">Valdymas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include('connect-db.php');
                                $ds = connectToAD();
                                $result = bindAD($ds);
                               
                                if($result){
                                    $basedn = 'OU=Registrai,OU=TableSet,DC=mycompany,DC=local';
                                    $filter = array("ou", "description");
                                    $sr = ldap_list($ds, $basedn, "ou=*", $filter);
                                    $info = ldap_get_entries($ds, $sr);
                                    for ($i=0; $i < $info["count"]; $i++) {  
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $i+1; ?></th>
                                    <td><a href="view.php?name=<?= $info[$i]["ou"][0]?>"><?php echo $info[$i]["ou"][0];?></a></td>
                                    <td><?php echo $info[$i]["description"][0];?></td>
                                    <td><a href="all.php">Redaguoti</a> | <a href="delete.php?id=<?= $info[$i]["ou"][0] ?>">Istrinti</a></td>
                                </tr>
                            <?php
                                    }
                                }
                                else{
                                    echo "You do not have rights to view Registries.";
                                }
                                ldap_close($ds);
                            ?>
                        </tbody>
                    </table>
                </div>  
           </div>  
      </body>  
 </html>