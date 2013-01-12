class At extends CComponent
{

  /**
	 * 分解留言内容并生成数组
	 * 
	 * @param string $content
	 * @return array
	 */
	public function process($content)
	{
		$return = array();
		$content = CHtml::encode($content.' ');
		$cont_sch = $cont_rpl = $at_uids = $tags = array();
		# @user
		if (false !== strpos($content, '@')) 
		{
			die($content);
			if (preg_match_all('~\@([\w\d\_\-\x7f-\xff]+)(?:[\r\n\t\s ]+|[\xa1\xa1]+|[\xa3\xac]|[\xef\xbc\x8c]|[\,\.\;\[\#])~', $content, $match)) 
			{
				if (is_array($match[1]) && count($match[1])) 
				{
					foreach ($match[1] as $k => $v) 
					{
						$v = trim($v);
						if ('　' == substr($v, -2)) {
							$v = substr($v, 0, -2);
						}
		
						if ($v && strlen($v) < 16) {
							$match[1][$k] = $v;
						}
					}
		
					$cr = new CDbCriteria();
					$cr->addInCondition('username', $match[1]);
					$members = User::model()->findAll($cr);
					if($members)
					{
						foreach($members as $row)
						{
							$_at = "@".$row->username;
							$cont_sch[$_at] = $_at;
							$cont_rpl[$_at] = "<M ".$row->id.">@".$row->username."</M> ";
							$at_uids[$row->id] = $row->id;
						}
					}
				}
			}
		}		
		
		// #主题#
		if (false !== strpos($content, '#'))
		{
			if (preg_match_all('~\#([^\/\-\@\#\[\$\{\}\(\)\;\<\>\\\\]+?)\#~', $content, $match))
			{
				$i = 0;
				foreach ($match[1] as $v)
				{
					$v = trim($v);
					if (($vl = strlen($v)) < 2 || $vl > 50)
					{
						continue;
					}
		
					$tags[$v] = $v;
					$_tag = "#{$v}#";
					$cont_sch[$_tag] = $_tag;
					$cont_rpl[$_tag] = "<T>#{$v}#</T>";
		
					// TODO 这里加检测标签是否存在，不存在则添加，存在则修改统计次数
				}
			}
		}
		
		if($cont_sch && $cont_rpl) 
		{
			foreach($cont_sch as $k=>$v) 
			{
				if($v && isset($cont_rpl[$k])) 
				{
					$content = str_replace($v, $cont_rpl[$k], $content);
				}
			}
		}
		
		$content = trim($content);
		
		$return['content'] = $content;
		$return['at_uids'] = $at_uids;
		$return['tags'] = $tags;
		return $return;
	}
	
	
}
