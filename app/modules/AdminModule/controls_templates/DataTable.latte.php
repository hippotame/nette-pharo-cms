<?php ?>

<table id="{$dataTableID}"
       class="table table-striped table-bordered table-hover nowrap"
       cellspacing="0" width="100%">
    <thead>
        <tr>
            {foreach $headers as $key => $row}
            <th
            <?php
             if (isset($row['width'])) {
                 echo 'width="' . str_replace('(px|em|mm)', '', $row['width']) . '"';
             }
            ?>
                id="datatable_{$key}">{$row['name']}</th>
            {/foreach}
        </tr>
    </thead>
    <tbody>
        <?php
         foreach ($rows as $k => $row) {
             ?>
             <tr>
                 <?php
                 foreach ($headers as $key => $head) {
                     ?>
                     <td>
                         <?php
                         if (isset($row[$key])) {
                             
                             $row_val = $row[$key];
                             if (isset($head['transform'])) {
                                 if ($this->presenter->getReflection()->hasMethod($head['transform'])) {
                                     $fce = $head['transform'];
                                     $row_val = $this->presenter->$fce($row[$key]);
                                 }
                             }
                             echo $row_val;
                         } else {
                             if ($this->presenter->getReflection()->hasMethod('action' . ucfirst($key))) {
                                 $module = $this->presenter->makeLink($key);
                                 switch ($key) {
                                     case 'edit':
                                         $button = 'warning';
                                         break;
                                     default:
                                         $button = 'danger';
                                 }
                                 echo '<a href="' . $module . '?id=' . $row['id'] . '" class="btn btn-' . $button . '">' . ucfirst($key) . '</a>';
                             } else {
                                 throw new DataTable\ExceptionDataTable("Metoda " . ucfirst($key) . " neexistuje");
                             }
                         }
                         ?>
                     </td>
                 <?php } ?>
             </tr>
         <?php } ?>
    </tbody>
</table>
{block admin_bottom_scripts}
<script>
    $(function () {
        $('#<?php echo $dataTableID; ?>').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>

{/block}
