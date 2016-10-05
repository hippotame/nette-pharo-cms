<?php
?>

<table id="cat_table"
	class="table table-striped table-bordered dt-responsive nowrap"
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
		{foreach $rows as $k => $row }
		<tr>
			{foreach $headers as $key => $head}
			<td>
				<?php
                    if (isset($row[$key])) {
                        echo $row[$key];
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
                            throw new DataTable\ExceptionDataTable("Metoda ".ucfirst($key)." neexistuje");
                        }
                    }
                 ?>
				</td>
		  {/foreach}
		</tr>
		{/foreach}
	</tbody>
</table>
{block admin_bottom_scripts}
<script>

</script>

{/block}
