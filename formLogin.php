<?php include 'header.php'; ?>
		<h2 class="red center rounded">You are NOT logged in to <?php echo $_Label;?></h2>
		<p><br></p>
		<form action="<?php echo $_SERVER['SCRIPT_NAME'];?>?login=post" method="post" class="login">
			<p><?php echo $_Label;?> Login</p>
			<table>
				<tr>
					<th>Username</th>
				</tr>
				<tr>
					<td><input type="text" class="text" name="username"></td>
				</tr>
				<tr>
					<th>Password</th>
				</tr>
				<tr>
					<td><input type="password"  class="text"name="password"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" class="submit" value="Login"></td>
				</tr>
			</table>
		</form>
		<p><br></p>
<?php include 'footer.php'; ?>
