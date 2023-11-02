<?php
/**
 * The view for the map row used in the loop
 */
$columns = getCustomFields($fields = array());
$colCount = count($columns);
// Check for Children
$hasChildren = false;
$childCount = 0;
$thClass = 'single';
$args = array(
	'post_parent' => $item->ID,
	'post_type' => 'map'
);
$children = get_children($args);
if (get_children($args)) {
	$hasChildren = true;
	$childCount = count($children);
	$thClass = 'multi';
}
// Check for Parents
$isChild = false;
if (wp_get_post_parent_id($item->ID)) {
    $isChild = true;
}
$progCols = $GLOBALS['prog'];
for ($c=0;$c<$colCount;$c++) {
	if ($columns[$c][3] == 'title-column') {
		
		$linkStart = '';
		$linkEnd = '';
		if (isset($meta['map-link'][0])) {
			$linkStart = '<a href="'. $meta['map-link'][0] .'">';
			$linkEnd = '</a>'; 
		}
		
		if ($isChild) {
			// Don't add header
		} else {
			?><th class="<?=$columns[$c][3]?> <?=$columns[$c][0]?> <?=$thClass?>" itemprop="name" scope="row" rowspan="<?=$childCount+1?>"><div class="wrap"><?=$linkStart?><?=$item->post_title?><?=$linkEnd?></div></th><?php
		}
	} elseif ($columns[$c][3] == 'progress-column') {
		
		if ($progCols == $GLOBALS['prog']) {
			?><td colspan="<?=$GLOBALS['prog']?>" class="exp-on-scroll <?=$columns[$c][3]?>"><div class="wrap">
				<div class="progress-contain"><div class="progress" style="width: <?=$meta['map-progress'][0]?>%;"><div class="progress-bar" role="progressbar" style="background-color: <?=$meta['map-color'][0]?>" ><?=((isset($meta['map-progress-text'][0])) ? $meta['map-progress-text'][0] : '')?></div></div></div>
			<?php
			
		} elseif ($progCols == 0) {
	?></div></td><?
		} 
		$progCols = $progCols-1;
		
		
	} elseif ($columns[$c][3] == 'image-column') {
		if (!empty($columns[$c][0])) {
			$imgID = $meta[$columns[$c][0]][0];
			$img = wp_get_attachment_image($imgID, 'map-logo-image', '', array('class'=>'img-responsive img-fluid'));
		} else {
			$img = 'IMG'; 
		}
		?><td class="<?=$columns[$c][3]?> <?=$columns[$c][0]?>"><div class="wrap"><?=$img?></div></td><?php
	} else {
		if (isset($meta[$columns[$c][0]][0])) {
			?><td class="<?=$columns[$c][3]?> <?=$columns[$c][0]?>"><div class="wrap"><?=$meta[$columns[$c][0]][0]?></div></td><?php
		} else {
			?><td></td><?php
		}
	}
}  
?>