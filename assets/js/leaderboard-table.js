jQuery(document).ready(function(){
	jQuery("#wpa_leaderboard_sortable").dataTable({
		"fnDrawCallback": function(oSettings){
			/* Need to redo the counters if filtered or sorted */
			if(oSettings.bSorted || oSettings.bFiltered){
				for(var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++){
					jQuery("td:eq(0)", oSettings.aoData[ oSettings.aiDisplay[i] ].nTr).html(i+1);
				}
			}
		},
		"aoColumnDefs": [
			{ "bSortable": false, "aTargets": [ 0 ] },
		],
		"aaSorting": [[ 1, "asc" ]]
	} );
});