<html>

<head>
    <title>Registru aplanko kurimas</title>
    <link rel="stylesheet" href="list.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</head>

<body bgcolor="#f2f2f2">

    <table id="header1">
        <tr>
            <th>
                <span style="float:center;">Jūs kuriate naują registrų aplanką</span>
            </th>
        </tr>
        <tr>
            <td>
                <a href="all.php">Atgal</a>
            </td>
        </tr>
    </table>
    <div class="container">
        <div class="form-group">
            <form name="add_name" id="add_name">
                <input type="text" name="ou" placeholder="Aplanko pavadinimas" class="form-control" />
                <div class="table-responsive">
                    <table class="table table-bordered" id="dynamic_field">
                        <tr>
                            <?php getDropdown() ?>
                            <td><input type="text" name="name[]" placeholder="Stulpelis"
                                    class="form-control name_list" /></td>
                            <td><button type="button" name="add" id="add" class="btn btn-success">+</button></td>
                        </tr>
                    </table>
                    <input type="button" name="submit" id="submit" class="btn btn-info" value="Sukurti" />
                </div>
            </form>
        </div>
    </div>
</body>

</html>
<script>
$(document).ready(function() {
    var i = 1;
    $('#add').click(function() {
        i++;
        $('#dynamic_field').append('<tr id="row' + i +
            '"><td><select name="formType[]"><option disabled value="">Lauko tipas...</option><option selected value="Text">Tekstinis</option><option value="Date">Data</option></select></td><td><input type="text" name="name[]" placeholder="Stulpelis" class="form-control name_list" /></td><td><button type="button" name="remove" id="' +
            i + '" class="btn btn-danger btn_remove">X</button></td></tr>');
    });
    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");
        $('#row' + button_id + '').remove();
    });
    $('#submit').click(function() {
        $.ajax({
            url: "proc-newList.php",
            method: "POST",
            data: $('#add_name').serialize(),
            success: function(data) {
                alert(data);
                $('#add_name')[0].reset();
            }
        });
    });
});
</script>

<?php
     function getDropdown(){
          ?><td>
    <select name="formType[]">
        <option disabled value="">Lauko tipas...</option>
        <option selected value="Text">Tekstinis</option>
        <option value="Date">Data</option>
    </select>
</td><?php
     }
?>