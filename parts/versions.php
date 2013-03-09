 <? 
 /*******************************************************************************
 * Copyright (c) 2007 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Wayne Beaton (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/
 
 if ($info != null) { ?>
 
 <style>
 	.info-box {width:600px;display:block;margin-left:auto;margin-right:auto;margin-top:10px;margin-bottom:10px;border-style:solid;border-width:1px;padding:5px}
 	.info-bullet {margin-left:50px}
 </style>
 
 <div class="info-box">
 
 <?
 	if (isset($info->outdated)) $App->setOutDated();
 	if ($info->project) {
		require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/projects/projectInfoData.class.php");
 		echo "<p>This article is known to apply to the following Eclipse project";
 		if (count($info->project) > 1) echo "s";
 		echo ":</p>";
 		echo "<ul>";
 		$separator = "";
 		foreach($info->project as $project) {
 			echo "<li class=\"info-bullet\">";
 			$id = $project['id'];
			$project_info = @new ProjectInfoData($id);
			$name = $project_info->projectname;
			if ($name == null) $name = $id;
 			echo "<a href=\"http://www.eclipse.org/projects/project_summary.php?projectid=$id\">$name</a>";
 			
 			if ($project->release) {
 				echo ", release";
 				if (count ($project->release) > 1) echo "s";
 				$separator = " ";
 				foreach ($project->release as $release) {
 					echo $separator . $release;
 					$separator = ", ";
 				}
 			}
 			
 			echo "</li>";
 		}
 		echo "</ul>";
 		
 		echo "<p>Help us keep this information up-to-date: let us know if this information applies to other projects or releases.";
 	}
 ?>
 
 <?
 	if ($info->bug) {
 		echo "<p>To comment on this article, ask questions, or propose corrections, please see ";
 		$separator = "";
 		foreach($info->bug as $bug) {
 			$id = $bug['id'];
 			echo $separator . "<a href=\"https://bugs.eclipse.org/bugs/show_bug.cgi?id=$id\">bug $id</a>";
 			$separator = ", ";
 		}
 		echo ".<p>";
 	} else {
 		echo "<p>To comment on this article, ask questions, or propose corrections, please <a href\"https://bugs.eclipse.org/bugs/enter_bug.cgi?product=Community&component=Articles\">open a bug</a>.</p>";
 	}
 	
 ?>

</div>
<? } ?>	
