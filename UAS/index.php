<?php
    //db prep
    $db = new \stdClass();
    $db->host = "localhost";
    $db->user = "user";
    $db->pass = "pass";
    $db->name = "dbname";
    $mysql1 = new mysqli($db->host,$db->user,$db->pass,$db->name);
    $mysql2 = new mysqli($db->host,$db->user,$db->pass,$db->name);
    if(isset($_POST['rekening_asal'])&&isset($_POST['rekening_tujuan'])&&isset($_POST['jumlah'])){
        $notice = "";
        $asal = $_POST['rekening_asal'];
        $tujuan = $_POST['rekening_tujuan'];
        $jumlah = $_POST['jumlah'];
        if($asal==$tujuan){
            $notice = "Rekening asal sama dengan rekening tujuan!";
        } else {
            if(is_numeric($jumlah)){
                $res = $mysql1->query("CALL notice_tf($asal,$tujuan,$jumlah)");
                $notice = $res->fetch_all()[0][0];
            } else {
                $notice = "Jumlah transfer tidak valid!";
            }
        }
    }
    $result = $mysql2->query("select * from nasabah");
    if($result->num_rows>0){
        $nasabah = $result->fetch_all();
    } else {
        $nasabah = false;
    }
?>
<html>
    <head>
        <title>UAS Distributed DB</title>
    </head>
    <body bgcolor="#000000" style="color:#ffffff">
        <div id="logo">
            <h1>Bank XYZ</h1>
        </div>
        <div id="list_nasabah">
            <fieldset>
                <legend>Daftar Nasabah</legend>
                <table id="nasabah" style="border:solid 1px #ffffff;">
                    <thead style="background-color:#444444">
                        <th>Nomor Rekening</th>
                        <th>Pemilik Rekning</th>
                        <th>Saldo</th>
                    </thead>
                    <?php 
                        if($nasabah){
                            for($row = 0;$row<sizeof($nasabah);$row++){
                                ?>
                                    <tbody style='background-color:#666666'>
                                        <td style="text-align:center"><?php echo($nasabah[$row][0]);?></td>
                                        <td><?php echo($nasabah[$row][1]);?></td>
                                        <td style="text-align:right"><?php echo($nasabah[$row][2]);?></td>
                                    </tbody>
                                <?php 
                            };
                        } else { ?>
                            <tbody>
                                <td colspan="3">Tidak ada data!</td>
                            </tbody>
                            <?php
                        }
                    ?>
                </table>
            </fieldset>
        </div>
        <?php if(isset($notice)){ ?>
            <div id="notice">
                <fieldset>
                    <legend>Notice:</legend>
                    <label><?php echo($notice);?></label>
                </fieldset>
            </div>
        <?php }?>
        <?php if($nasabah){?>
            <div id="trx_nasabah">
                <fieldset>
                    <legend>Transfer</legend>
                    <form id="trx" action="." method="POST">
                        <table>
                            <tr>
                                <td>Rekening Asal: </td>
                                <td>
                                    <select name="rekening_asal" required>
                                        <option value="">-- Pilih Rekening --</option>
                                        <?php for($row = 0;$row<sizeof($nasabah);$row++){?>
                                            <option value="<?php echo($nasabah[$row][0])?>"><?php echo($nasabah[$row][0]." - ".$nasabah[$row][1])?></option>
                                        <?php }?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Rekening Tujuan: </td>
                                <td>
                                    <select name="rekening_tujuan" required>
                                        <option value="">-- Pilih Rekening --</option>
                                        <?php for($row = 0;$row<sizeof($nasabah);$row++){?>
                                            <option value="<?php echo($nasabah[$row][0])?>"><?php echo($nasabah[$row][0]." - ".$nasabah[$row][1])?></option>
                                        <?php }?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Jumlah Transfer:</td>
                                <td><input type="number" name="jumlah" required></input></td>
                            </tr>
                            <tr style="text-align:center">
                                <td colspan="2">
                                    <button type="submit">Transfer</button>
                                    <button type="reset">Reset</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </fieldset>
            </div>
        <?php }?>
    </body>
</html>
