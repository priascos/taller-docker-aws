<form id="archivosForm"
  action="<?php echo url_for('archivos/'.($archivosForm->getObject()->isNew() ? 'create' : 'update').(!$archivosForm->getObject()->isNew() ? '?id='.$archivosForm->getObject()->getId() : '')) ?>"
  method="post"
  <?php $archivosForm->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <?php if (!$archivosForm->getObject()->isNew()): ?>
  <input type="hidden" name="sf_method" value="put" />
  <?php endif; ?>
<h2> Nuevo Archivo </h2>
<table class="table table-responsive table-condensed table-striped">
    <tr>
      <td>
          <?php echo $archivosForm['archivo']->renderError() ?>
          <?php echo $archivosForm['archivo']->render()?>
      </td>
      <th>
          Descripción
      </th>
      <td>
          <?php echo $archivosForm['nombre']->renderError() ?>
          <?php echo $archivosForm['nombre']->render()?>
      </td>
    </tr>
    <tfoot>
        <tr>
            <td colspan="3">
                <input id="btn-submitcargararchivos" style="font-size:12px !important" class="btn btn-info" type="submit" value="Guardar" />
            </td>
        </tr>
    </tfoot>  
</table>


<div style="display: none">
<?php echo $archivosForm['fecha']?>
</div>
<?php echo $archivosForm['_csrf_token']; ?>
<?php echo $archivosForm->renderHiddenFields() ?>

</form>  
<?php if(count($archivoss)){ ?>
<table class="table table-condensed table-responsive table-striped">
  <thead>
    <tr>
      <th>Id</th>
      <th style="text-align: center">Archivo</th>
      <th>Descripción</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($archivoss as $archivos): 
      if ($archivos->getArchivo()){?>
    <tr>
      <td>
          <a class="btn btn-info" href="<?php echo "/uploads/archivo/".$archivos->getArchivo() ?>" >  <?php echo $archivos->getId() ?> </a>
      </td>
      <td class="center-all">
      <?php $cadena = $archivos->getArchivo(); 
        $buscar = ".jpg";
        $resultado = strpos($cadena, $buscar); 

        if($resultado == true)
        {
            echo '<img width ="100" src="/uploads/archivo/'.$archivos->getArchivo().'" />';
        }
        else
        {
            $buscar = ".png";
            $resultado = strpos($cadena, $buscar);

            if($resultado == true)
            {
                echo '<img width ="100" src="/uploads/archivo/'.$archivos->getArchivo().'" />';
            }
            else
            {
                $buscar = ".pdf";
                $resultado = strpos($cadena, $buscar);

                if($resultado == true)
                {
                    echo link_to('<i class="fa fa-download fa-2x text-info"></i>', '/uploads/archivo/'.$archivos->getArchivo() , array('target' => '_blank'));


                }
            }    

        }
    ?>
    </td>

      <td><?php echo $archivos->getnombre() ?></td>
      <td><?php echo link_to('<i class="fa fa-trash-o fa-2x text-info"></i>', 'archivos/delete?id='.$archivos->getId(), array('method' => 'delete', 'confirm' => 'Está seguro de borrar el archivo?')) ?></td>
    </tr>
      <?php }endforeach; ?>
  </tbody>
</table>
<?php }else{ ?>
<span class="text-muted">No hay archivos aún cargados</span>
<?php } ?>
<script>
    $(function() {
        $("#archivosForm").on("submit",function(e){
            e.preventDefault();
            
            var form = document.getElementById("archivosForm");
            var btn = $(this).find("#btn-submitcargararchivos");
            // or with jQuery
            //var form = $("#myform")[0];

            $.ajax({
                url:"<?php echo url_for("archivos/ProcessarchivosFormAjax") ?>",
                data: new FormData(form),// the formData function is available in almost all new browsers.
                type:"post",
                contentType:false,
                processData:false,
                cache:false,
                dataType:"json", // Change this according to your response from the server.
                beforeSend:function(){
                    btn.attr("disabled","disabled");
                },                
                error:function(err){
                    console.error(err);
                },
                success:function(data){
                    alertify.success("El archivo se a subido satisfactoriamente");
                    
                    if(typeof(CargarArchivos) == "function"){                        
                        CargarArchivos();                    
                    }    
                },
                complete:function(){
                    btn.removeAttr("disabled");
                    
                }
            });
        });
    });
</script>

