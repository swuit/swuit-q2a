<?php

//	Output this header as early as possible
	//header('Content-Type: text/plain; charset=utf-8');


//	Ensure no PHP errors are shown in the Ajax response
	@ini_set('display_errors', 0);


//	Load the Q2A base file which sets up a bunch of crucial functions
	require_once '../../qa-include/qa-base.php';
	qa_report_process_stage('init_ajax');		

//	Get general Ajax parameters from the POST payload, and clear $_GET
	qa_set_request(qa_post_text('qa_request'), qa_post_text('qa_root'));
	
	require_once QA_INCLUDE_DIR.'qa-db-selects.php';

	$query = $_POST['query'];
	//
	// 
	$categories = qa_db_read_all_assoc(qa_db_query_sub(
		"SELECT ^categories.categoryid,^categories.parentid,^categories.tags,^categories.title,^categories.qcount,^categories.position,^categories.backpath,^categories.title, ^categorymetas.content AS meta
		FROM ^categories
		LEFT JOIN ^categorymetas
		ON ^categories.categoryid=^categorymetas.categoryid AND ^categorymetas.title='et_category'
		WHERE ^categories.title like '%" . $query ."%'
		ORDER BY ^categories.qcount DESC
		LIMIT 10
		" ));
	//
	//	echo "<pre>";  var_dump( $categories ); echo "</pre>";
	// 

	foreach ($categories as $key =>  $category){
		$categories[$key]['id'] = $category['categoryid'];
		$categories[$key]['name'] = $category['title'];
		//$categories[$key]['meta'] = json_decode($categories[$key]['meta'],true);
	}

	echo json_encode($categories);
