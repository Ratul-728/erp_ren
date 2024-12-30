<?php

        function buildGetMasterArray($table,$condition){
        
        $inputData = array(
        	'TableName' => $table.' q',
        	'FetchCols' =>  array(
                    			'q.id dataid',
                    			'q.socode productCode', 
                    			'q.orderstatus', 
                    			'q.project',
                    			'q.srctype saleType',
                    			'q.organization', 
                    			'q.customer',
                    			'DATE_FORMAT(q.orderdate,"%e/%c/%Y") orderdate',
                    			'q.deliveryamt deliveryAmount',
                    			'q.accmanager accountManager', 
                    			'q.vat', 
                    			'q.tax',
                    			'q.invoiceamount invoiceAmount', 
                    			'q.makeby createdBy', 
                    			'q.makedt createdDate',
                    			'q.status',
                    			'q.remarks',
                    			'q.poc pointOfContact',
                    			'q.note',
                    			'p.name projectName',
                    			'o.name organizationName',
                    			'q.adjustment',
                                ),
        	'Join'     => array(
        	                    'LEFT JOIN organization o   ON o.id = q.organization',
        	                    'LEFT JOIN project p        ON q.project = p.id',
        	                    ),
        	//'Where'    =>  array('socode' => 'QT-000001'),
        	'Where'    =>  $condition,
        );
        
        return $inputData;
        
    } 
    
    
    
        function buildGetProductArray($table,$condition){
        
        $inputData = array(
        	'TableName' => $table.' qd',
        	'FetchCols' =>  array(
                                'qd.id dataid', 
                                'qd.socode orderId', 
                                'qd.sosl listSL', 
                                'qd.productid productDataId', 
                                'qd.mu unit', 
                                'round(qd.qty,0) quantity',
                                'round(qd.otc,2) price', 
                                'qd.remarks', 
                                'qd.makeby', 
                                'qd.makedt',
                                'qd.currency',
                                'qd.vatrate vat',
                                'qd.aitrate ait', 
                                'qd.discountrate discountRate',
                                'qd.discounttot discountTotal',
                                'COALESCE(s.freeqty,0) freeqty',
                                'p.name productName',
                                'p.barcode',
                                ),
        	'Join'     => array(
        	                    'LEFT JOIN item p       ON qd.`productid` = p.id',
        	                    'LEFT JOIN stock s      ON qd.productid = s.product',
        	                    ),
        	'Where'    =>  $condition,
        );
        
        return $inputData;
        
    }     
    

    

    $cols['quotation'] = array(
        'tableid'       => 'id',
        'socode'        => 'socode',
        'customerType'  => 'customertp',
        'organization'  => 'organization',
        'saleType'      => 'srctype',
        'projectName'   => 'project',
        'customer'      => 'customer',
        'orderDate'     => 'orderdate',
        'deliveryDate'  => 'deliverydt',
        'deliveryBy'    => 'deliveryby',
        'deliveryAmount' => 'deliveryamt',
        'accountManager' => 'accmanager',  //current loggedin user;
        'vat'           => 'vat',
        'tax'           => 'tax',
        'invoiceAmount' => 'invoiceamount',
        'createdBy'     => 'makeby',
        'createdDate'   => 'makedt',
        'status'        => 'status',
        'remarks'       => 'remarks',
        'note'          => 'note',
        'pointOfContact' => 'poc',
        'orderstatus'   => 'orderstatus',
        'adjustment'    => 'adjustment',
    );
    
    $cols['quotation_revisions'] = array(
        'tableid'       => 'id',
        'socode'        => 'socode',
        'customerType'  => 'customertp',
        'organization'  => 'organization',
        'saleType'      => 'srctype',
        'projectName'   => 'project',
        'customer'      => 'customer',
        'orderDate'     => 'orderdate',
        'deliveryDate'  => 'deliverydt',
        'deliveryBy'    => 'deliveryby',
        'deliveryAmount' => 'invoiceamount',
        'accountManager' => 'accmanager',  //current loggedin user;
        'vat'           => 'vat',
        'tax'           => 'tax',
        'invoiceAmount' => 'invoiceamount',
        'createdBy'     => 'makeby',
        'createdDate'   => 'makedt',
        'status'        => 'status',
        'remarks'       => 'remarks',
        'note'          => 'note',
        'pointOfContact' => 'poc',
        'orderstatus'   => 'orderstatus',
        'adjustment'    => 'adjustment',
    );   

?>