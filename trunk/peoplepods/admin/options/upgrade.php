<?

	include_once("../../PeoplePods.php");	
	$POD = new PeoplePod(array('lockdown'=>'adminUser','authSecret'=>$_COOKIE['pp_auth']));

	$this_database_update = 0.71;

	$last_version = $POD->libOptions('last_database_update');
	if (!$last_version) { $last_version = 0; }


	if (isset($_GET['confirm'])) {
	
		echo "<ul>";
		
		
		if ($last_version < 0.7) { 
			echo "<li>Checking permissions...</li>";
			$POD->saveLibOptions();
			if (!$POD->success()) { 
			echo "<li><strong>" . $POD->error() . "</strong></li>";
			} else {
	
				echo "<li>Creating table meta_tmp...</li>";	
				$sql = "CREATE TABLE meta_tmp(type enum('group','content','user'),itemId bigint(12), name varchar(100),value text,id bigint(12) NOT NULL UNIQUE auto_increment, unique index u (type,itemId,name));";
				$res = mysql_query($sql,$POD->DATABASE);
				if (!$res) { 
					echo "<li><strong>SQL Error: " . mysql_error() . "</strong></li>";
				} else { 	
					echo "<li>Copying meta values into meta_tmp...</li>";
					$sql = "REPLACE INTO meta_tmp (type,itemId,name,value) SELECT type,itemId,name,value FROM meta;";
					$res = mysql_query($sql,$POD->DATABASE);
					if (!$res) { 
						echo "<li><strong>SQL Error: " . mysql_error() . "</strong></li>";
					} else { 	
						echo "<li>Deleting values from meta... (If this errors, you may have to restore your db from backup)</li>";
						$sql = "DELETE FROM meta";
						$res = mysql_query($sql,$POD->DATABASE);
						if (!$res) { 
							echo "<li><strong>SQL Error: " . mysql_error() . "</strong></li>";
						} else { 	
							echo "<li>Adding unique index to the meta table...</li>";
							$sql = "ALTER TABLE meta ADD UNIQUE INDEX u (type,itemId,name);";
							$res = mysql_query($sql,$POD->DATABASE);
							if (!$res) { 
								echo "<li><strong>SQL Error: " . mysql_error() . "</strong></li>";
							} else {
								echo "<li>Copying values from meta_tmp back into meta... (If this errors, you may have to restore your db from backup)</li>";
								$sql = "INSERT INTO meta (type,itemId,name,value) SELECT type,itemId,name,value FROM meta_tmp;";
								$res = mysql_query($sql,$POD->DATABASE);
	
								if (!$res) { 
									echo "<li><strong>SQL Error: " . mysql_error() . "</strong></li>";
								} else {
									echo "<li>Cleaning up by removing meta_tmp table...</li>";		
									$sql = "DROP TABLE meta_tmp;";
									$res = mysql_query($sql,$POD->DATABASE);
									if (!$res) { 
										echo "<li><strong>SQL Error: " . mysql_error() . "</strong></li>";
									} else {
										$POD->setLibOptions('last_database_update','0.7');
										$POD->saveLibOptions();
										if (!$POD->success()) { 
											echo "<li><strong>" . $POD->error() . "</strong></li>";
										} else {
											echo "<li>Upgrade to 0.7 complete.</li>";
										}
									}
								}
							}
						}
					}
				}
			}
		}	
		if ($last_version < 0.71) {
		
			echo "<li>Checking permissions...</li>";
			$POD->saveLibOptions();
			if (!$POD->success()) { 
				echo "<li><strong>" . $POD->error() . "</strong></li>";
			} else {
				echo "<li>Altering Meta Table..</li>";	
				$sql = "ALTER TABLE meta CHANGE type type enum('content','user','group','comment','file');";
				$res = mysql_query($sql,$POD->DATABASE);
				if (!$res) { 
						echo "<li><strong>SQL Error: " . mysql_error() . "</strong></li>";
				} else {
					echo "<li>Altering Meta Table..</li>";	
					$sql = "ALTER TABLE flags CHANGE type type enum('content','user','group','comment','file');";
					$res = mysql_query($sql,$POD->DATABASE);
					if (!$res) { 
							echo "<li><strong>SQL Error: " . mysql_error() . "</strong></li>";
					} else {

						echo "<li>Creating table activity...</li>";	
						$sql = "CREATE TABLE activity(userId bigint(12),target varchar(100),targetType varchar(10),count int default 0,message varchar(255),gid varchar(25),date datetime,id bigint(12) NOT NULL UNIQUE auto_increment, index uid (userId),index tid (target,targetType),unique index gidx (gid));";
						$res = mysql_query($sql,$POD->DATABASE);
						if (!$res) { 
							echo "<li><strong>SQL Error: " . mysql_error() . "</strong></li>";
						} else {
							$POD->setLibOptions('last_database_update','0.71');
							$POD->saveLibOptions();
							if (!$POD->success()) { 
								echo "<li><strong>" . $POD->error() . "</strong></li>";
							} else {
								echo "<li>Upgrade to 0.71 complete.</li>";
							}
						}
					}
				}
			}
		}
		echo "</ul>";
	
	} else { 
		
		
	$POD->changeTheme('admin');
	$POD->header(); 
	
	?>
	<div class="column_padding">
		<h1>Upgrade</h1>
	<?
		if ($last_version < $this_database_update) { ?>
		
			<p>PeoplePods needs to make updates to your database.  We suggest you make a backup of your database first!</p>
			<p><a href="upgrade.php?confirm=destruct+alpha+alpha+destruct">UPGRADE</a></p>
		
		<? } else { ?>
		
			<p>Your PeoplePods schema is up to date.</p>
			
		<? } 	
	
	$POD->footer(); 
	
	}


?>