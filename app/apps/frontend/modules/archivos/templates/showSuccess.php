<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $archivos->getId() ?></td>
    </tr>
    <tr>
      <th>Fecha:</th>
      <td><?php echo $archivos->getFecha() ?></td>
    </tr>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $archivos->getNombre() ?></td>
    </tr>
    <tr>
      <th>Archivo:</th>
      <td><?php echo $archivos->getArchivo() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('archivos/edit?id='.$archivos->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('archivos/index') ?>">List</a>
