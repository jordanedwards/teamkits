<table class="admin_table sort_table" width="100%" cellpadding="0" cellspacing="0"><tr><?php
foreach($this->field_names as $key => $val){
	if ($key == $this->index_name){
		echo '<th class="nosort"></th>';
	} else {
		echo '<th class="sort_column" id="' . $key  . '" nowrap="nowrap">' . str_replace("_"," ",ucfirst($val)) . '</th>';
	}
}	
?><th class="nosort"></th></tr>
<tr><?php
foreach($this->field_names as $key => $val){
	if ($key == $this->index_name){
		echo '<td align="center" style="width:50px"><a href="' . $this->selected_table . '_edit.php?id={' . $this->selected_table . '.id}"><i class="fa fa-edit fa-lg"></i></a></td>';
	} else {
		echo '<td>{' . $this->selected_table . '.' . $key . '}</td>';
	}
}
echo '<td align="center" style="width:50px"><a href="actions/action_' . $this->selected_table . '_edit.php?action=delete&page_id=' . $this->selected_table . '_edit.php&id={' . $this->selected_table . '.id}" onclick="return confirm(\'You are about to delete a ' . $this->selected_table . ' from the system. Do you want to continue?\');"><i class="fa fa-times-circle fa-lg"></i></a></td>';
?></tr>
</table><br />