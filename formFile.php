<?php include 'header.php'; ?>
		<form action="<?php echo $_SERVER['SCRIPT_NAME'].'?file='.$_GET['file']; ?>" method="post" id="formFile">
			<p>
				<input class="submit" type="submit" value="Finish">
				<input class="submit" type="submit" value="Apply" onclick="this.form.action+='&path=<?php echo $_GET['path'];?>';">
				<input class="submit" type="reset" value="Reset">
				<input class="submit" type="button" value="Cancel" onclick="location='<?php echo $_SERVER['SCRIPT_NAME'];?>';">
				<input type="hidden" name="filePath" value="<?php echo $_GET['path'];?>"><input type="hidden" name="filePath" value="<?php echo $_GET['path'];?>">
			</p>
			<h3 title="<?php if('-1'!=$_GET['file'])echo 'Modified: '.date ("F d Y H:i:s", filemtime($_Paths[$_GET['path']].'/'.$_GET['file']));?>"><?php 
				echo $_Paths[$_GET['path']].'/';
				if('-1'!=$_GET['file']){
					?><span><?php echo $_GET['file'];?></span>
					<input type="hidden" name="fileName" value="<?php echo $_GET['file'];?>"><?php
				}else{
					?><input type="text" name="fileName" value="<?php echo date("YmdHis");?>.txt"><?php
				}
			?></h3>
			<div>
				<textarea rows="20" cols="120" id="fileContents" name="fileContents" class="clear"><?php
					echo htmlspecialchars(isset($_POST['fileContents'])?$_POST['fileContents']:$fileContents)
				?></textarea>
			</div>
		</form>
<?php include 'footer.php'; ?>
