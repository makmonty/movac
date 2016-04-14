<div class='data-edit'>
	<div class='back'><a href='#' onclick='history.go(-1);return false;'>{ldelim}t{rdelim}Volver{ldelim}/t{rdelim}</a></div>
	<form method='post' enctype='multipart/form-data' id='{$objectname}_form' class='validated_form form-horizontal'>
		<input type='hidden' name='id' value='{ldelim}${$objectname}.id{rdelim}' />
{foreach $columns as $field => $def}
	{$required = (isset($def.notnull) && $def.notnull)? "required" : ""}
	{$email = (substr($field,0,5) == "email")? "email" : ""}
	{$image = (substr($field,0,5) == "image")? "image" : ""}
	{$file = (substr($field,0,4) == "file")? "file" : ""}
	{$relation = (substr($field,0,3) == "id_")? "relation" : ""}
	{$date = ($def.type == "date")? "date" : ""}
	{$datetime = ($def.type == "datetime" || $def.type == "timestamp")}
		
		<div class='field-edit control-group'>
		
			<label for='{$field}' class='control-label'>{ldelim}t{rdelim}label_{$field}{ldelim}/t{rdelim}</label>
			<div class='controls'>
	{if $def.type == "enum"}
		
				<select name='{$field}' class='{$def.type} {$required}' {$required}>
		{foreach $def.values as $v}
			
					<option value='{$v}'>{ldelim}t{rdelim}label_enum_{$v}{ldelim}/t{rdelim}</option>
		{/foreach}
				
				</select>
	{elseif $image}
		
			{ldelim}$img_url = (${$objectname}.{$field})? ${$objectname}.{$field} : "noimage.png"{rdelim}
				<div class='uploaded_image'><img src='{ldelim}$root{rdelim}images/{ldelim}$img_url{rdelim}' /></div>
				<input type='file' class='{$def.type} {$required} image' name='{$field}' {$required} />
	{elseif $file}
		
			{ldelim}if ${$objectname}.{$field}{rdelim}
					<div class='uploaded_file'><img src='{ldelim}$root{rdelim}files/{ldelim}${$objectname}.{$field}{rdelim}' /></div>
			{ldelim}/if{rdelim}
				<input type='file' class='{$def.type} {$required} file' name='{$field}' {$required} />
	{elseif $datetime}
		
			{ldelim}$d = explode(" ", ${$objectname}.{$field}){rdelim}
			{ldelim}$d0 = $d[0]{rdelim}
			{ldelim}$d1 = (isset($d[1]))? $d[1] : ""{rdelim}
				<input type='text' class='date date-picker input-small {$required}' name='{$field}_date' value='{ldelim}if ${$objectname}.{$field}{rdelim}{ldelim}format_date date=$d0 format='d/m/Y'{rdelim}{ldelim}/if{rdelim}' {$required} />
				<input type='text' class='time time-picker input-mini {$required}' name='{$field}_time' value='{ldelim}if ${$objectname}.{$field}{rdelim}{ldelim}$d1{rdelim}{ldelim}/if{rdelim}' {$required} />
	{elseif $relation}
		
			{ldelim}html_options name="{$field}" options=$selects.{$field} selected=${$objectname}.{$field}{rdelim}
	{elseif $field != "id"}
		
				<input type='text' class='{$def.type} {$required} {$email} {$date}' name='{$field}' value='{ldelim}${$objectname}.{$field}{rdelim}' {$required} />
	{/if}
		
			</div>
		</div>
{/foreach}

		<div class='buttons control-group'>
			<div class='controls'>
				<input type="submit" class="btn btn-primary" name="submit" value="{ldelim}t{rdelim}Guardar{ldelim}/t{rdelim}" />
			</div>
		</div>
	</form>
</div>