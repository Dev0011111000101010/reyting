<?php
global $wprecall;
$wprecall->fields['google_thumbnail'] = 'Google Thumbnail';
class Rcl_Google_Thumbnail_Field extends Rcl_Field_Abstract {

	public $btnname;
	public $desc;

	function __construct( $args ) {
		parent::__construct( $args );
	}

	function get_options() {
		$options = [];
		$options[] = array(
			'type'	 => 'text',
			'slug'	 => 'field_buttonname',
			'default'	 => $this->field_buttonname,
			'title'	 => __( 'Текст кнопки загрузка', 'wp-recall' )
		);
		$options[] = array(
			'type'	 => 'textarea',
			'slug'	 => 'what-to-upload',
			'default'	 => $this->desc,
			'title'	 => __( 'Описание что нужно загрузить', 'wp-recall' )
		);
		return $options;
	}

	function get_input() {
		$content = '';

		$btn          = $this->field_buttonname;
		$fieldcontent = $this->value;
		$content.='<div class="rcl-form-field">';
		$iframe       = '<iframe class="post-thumb" src="' . $fieldcontent . '"></iframe>';
		$input   = '<input id="' . $this->slug . '" type="text" ' . $this->required . ' ' . $this->placeholder . ' ' . ' name="' . $this->slug . '" value="' . $fieldcontent . '"/>';
		$content .= $iframe.'<div class="repeater-single d-flex thumbnail-field">';
		$content .= $input.'<div class="button-upload_google btn btn-primary" onclick="thumbnailUploader(this)"><span>' . $btn . '</span></div>';
		$content .= '</div></div>';

		return $content;

	}

	function get_value(){
		return implode(', ', $this->value);
	}

}

add_filter( 'rcl_fields', 'add_google_thumbnail_field' );
function add_google_thumbnail_field( $fields ) {

	$fields['google_thumbnail'] = array(
		'label' => 'Google Thumbnail',
		'class' => 'Rcl_Google_Thumbnail_Field'
	);

	return $fields;
}

add_filter('rcl_field_types_manager_post_1', function ($data) {
	$data['google_thumbnail'] = 'Google Thumbnail';
	return $data;
});

?>