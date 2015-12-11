<?php
$theme_dir = dirname( __FILE__ ) . '/';
$theme_url = qa_opt('site_url') . 'qa-theme/' . qa_get_site_theme() . '/';
qa_register_layer('/qa-admin-options.php', 'Theme Options', $theme_dir , $theme_url );

class qa_html_theme extends qa_html_theme_base{
	function head_metas()
	{
		qa_html_theme_base::head_metas();
		$this->output('<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">');
	}
	function head_css()
	{
		if (qa_opt('qat_compression')==2) //Gzip
			$this->output('<LINK REL="stylesheet" TYPE="text/css" HREF="'.$this->rooturl.'qa-styles-gzip.php'.'"/>');
		elseif (qa_opt('qat_compression')==1) //CSS Compression
			$this->output('<LINK REL="stylesheet" TYPE="text/css" HREF="'.$this->rooturl.'qa-styles-commpressed.css'.'"/>');
		else // Normal CSS load
			$this->output('<LINK REL="stylesheet" TYPE="text/css" HREF="'.$this->rooturl.$this->css_name().'"/>');
		
		if (isset($this->content['css_src']))
			foreach ($this->content['css_src'] as $css_src)
				$this->output('<LINK REL="stylesheet" TYPE="text/css" HREF="'.$css_src.'"/>');
				
		if (!empty($this->content['notices']))
			$this->output(
				'<STYLE><!--',
				'.qa-body-js-on .qa-notice {display:none;}',
				'//--></STYLE>'
			);
	}	

	function body_content()
	{
		$this->body_prefix();
		$this->notices();
		$this->widgets('full', 'top');
		$this->header();
		
		$this->output('<DIV CLASS="qa-body-wrapper">', '');
		$this->widgets('full', 'high');
		$this->main();
		$this->sidepanel();
		$this->widgets('full', 'low');
		$this->output('</DIV> <!-- END body-wrapper -->');

		$this->footer();
		$this->widgets('full', 'bottom');
		
		$this->body_suffix();
	}
	function header()
	{
		$this->output('<div class="qa-header">');
		
		$this->output('<DIV CLASS="qa-header-wrapper">');
		$this->logo();
		$this->nav_user_search();
		$this->header_clear();
		$this->output('</DIV>');
		
		$this->output('<DIV CLASS="qa-header-nav"><DIV CLASS="qa-header-wrapper">');
		$this->nav_main_sub();
		$this->header_clear();
		$this->output('</DIV></DIV>');
			
		$this->output('</DIV> <!-- END qa-header -->', '');
		

		
		
	}
	function nav_main_sub()
	{
		$this->nav('main');
	}
	function search_field($search)
	{
		$this->output('<INPUT '.$search['field_tags'].' VALUE="'.@$search['value'].'" CLASS="qa-search-field" placeholder="Looking for something?"/>');
	}

	
	function main()
	{
		$content=$this->content;

		$this->output('<DIV CLASS="qa-main'.(@$this->content['hidden'] ? ' qa-main-hidden' : '').'">');
		$this->output('<DIV CLASS="qa-main-wrapper">');
		$this->widgets('main', 'top');
		
		$this->page_title_error();		
		
		$this->widgets('main', 'high');

		/*if (isset($content['main_form_tags']))
			$this->output('<FORM '.$content['main_form_tags'].'>');*/
			
		$this->main_parts($content);
	
		/*if (isset($content['main_form_tags']))
			$this->output('</FORM>');*/
			
		$this->widgets('main', 'low');

		$this->page_links();
		$this->suggest_next();
		
		$this->widgets('main', 'bottom');

		$this->output('</DIV></DIV> <!-- END qa-main -->', '');
	}
	
	function page_title_error()
	{
		$title=@$this->content['title'];
		$favorite=@$this->content['favorite'];
		
		if (isset($favorite))
			$this->output('<FORM '.$favorite['form_tags'].'>');
			
		$this->output('<H1>');

		if (isset($favorite)) {
			$this->output('<DIV CLASS="qa-favoriting" '.@$favorite['favorite_tags'].'>');
			$this->favorite_inner_html($favorite);
			$this->output('</DIV>');
		}

		if (isset($title))

			$this->output('<span>'.$title.'</span>');
			$this->nav('sub');
		
		if (isset($this->content['error'])) {
			$this->output('</H1>');
			$this->error(@$this->content['error']);
		} else
			$this->output('</H1>');

		if (isset($favorite)) {
			$this->form_hidden_elements(@$favorite['form_hidden']);
			$this->output('</form>');
		}
	}
	
	function footer()
	{
		$this->output('<DIV CLASS="qa-footer"><DIV CLASS="qa-footer-wrapper">');
		
		$this->nav('footer');
		$this->attribution();
		$this->footer_clear();
		
		$this->output('</DIV></DIV> <!-- END qa-footer -->', '');
	}
	
	function q_item_stats($q_item)
	{
		$this->output('<DIV CLASS="qa-q-item-stats">');
		
		$this->voting($q_item);
		$this->a_count($q_item);
		$this->view_count($q_item);
		
		$this->output('</DIV>');
	}
	
	function q_item_main($q_item)
	{
		$this->output('<DIV CLASS="qa-q-item-main">');
		
		$this->q_item_title($q_item);
		$this->q_item_content($q_item);
		
		qa_html_theme_base::post_avatar_meta($q_item, 'qa-q-item');
		$this->post_tags($q_item, 'qa-q-item');
		$this->q_item_buttons($q_item);
			
		$this->output('</DIV>');
	}
	

	function post_meta_what($post, $class)
	{
		if (isset($post['what'])) {
			$classes=$class.'-what';
			if (@$post['what_your'])
				$classes.=' '.$class.'-what-your';
			
			if (isset($post['what_url']))
				$this->output('<a href="'.$post['what_url'].'" class="'.$classes.'">'.$post['what'].'</a>');
			else
				$this->output('<span class="'.$classes.'">'.$post['what'].'</span>');
		}
	}

	function c_item_main($c_item)
	{
		$this->error(@$c_item['error']);

		if (isset($c_item['expand_tags']))
			$this->c_item_expand($c_item);
		elseif (isset($c_item['url']))
			$this->c_item_link($c_item);
		else
			$this->c_item_content($c_item);
		
		$this->output('<DIV CLASS="qa-c-item-footer">');
		$this->post_avatar($c_item, 'qa-c-item');
		$this->post_meta($c_item, 'qa-c-item');
		$this->c_item_buttons($c_item);
		$this->output('</DIV>');
	}
	function attribution()
	{
	// you can disable this links in admin options
		$this->output('<DIV CLASS="qa-attribution-container">');
			qa_html_theme_base::attribution();
			$this->output(
				'<DIV CLASS="qa-attribution">',
				', Design by <A HREF="http://QA-Themes.com/" title="Free Question2Answer Themes and plugins">Q2A Themes</A>',
				'</DIV>'
			);
		$this->output('</DIV>');
	}
}


/*
	Omit PHP closing tag to help avoid accidental output
*/
