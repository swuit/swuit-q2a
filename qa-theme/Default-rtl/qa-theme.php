<?php
	class qa_html_theme extends qa_html_theme_base
	{
		function attribution()
		{
			// Please see the license at the top of this file before changing this link. Thank you.
				
			qa_html_theme_base::attribution();

			// modxclub [start] Please erase. Use this theme according to license of Question2Answer.
			$this->output(
'<div class="qa-attribution">, R2L Edition by <a href="http://www.Tohid.ir.tc" title="Freelance Web Designer, Programmer, Security Consultant">',
'Towhid Nategheian</a>',
'</div>'
			);
			// modxclub [end]
		}
	}

/*
	Omit PHP closing tag to help avoid accidental output
*/