<div class="well">
    <table class="table">
      <thead>
        <tr>
          <th>Arquivo</th>
          <th>Data Upload</th>
          <th>Download</th>
          <th style="width: 36px;"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php 
          $sql = mysqli_query($connection, "SELECT * FROM apswp_arquivos where destinatario = usuario_corrente or destinatario = todos_usuarios");
          while ($row = $sql->fetch_assoc()){
          ?>
          <td>Arquivo</td>
          <td>01/01/2019</td>
          <td>
              <a href="user.html">Download<i class="icon-pencil"></i></a>
          </td>
          <?php
          }
          ?>
        </tr>
      </tbody>
    </table>
</div>
<div class="pagination">
    <ul>
        <li><a href="#">Anterior</a></li>
        <li><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">Próxima</a></li>
    </ul>
</div>
<div class="modal small hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Delete Confirmation</h3>
    </div>
    <div class="modal-body">
        <p class="error-text">Are you sure you want to delete the user?</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-danger" data-dismiss="modal">Delete</button>
    </div>
</div>