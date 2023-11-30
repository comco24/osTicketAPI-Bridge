<?php

function faqs_create($params = array()) {
	require_once(DOCROOT. "tikety/app/private/api-conf.php");
	require_once(DOCROOT. "tikety/app/class/x_api.php");

	$group_id = 0;
	$breadcrumb_replace = ''; $breadcrumb_replace_data = array();

	$request_uri_parts = explode('/', $_SERVER['REQUEST_URI']);
	$base_url = '/' . $request_uri_parts[1] .'/';
	$loop = 0;
	foreach ($request_uri_parts as $uri_part) {
		if (preg_match("/^fg(\d{1,})$/si", $uri_part, $matches)) {
			$group_id = $matches[1];
			$breadcrumb_replace_data[] = $group_id;
		}#if
	}#foreach

	$api = new x_api(X_API_S_KEY, X_API_APP_ID, X_API_URL);
	if ($group_id == 0) $group_id = $params['group']; else {
		$breadcrumb_replace = $base_url;
		$faqs_data_categiories = $api->getOSTFaqsDataCategories();
		$faqs_data_categiories = json_decode($faqs_data_categiories);
	}

	$faqs_data = $api->getOSTFaqsData($group_id);
	$faqs_data = json_decode($faqs_data);

	if (!is_array($faqs_data->category)) return '';
	if (count($faqs_data->category) == 0) return '';

	if (count($faqs_data->category) == 1) {
		foreach ($faqs_data->category as $category) {
			$temp .= "<h2 class='faqs_main_header'>$category->category_name</h2>";
			if ($category->category_description != '') $temp .= "<p>$category->category_description</p>";
			$temp .= "<br>";

			$sub = '';
			if (count($category->sub_categories) > 0) {
			foreach ($category->sub_categories as $sub_category) {
				$url = $base_url . 'fg'. $sub_category->category_id;
				$url = $_SERVER['REQUEST_URI'] . '/fg'. $sub_category->category_id;
				$sub .= "<div class='col-xs-12 col-md-6 outer_frame'>
							<div class='row frame'>
								<div class='col-xs-2 col-sm-2 align-middle picture_col'></div>
								<div class='col-xs-10 col-sm-10 text_col'><a href='$url'>$sub_category->category_name</a></div>
							</div>
						</div>";
			}#foreach

			if ($sub != '') $sub = "
			<div class='row pagegallery'>
				$sub
			</div>";
			}#if subcategories

			$temp .= $sub;
			if ((is_array($category->faqs) and (count($category->faqs) > 0))) {
				$loop = 0;
				foreach ($category->faqs as $faq) {
					$loop++;
					if (($loop == 1) and (count($category->faqs) > 0)) $add_class = 'first'; else $add_class = '';
					$temp .= "<div class='faq_question $add_class' id='faq_question_$faq->id' data-id='$faq->id'>
								<div class='question'>$faq->question <span class='changer'><i class='fa fa-2x fa-angle-down'></i></span></div>
								<div class='answer'>$faq->answer</div>
							</div>";
				}#foreach
			}#if

		}#foreach
	} else {
		foreach ($faqs_data->category as $category) {
			$url = $base_url . 'fg'. $category->id;
			$temp .= "<div class='col-xs-12 col-md-6 outer_frame'>
						<div class='row frame'>
							<div class='col-xs-2 col-sm-2 align-middle picture_col'></div>
							<div class='col-xs-10 col-sm-10 text_col'><a href='$url'>$category->category_name</a></div>
						</div>
					</div>";
		}#foreach

		if ($temp != '') $temp = "
		<div class='row pagegallery'>
			$temp
		</div>";
	}
#	print_r($faqs_data);
#	print_r($params);exit;

	$temp .= _get_js_faqs();

	if ($breadcrumb_replace != '') $temp .= _get_js_breadcrumb($breadcrumb_replace, $breadcrumb_replace_data, $faqs_data_categiories);
	return $temp;
}#faqs_create


function _get_js_faqs() {
	$temp = "
		<script>
		var basic_url = location.href;
		$('.faq_question .question').on('click', function() {
			var pointer = $(this).find('i');
			var opened = false;

			if (pointer.hasClass('fa-angle-down')) {
				$(document).find('i').each(function() {
					if ($(this).hasClass('fa-angle-up')) {
						$(this).addClass('fa-angle-down');
						$(this).removeClass('fa-angle-up');
						$(this).parent().parent().parent().find('.answer').hide(200);
						$(this).parent().parent().parent().find('.question').removeClass('selected');
					}
				});
				pointer.removeClass('fa-angle-down');
				pointer.parent().parent().removeClass('gray');
				pointer.addClass('fa-angle-up');
				$(this).parent().find('.answer').show(200);
				$(this).parent().find('.question').addClass('selected');
				opened = true;
			} else {
				pointer.removeClass('fa-angle-up');
				pointer.addClass('fa-angle-down');
				$(this).parent().find('.answer').hide(200);
				opened = false;
			}

			if (opened == true) {
				$(document).find('i').each(function() {
					if ($(this).hasClass('fa-angle-down')) {
						$(this).parent().parent().addClass('gray');
					}
				});
			} else {
				$(document).find('i').each(function() {
					if ($(this).hasClass('fa-angle-down')) {
						$(this).parent().parent().removeClass('gray');
						$(this).parent().parent().removeClass('selected');
					}
				});
			}

		});

		</script>
	";


	return $temp;
}#_get_js_faqs


function  _get_js_breadcrumb($base_url = '', $breadcrumb_replace_data = array(), $faqs_data_categiories = array()) {
	$url = $base_url;
	$loop == 0;
	foreach ($breadcrumb_replace_data as $group_id) {
		$url .= "/fg$group_id";
		$url = preg_replace("~//~", "/", $url);
		$loop++;
		foreach ($faqs_data_categiories->category as $category) {
			if ($category->id == $group_id) {
				$name = $category->category_name;
				break;
			}#if
		}#foreach
		if ($loop == count($breadcrumb_replace_data)) $replace .= "$name"; else $replace .= "<a href='$url'>$name</a> <span class='bullet'>›</span> ";

	}#foreach

	$temp = "
		<script>
			var pointer = $('.breadcrumb.product');
			var text = pointer.find('.current').html();
			var current = $('.faqs_main_header').html();
			pointer.find('.current').replaceWith(\"<a href='$base_url'>\" + text + \"</a> <span class='bullet'>›</span> $replace\");
		</script>
	";
	return $temp;
}# _get_js_breadcrumb


function _get_group_data($group_id) {
	$api = new x_api(X_API_S_KEY, X_API_APP_ID, X_API_URL);

	// getOSTFaqsData() vracia jSON objekt so vsetkymi aktivnym FAQ polozkami vratane kategorii
	$faqs_data = $api->getOSTFaqsData($group_id);
	$faqs_data = json_decode($faqs_data);
	if (is_array($faqs_data->category)) {
		foreach ($faqs_data->category as $category) {
			if ($category->id == $group_id) {
				return array('category_name' => $category->category_name, 'category_description' => $category->category_description);
			}#if
		}#foreach
	}#if

	return array();
}
