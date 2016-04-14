<div class='data-view'>
	{foreach $columns as $field => $def}
	
		<div class='field-view'>
			<label>{ldelim}t{rdelim}label_{$field}{ldelim}/t{rdelim}</label>
			<span>{ldelim}${$objectname}.{$field}{rdelim}</span>
		</div>
	{/foreach}
</div>