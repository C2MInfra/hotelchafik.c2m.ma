<fieldset class="tableform" >
  <form method="post" name="form_comm" >
    <table width="542" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="30%"><strong>Nom Commercial : </strong></td>
        <td width="70%"> <select name="comm"><?php 
        $result=connexion::getConnexion()->query("select id,login from utilisateur");
        $rep=$result->fetchAll();
        for ($i=0;$i<=count($rep)-1;$i++){
            echo "<option value='".$rep[$i][0]."'>".$rep[$i][1]."</option>";
        }
        ?></select></td>
      </tr>
      <tr>
        <td width="30%"><strong>Date  D&eacute;but : </strong></td>
        <td width="70%"> <input type="text" name="dd" class="inputText" id="dd"   />  </td>
      </tr>
       <tr>
        <td ><strong>Date Fin  : </strong></td>
        <td > <input type="text" name="df" class="inputText" id="df"    /> </td>
      </tr>
      <tr>
        <td colspan="2" align="center" >
            <input type="submit" class="button" onclick="document.form_facture.submit();" value="Afficher" />
        </td>
      </tr>
    </table>
  </form>
</fieldset>