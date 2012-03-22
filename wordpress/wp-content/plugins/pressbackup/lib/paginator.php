<?
	Class PressbackPaginator {

		function get_html ($pagination = array()) {

			global $pressbackup;
			$output = null;

			if (($pagination['page'] < $pagination['pages']) &&($pagination['page']==1))
			{
				$pageNext=$pagination['page'] + 1;
				$next = $pagination['func_path']; $next[] .= $pageNext;
				$last = $pagination['func_path']; $last[] .= $pagination['pages'];

				$output=	"<span class=\"displaying-num\">Displaying 1 - ".$pagination['pagination']." of ".$pagination['total']."</span> ".
							$pressbackup->Html->link('Next', $next, array('class'=>'button abpdding') )." ".
							$pressbackup->Html->link('Last', $last, array('class'=>'button abpdding') );
			}
			elseif (($pagination['page'] < $pagination['pages']) &&($pagination['page'] >1))
			{
				$pageNext=$pagination['page'] + 1;
				$pagePrev=$pagination['page'] - 1;
				$first = $pagination['func_path']; $first[] .= '1';
				$next = $pagination['func_path']; $next[] .= $pageNext;
				$prev = $pagination['func_path']; $prev[] .= $pagePrev;
				$last = $pagination['func_path']; $last[] .= $pagination['pages'];

				$output =	"<span class=\"displaying-num\">Displaying ".$pagination['ini']." - ".$pagination['fin']." of ".$pagination['total']."</span> ".
								$pressbackup->Html->link('First', $first, array('class'=>'button abpdding') )." ".
								$pressbackup->Html->link('Prev', $prev, array('class'=>'button abpdding') )." ".
								$pressbackup->Html->link('Next', $next, array('class'=>'button abpdding') )." ".
								$pressbackup->Html->link('Last', $last, array('class'=>'button abpdding') );
			}
			elseif (($pagination['page'] == $pagination['pages']) &&($pagination['pages'] !=1))
			{
				$pagePrev=$pagination['page'] - 1;
				$first = $pagination['func_path']; $first[] .= '1';
				$prev = $pagination['func_path']; $prev[] .= $pagePrev;

				if ($pagination['fin'] > $pagination['total']) {$pagination['fin'] = $pagination['total'];}
				$output =	"<span class=\"displaying-num\">Displaying ".$pagination['ini']." - ".$pagination['fin']." of ".$pagination['total']."</span> ".
								$pressbackup->Html->link('First', $first, array('class'=>'button abpdding') )." ".
								$pressbackup->Html->link('Prev', $prev, array('class'=>'button abpdding') );
			}
			elseif(($pagination['pages'] == 1)&&($pagination['total']!=0))
			{
				$output = "<span class=\"displaying-num\">Displaying 1 - ".$pagination['total']." of ".$pagination['total']."</span>";
			}

			return $output;
		}

	}

?>