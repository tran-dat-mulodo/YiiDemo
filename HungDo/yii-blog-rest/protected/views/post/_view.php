<div class="post">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
	</div>
	<div class="author">
		posted by <?php echo $data->author->username . ' on ' . date('F j, Y',$data->create_time); ?>
	</div>
	<div class="content">
		
		<?php
			$this->beginWidget('CMarkdown', array('purifyOutput'=>true));
			if(isset($_GET['id'])) {
				
				//test fragment cache
				if ($this -> beginCache('test_fragment_cache', array(//begin
					'duration' => 30, 
					'varyByParam' => array('id'), //cache by $_GET['id']
				))) {
					//Test Dinamic Content
					
					echo $data->content;
					$this->renderDynamic('testDinamicContent');
					echo 'Test Fragment cache:' .date("d-m-Y H:i:s", time()). ' - id = '.$_GET['id'];
					$this -> endCache();//end
				}
				
				//echo $data->content;
			}
				
			else
				echo substr(strip_tags($data->content), 0, 200).'...';
			$this->endWidget();
		?>
	</div>
	<div class="nav">
		<b>Tags:</b>
		<?php echo implode(', ', $data->tagLinks); ?>
		<br/>
		<?php echo CHtml::link('Permalink', $data->url); ?> |
		<?php echo CHtml::link("Comments ({$data->commentCount})",$data->url.'#comments'); ?> |
		Last updated on <?php echo date('F j, Y',$data->update_time); ?>
	</div>
</div>
