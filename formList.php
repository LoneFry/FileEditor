<?php include 'header.php'; ?>
		<ul id="fileList">
<?php if(is_array($_Paths) && count($_Paths) > 0) {
		foreach($_Paths as $k => $path){ ?>
			<li><?php echo $path; ?>
				<ul>
					<li><a href="<?php echo $_SERVER['SCRIPT_NAME'].'?path='.$k;?>&file=-1">Create New File</a></li>
<?php $list=getFiles($path);
		if(is_array($list) && count($list) > 0)
			foreach($list as $file){ 
				if(testMasks($file)){ ?>
					<li><a href="<?php echo $_SERVER['SCRIPT_NAME'].'?path='.$k.'&file='.$file;?>"><?php echo $file;?></a></li>
<?php			}
			} ?>
				</ul>
			</li>
<?php	}
	} ?>
		</ul>
<?php include 'footer.php'; ?>
