<style type="text/css">
.grace-trace{width:100%; position:fixed; z-index:999; left:0; bottom:0; background-color:#FFFFFF; border-top:1px solid #999999; display:none;}
.grace-trace-item{width:31%; float:left; overflow:hidden; padding:10px 1%;}
.grace-trace-item > .title{line-height:50px; font-size:15px; font-weight:bold; color:#666666; border-bottom:1px solid #E9E9E9;}
.grace-trace-item > .text{line-height:2.2em; font-size:13px; padding:20px 0; height:158px; overflow-y:auto; margin:8px 0;}
.grace-trace-item > .text .sql{border-bottom:1px dashed #E9E9E9; padding-bottom:10px; margin-bottom:10px;}
.grace-trace-item > .text .sql span{color:#888888; font-size:16px;}
.grace-trace-item > .text .sql i{color:#FF0036;}
.grace-trace-item > .text .sql a{font-size:13px; color:#3688FF;}
.grace-trace-item > .text .sql b{font-weight:400; font-size:13px;}
.green{color:green;}
.red{color:red;}
.grace-trace-small{width:212px; padding:8px 5px; background-color:#F8F8F8; position:fixed; right:0; bottom:300px; border-bottom-left-radius:30px; border-top-left-radius:30px; box-shadow:1px 1px 18px #999999; cursor:pointer; font-size:0; line-height:0;}
.grace-trace-small img{float:left; width:38px; margin-left:5px; border-radius:38px;}
.grace-trace-small-msg{width:150px; margin-left:15px; float:left; overflow:hidden; font-size:13px; line-height:20px; color:#333333;}
</style>
<?php $cost = pgCost();?>
<div class="grace-trace" id="grace-trace">
	<div class="grace-trace-item">
		<div class="title">运行信息</div>
		<div class="text">
			远程时间 : <?php echo date('Y-m-d H:i:s');?><br />
			运行耗时 : <?php echo $cost[0];?> 毫秒<br />
			内存消耗 : <?php echo $cost[1];?> k<br />
		</div>
	</div>
	<?php $includedFiles = get_included_files();?>
	<div class="grace-trace-item">
		<div class="title">引入文件 [ <?php echo count($includedFiles);?> ]</div>
		<div class="text">
			<?php 
			foreach($includedFiles as $k => $file){ 
				echo ($k+1).'. '.$file.'<br />';
			}?>
		</div>
	</div>
	<div class="grace-trace-item">
		<div class="title">sql 运行日志</div>
		<div class="text">
			<?php foreach($GLOBALS['graceSql'] as $k => $sql){?>
				<div class="sql">
					<span>记录 <?php echo $k + 1;?></span><br />
					结果 : <?php if($sql[0] == '执行成功'){echo '<b class="green">'.$sql[0].'</b>';}else{echo '<b class="red">'.$sql[0].'</b>';}?> 耗时 : <?php echo $sql[2];?> 毫秒 <br />
					语句 : <?php echo $sql[1];?><br  />
					<?php if(!empty($sql[3])){echo '错误 : <i>'.$sql[3].'</i>&nbsp;&nbsp;&nbsp;[ <a href="https://fanyi.baidu.com/#en/zh/'.urlencode($sql[3]).'" target="_blank">翻译一下</a> ]';}?>
				</div>
			<?php }?>
		</div>
	</div>
</div>
<div class="grace-trace-small" onclick="showTrace()">
	<img src="https://cdn.jsdelivr.net/gh/yaoGrace/CdnStatics/cdnImgs/phpgrace/trace.png"/>
	<div class="grace-trace-small-msg">
		运行耗时 : <?php echo $cost[0];?> 毫秒<br />
		内存消耗 : <?php echo $cost[1];?> k
	</div>
</div>
<script type="text/javascript">
var graceTraceStatus = false;
function showTrace(){
	var graceTrace = document.getElementById('grace-trace');
	graceTraceStatus = !graceTraceStatus;
	if(graceTraceStatus){
		graceTrace.style.display = 'block';
	}else{
		graceTrace.style.display = 'none';
	}
}
</script>