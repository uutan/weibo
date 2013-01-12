class Html extends CHtml {
  
	
	/**
	 * 将规则转换为html代码
	 * @param unknown_type $content
	 */
	public static function showAt($content)
	{
		if (false !== strpos($content, '</M>'))
		{
			$url = "/people/|REPLACE_VALUE|-index";
			$content = preg_replace('~<M ([^>]+?)>\@(.+?)</M>~', '<a href="' .str_replace('|REPLACE_VALUE|', '\\1', $url) .'" class="J_hover" data-uid="\\1">@\\2</a>', $content);
		}
		
		if (false !== strpos($content, '<T>#'))
		{
			$tagUrl = "/share/commentTag/|REPLACE_VALUE|";
			$content = preg_replace('~<T>#(.+?)#</T>~', '<a href="'.str_replace('|REPLACE_VALUE|', '\\1', $tagUrl) . ' . \'">#\\1#</a>', $content);
		}
		return $content;
	}
}
