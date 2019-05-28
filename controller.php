<?php
	// Controller
	public function fetchProvinces()
    {
    	$start      = Input::get('iDisplayStart');      // Offset
    	$length     = Input::get('iDisplayLength');     // Limit
    	$sSearch    = Input::get('sSearch');            // Search string
    	$col        = Input::get('iSortCol_0');         // Column number for sorting
    	$sortType   = Input::get('sSortDir_0');         // Sort type

    	// Datatable column number to table column name mapping
        $arr = array(
            0 => 'id',
            1 => 'name',
			2 => 'pst',
			3 => 'gst',
			4 => 'hst',
			5 => 'service_charge',
            6 => 'status'
        );

        // Map the sorting column index to the column name
        $sortBy = $arr[$col];

        // Get the records after applying the datatable filters
        $provinces = Province::where('name','like', '%'.$sSearch.'%')
                    ->orderBy($sortBy, $sortType)
                    ->limit($length)
                    ->offset($start)
                    ->select('id', 'name', 'pst', 'gst', 'hst', 'service_charge', 'status')
                    ->get();

        $iTotal = Province::where('name','like', '%'.$sSearch.'%')->count();

        // Create the datatable response array
        $response = array(
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iTotal,
            'aaData' => array()
        );

        $k=0;
        if ( count( $provinces ) > 0 )
        {
            foreach ($provinces as $province)
            {
            	$response['aaData'][$k] = array(
                    0 => $province->id,
                    1 => ucfirst( strtolower( $province->name ) ),
					2 => number_format($province->pst, 2, '.', ''),
					3 => number_format($province->gst, 2, '.', ''),
					4 => number_format($province->hst, 2, '.', ''),
					5 => number_format($province->service_charge, 2, '.', ''),
                    6 => Helper::getStatusText($province->status),
                    7 => '<a href="javascript:void(0);" id="'. $province->id .'" class="edit_province"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>'
                );
                $k++;
            }
        }

    	return response()->json($response);
    }
?>