<?php


use Goteo\Library\Text,
    Goteo\Model,
    Goteo\Core\Redirection,
    Goteo\Library\SuperForm;

define('ADMIN_NOAUTOSAVE', true);

$post = $this['post'];

if (!$post instanceof Model\Blog\Post) {
    throw new Redirection('/admin/blog');
}

// Superform
    $tags = array();

    foreach ($this['tags'] as $value => $label) {
        $tags[] =  array(
            'value'     => $value,
            'label'     => $label,
            'checked'   => isset($post->tags[$value])
            );
    }

    $allow = array(
        array(
            'value'     => 1,
            'label'     => Text::_("Yes")
            ),
        array(
            'value'     => 0,
            'label'     => Text::_("No")
            )
    );


    $images = array();
    foreach ($post->gallery as $image) {
        $images[] = array(
            'type'  => 'html',
            'class' => 'inline gallery-image',
            'html'  => is_object($image) ?
                       $image . '<img src="'.SRC_URL.'/image/'.$image->id.'/128/128" alt="'.Text::_("Image").'" /><button class="image-remove weak" type="submit" name="gallery-'.$image->id.'-remove" title="'.Text::_("Remove image").'" value="remove"></button>' :
                       ''
        );

    }

?>
<script type="text/javascript" src="/view/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	// Lanza wysiwyg contenido
	CKEDITOR.replace('text_editor', {
		toolbar: 'Full',
		toolbar_Full: [
				['Source','-'],
				['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
				['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
				'/',
				['Bold','Italic','Underline','Strike'],
				['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
				['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
				['Link','Unlink','Anchor'],
                ['Image','Format','FontSize'],
			  ],
		skin: 'kama',
		language: 'es',
		height: '300px',
		width: '675px'
	});
});
</script>

<form method="post" action="/admin/blog/<?php echo $this['action']; ?>/<?php echo $post->id; ?>" class="project" enctype="multipart/form-data">

    <?php echo new SuperForm(array(

        'action'        => '',
        'level'         => 3,
        'method'        => 'post',
        'title'         => '',
        'hint'          => Text::get('guide-blog-posting'),
        'class'         => 'aqua',
        'footer'        => array(
            'view-step-preview' => array(
                'type'  => 'submit',
                'name'  => 'save-post',
                'label' => Text::get('regular-save'),
                'class' => 'next'
            )
        ),
        'elements'      => array(
            'id' => array (
                'type' => 'hidden',
                'value' => $post->id
            ),
            'blog' => array (
                'type' => 'hidden',
                'value' => $post->blog
            ),
            'title' => array(
                'type'      => 'textbox',
                'required'  => true,
                'size'      => 20,
                'title'     => Text::_("Título"),
                'hint'      => Text::get('tooltip-updates-title'),
                'errors'    => !empty($errors['title']) ? array($errors['title']) : array(),
                'value'     => $post->title,
            ),
            'text' => array(
                'type'      => 'textarea',
                'required'  => true,
                'cols'      => 40,
                'rows'      => 4,
                'title'     => Text::_("Text entry"),
                'hint'      => Text::get('tooltip-updates-text'),
                'errors'    => !empty($errors['text']) ? array($errors['text']) : array(),
                'value'     => $post->text
            ),
            'image' => array(
                'title'     => Text::_("Image"),
                'type'      => 'group',
                'hint'      => Text::get('tooltip-updates-image'),
                'errors'    => !empty($errors['image']) ? array($errors['image']) : array(),
                'class'     => 'image',
                'children'  => array(
                    'image_upload'    => array(
                        'type'  => 'file',
                        'class' => 'inline image_upload',
                        'label' => Text::get('form-image_upload-button'),
                        'hint'  => Text::get('tooltip-updates-image_upload'),
                    )
                )
            ),

            'gallery' => array(
                'type'  => 'group',
                'title' => Text::get('overview-field-image_gallery'),
                'class' => 'inline',
                'children'  => $images
            ),

            'media' => array(
                'type'      => 'textbox',
                'title'     => Text::_("Video"),
                'class'     => 'media',
                'hint'      => Text::get('tooltip-updates-media'),
                'errors'    => !empty($errors['media']) ? array($errors['media']) : array(),
                'value'     => (string) $post->media,
                'children'  => array(
                    'media-preview' => array(
                        'title' => 'Vista previa',
                        'class' => 'media-preview inline',
                        'type'  => 'html',
                        'html'  => !empty($post->media) ? $post->media->getEmbedCode() : ''
                    )
                )
            ),
            'legend' => array(
                'type'      => 'textarea',
                'title'     => Text::get('regular-media_legend'),
                'value'     => $post->legend,
            ),
            
            'tags' => array(
                'type'      => 'checkboxes',
                'name'      => 'tags[]',
                'title'     => Text::_("Tags"),
                'options'   => $tags,
                'hint'      => Text::get('tooltip-updates-tags'),
                'errors'    => !empty($errors['tags']) ? array($errors['tags']) : array(),
            ),

            'date' => array(
                'type'      => 'datebox',
                'required'  => true,
                'title'     => Text::_("Date of publication"),
                'hint'      => Text::get('tooltip-updates-date'),
                'size'      => 8,
                'value'     => $post->date
            ),
            'allow' => array(
                'title'     => Text::_("Allows Comment"),
                'type'      => 'slider',
                'options'   => $allow,
                'class'     => 'currently cols_' . count($allow),
                'hint'      => Text::get('tooltip-updates-allow_comments'),
                'errors'    => !empty($errors['allow']) ? array($errors['allow']) : array(),
                'value'     => (int) $post->allow
            ),
            'publish' => array(
                'title'     => Text::_("Posted"),
                'type'      => 'slider',
                'options'   => $allow,
                'class'     => 'currently cols_' . count($allow),
                'hint'      => Text::get('tooltip-updates-publish'),
                'errors'    => !empty($errors['publish']) ? array($errors['publish']) : array(),
                'value'     => (int) $post->publish
            ),
            'home' => array(
                'title'     => Text::_("in Focus"),
                'type'      => 'slider',
                'options'   => $allow,
                'class'     => 'currently cols_' . count($allow),
                'hint'      => Text::get('tooltip-updates-home'),
                'errors'    => !empty($errors['home']) ? array($errors['home']) : array(),
                'value'     => (int) $post->home
            ),
            'footer' => array(
                'title'     => Text::_("Link in Footer"),
                'type'      => 'slider',
                'options'   => $allow,
                'class'     => 'currently cols_' . count($allow),
                'hint'      => Text::get('tooltip-updates-footer'),
                'errors'    => !empty($errors['footer']) ? array($errors['footer']) : array(),
                'value'     => (int) $post->footer
            )

        )

    ));
    ?>

</form>