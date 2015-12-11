<?php
/**
 * Author	水脉烟香
 * Blog  	http://www.smyx.net/
 * Created  Dec 12, 2012
 */

// 将所有链接的相对地址设置为绝对地址
function qa_path_to_root() {
	$site_url = qa_opt('site_url');
	if (!empty($site_url)) {
		return $site_url;
	} else {
		global $qa_root_url_relative;
		return $qa_root_url_relative;
	} 
} 
// 使用社交网络头像
function qa_get_user_avatar_html($flags, $email, $handle, $blobid, $width, $height, $size, $padding = false) {
	if (qa_opt('avatar_allow_gravatar') && ($flags &QA_USER_FLAGS_SHOW_GRAVATAR)) {
		$html = qa_get_gravatar_html($email, $size);
	} elseif (qa_opt('avatar_allow_upload') && (($flags &QA_USER_FLAGS_SHOW_AVATAR))) {
		if (isset($blobid)) {
			$html = qa_get_avatar_blob_html($blobid, $width, $height, $size, $padding);
		} elseif (strlen($handle)) {
			$userprofile = qa_db_select_with_pending(qa_db_user_profile_selectspec($handle, false));
			if (!empty($userprofile['social_avatar'])) {
				$html = '<img src="' . $userprofile['social_avatar'] . '" width="' . $size . '" height="' . $size . '" class="qa-avatar-image" />';
			} else {
				$html = null;
			} 
		} 
	} 
	if (!isset($html)) {
		if ((qa_opt('avatar_allow_gravatar') || qa_opt('avatar_allow_upload')) && qa_opt('avatar_default_show') && strlen(qa_opt('avatar_default_blobid'))) {
			$html = qa_get_avatar_blob_html(qa_opt('avatar_default_blobid'), qa_opt('avatar_default_width'), qa_opt('avatar_default_height'), $size, $padding);
		} else {
			$html = null;
		} 
	} 
	return (isset($html) && strlen($handle)) ? ('<A HREF="' . qa_path_html('user/' . $handle) . '" CLASS="qa-avatar-link">' . $html . '</A>') : $html;
} 
