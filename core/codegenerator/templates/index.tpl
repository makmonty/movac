<div class='data-index'>
	<div class='new'>
		<a href='{ldelim}$root{rdelim}{$objectname}/edit'>{ldelim}t{rdelim}Nuevo {$objectname}{ldelim}/t{rdelim}</a>
	</div>
	
	<div class='data-list'>
		<table class='results'>
			<thead>
			{foreach $columns as $field => $def}
			
				<th>{ldelim}t{rdelim}label_{$field}{ldelim}/t{rdelim}</th>
			{/foreach}
			
				<th>{ldelim}t{rdelim}Acciones{ldelim}/t{rdelim}</th>
			</thead>
			
			<tbody></tbody>
		</table>
	</div>
</div>