# Move Toolset Metabox Above WYSIWYG Editor

WordPress plugin to allow moving metaboxes created with the Toolset plugin above the WYSIWYG editor, with the filter `fe_tmm_move_metabox_after_title_ids`.

## Example Implementation

Add the following code to `functions.php` or a [mu-plugin](https://codex.wordpress.org/Must_Use_Plugins)].

```
add_filter( 'fe_tmm_move_metabox_after_title_ids', function ( $group_ids ) {
	$group_ids[] = 1813;  // Move group id 1813 above the WYSIWYG editor.
	$group_ids[] = 1820;  // Move group id 1820 above the WYSIWYG editor.
	return $group_ids;
} );
```

## Credits

[Sal Ferrarello](https://salferrarello.com) / [@salcode](https://twitter.com/salcode)
