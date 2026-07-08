<?php
/**
 * Plugin Name: True View — MCP Abilities
 * Description: All True View MCP tools in one file — posts, pages, media, Yoast SEO meta, menus, site identity, content search. Gated by user capabilities; writes default to draft.
 * Version:     1.1.0
 *
 * Install: place this single file in wp-content/mu-plugins/ and DELETE the older
 *          trueview-mcp-abilities.php / trueview-mcp-write.php / trueview-mcp-extra.php
 *          (keeping them alongside this file would double-register and fatal-error).
 * Requires: WordPress 6.9+ and the MCP Adapter plugin active.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_abilities_api_init',
	function () {

		if ( ! function_exists( 'wp_register_ability' ) ) {
			return;
		}

		// Small helper to keep meta blocks short.
		$meta = function ( $readonly, $destructive = false, $idempotent = false ) {
			return array(
				'mcp'         => array(
					'public' => true,
					'type'   => 'tool',
				),
				'annotations' => array(
					'readonly'    => $readonly,
					'destructive' => $destructive,
					'idempotent'  => $idempotent,
				),
			);
		};

		// Map a WP_Post to a compact array.
		$post_row = function ( $p ) {
			return array(
				'id'     => (int) $p->ID,
				'title'  => get_the_title( $p ),
				'status' => $p->post_status,
				'type'   => $p->post_type,
				'link'   => (string) get_permalink( $p ),
			);
		};

		/* ===== POSTS ===================================================== */

		wp_register_ability(
			'trueview/list-posts',
			array(
				'label'               => 'List Posts',
				'description'         => 'List recent posts. Read-only.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'numberposts' => array(
							'type'    => 'integer',
							'default' => 10,
							'minimum' => 1,
							'maximum' => 50,
						),
						'post_status' => array(
							'type'    => 'string',
							'enum'    => array( 'publish', 'draft', 'pending', 'private', 'any' ),
							'default' => 'publish',
						),
					),
				),
				'output_schema'       => array(
					'type'  => 'array',
					'items' => array( 'type' => 'object' ),
				),
				'execute_callback'    => function ( $input ) use ( $post_row ) {
					$posts = get_posts(
						array(
							'numberposts' => min( max( (int) ( $input['numberposts'] ?? 10 ), 1 ), 50 ),
							'post_status' => $input['post_status'] ?? 'publish',
							'post_type'   => 'post',
						)
					);
					return array_map( $post_row, $posts );
				},
				'permission_callback' => function () {
					return current_user_can( 'read' ); },
				'meta'                => $meta( true, false, true ),
			)
		);

		wp_register_ability(
			'trueview/create-post',
			array(
				'label'               => 'Create Post',
				'description'         => 'Create a post. Defaults to draft; publishes only if status=publish AND the user can publish.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'title'    => array(
							'type'      => 'string',
							'minLength' => 1,
							'maxLength' => 200,
						),
						'content'  => array( 'type' => 'string' ),
						'excerpt'  => array( 'type' => 'string' ),
						'status'   => array(
							'type'    => 'string',
							'enum'    => array( 'draft', 'publish' ),
							'default' => 'draft',
						),
						'category' => array( 'type' => 'string' ),
					),
					'required'   => array( 'title' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					$status = ( ( $input['status'] ?? 'draft' ) === 'publish' && current_user_can( 'publish_posts' ) ) ? 'publish' : 'draft';
					$data   = array(
						'post_title'   => sanitize_text_field( $input['title'] ),
						'post_content' => wp_kses_post( $input['content'] ?? '' ),
						'post_excerpt' => sanitize_text_field( $input['excerpt'] ?? '' ),
						'post_status'  => $status,
						'post_type'    => 'post',
					);
					if ( ! empty( $input['category'] ) ) {
						$existing = get_category_by_slug( sanitize_title( $input['category'] ) );
						$cat_id   = $existing ? (int) $existing->term_id : (int) wp_create_category( $input['category'] );
						if ( $cat_id ) {
							$data['post_category'] = array( $cat_id );
						}
					}
					$id = wp_insert_post( $data, true );
					if ( is_wp_error( $id ) ) {
						throw new Exception( 'Create failed: ' . $id->get_error_message() );
					}
					return array(
						'post_id'  => (int) $id,
						'url'      => (string) get_permalink( $id ),
						'edit_url' => (string) get_edit_post_link( $id, 'raw' ),
						'status'   => get_post_status( $id ),
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' ); },
				'meta'                => $meta( false ),
			)
		);

		wp_register_ability(
			'trueview/update-post',
			array(
				'label'               => 'Update Post',
				'description'         => 'Update an existing post or page. Only fields you pass change.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'id'      => array( 'type' => 'integer' ),
						'title'   => array( 'type' => 'string' ),
						'content' => array( 'type' => 'string' ),
						'excerpt' => array( 'type' => 'string' ),
						'status'  => array(
							'type' => 'string',
							'enum' => array( 'draft', 'pending', 'publish', 'private' ),
						),
					),
					'required'   => array( 'id' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					$id   = (int) $input['id'];
					$post = get_post( $id );
					if ( ! $post ) {
						throw new Exception( "No post with id {$id}." ); }
					if ( ! current_user_can( 'edit_post', $id ) ) {
						throw new Exception( "Not allowed to edit post {$id}." ); }

					$data = array( 'ID' => $id );
					if ( isset( $input['title'] ) ) {
						$data['post_title']   = sanitize_text_field( $input['title'] ); }
					if ( isset( $input['content'] ) ) {
						$data['post_content'] = wp_kses_post( $input['content'] ); }
					if ( isset( $input['excerpt'] ) ) {
						$data['post_excerpt'] = sanitize_text_field( $input['excerpt'] ); }
					if ( isset( $input['status'] ) ) {
						if ( 'publish' === $input['status'] && ! current_user_can( 'publish_posts' ) ) {
							throw new Exception( 'Not allowed to publish.' );
						}
						$data['post_status'] = $input['status'];
					}
					$res = wp_update_post( $data, true );
					if ( is_wp_error( $res ) ) {
						throw new Exception( 'Update failed: ' . $res->get_error_message() ); }

					return array(
						'post_id' => $id,
						'url'     => (string) get_permalink( $id ),
						'status'  => get_post_status( $id ),
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' ); },
				'meta'                => $meta( false, false, true ),
			)
		);

		wp_register_ability(
			'trueview/get-post',
			array(
				'label'               => 'Get Post',
				'description'         => 'Get one post or page in full (title, content, status, excerpt, link). Read-only.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array( 'id' => array( 'type' => 'integer' ) ),
					'required'   => array( 'id' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					$id   = (int) $input['id'];
					$post = get_post( $id );
					if ( ! $post ) {
						throw new Exception( "No post with id {$id}." ); }
					if ( ! current_user_can( 'edit_post', $id ) && 'publish' !== $post->post_status ) {
						throw new Exception( "Not allowed to read post {$id}." );
					}
					return array(
						'id'       => (int) $post->ID,
						'title'    => $post->post_title,
						'content'  => $post->post_content,
						'excerpt'  => $post->post_excerpt,
						'status'   => $post->post_status,
						'type'     => $post->post_type,
						'link'     => (string) get_permalink( $post ),
						'modified' => $post->post_modified,
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'read' ); },
				'meta'                => $meta( true, false, true ),
			)
		);

		wp_register_ability(
			'trueview/trash-post',
			array(
				'label'               => 'Trash Post',
				'description'         => 'Move a post or page to Trash (reversible).',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array( 'id' => array( 'type' => 'integer' ) ),
					'required'   => array( 'id' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					$id = (int) $input['id'];
					if ( ! get_post( $id ) ) {
						throw new Exception( "No post with id {$id}." ); }
					if ( ! current_user_can( 'delete_post', $id ) ) {
						throw new Exception( "Not allowed to trash post {$id}." ); }
					if ( ! wp_trash_post( $id ) ) {
						throw new Exception( "Trash failed for post {$id}." ); }
					return array(
						'post_id' => $id,
						'status'  => get_post_status( $id ),
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' ); },
				'meta'                => $meta( false, true, false ),
			)
		);

		/* ===== PAGES ===================================================== */

		wp_register_ability(
			'trueview/list-pages',
			array(
				'label'               => 'List Pages',
				'description'         => 'List pages (id, title, status, parent, link). Read-only.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'number' => array(
							'type'    => 'integer',
							'default' => 50,
							'minimum' => 1,
							'maximum' => 200,
						),
					),
				),
				'output_schema'       => array(
					'type'  => 'array',
					'items' => array( 'type' => 'object' ),
				),
				'execute_callback'    => function ( $input ) {
					$pages = get_posts(
						array(
							'numberposts' => min( max( (int) ( $input['number'] ?? 50 ), 1 ), 200 ),
							'post_type'   => 'page',
							'post_status' => 'any',
							'orderby'     => 'menu_order title',
							'order'       => 'ASC',
						)
					);
					return array_map(
						function ( $p ) {
							return array(
								'id'     => (int) $p->ID,
								'title'  => get_the_title( $p ),
								'status' => $p->post_status,
								'parent' => (int) $p->post_parent,
								'link'   => (string) get_permalink( $p ),
							);
						},
						$pages
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'read' ); },
				'meta'                => $meta( true, false, true ),
			)
		);

		wp_register_ability(
			'trueview/create-page',
			array(
				'label'               => 'Create Page',
				'description'         => 'Create a page. Defaults to draft. Optional parent id. (Edit/get/trash via the post tools.)',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'title'   => array(
							'type'      => 'string',
							'minLength' => 1,
							'maxLength' => 200,
						),
						'content' => array( 'type' => 'string' ),
						'status'  => array(
							'type'    => 'string',
							'enum'    => array( 'draft', 'publish' ),
							'default' => 'draft',
						),
						'parent'  => array( 'type' => 'integer' ),
					),
					'required'   => array( 'title' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					$status = ( ( $input['status'] ?? 'draft' ) === 'publish' && current_user_can( 'publish_pages' ) ) ? 'publish' : 'draft';
					$id     = wp_insert_post(
						array(
							'post_title'   => sanitize_text_field( $input['title'] ),
							'post_content' => wp_kses_post( $input['content'] ?? '' ),
							'post_status'  => $status,
							'post_type'    => 'page',
							'post_parent'  => isset( $input['parent'] ) ? (int) $input['parent'] : 0,
						),
						true
					);
					if ( is_wp_error( $id ) ) {
						throw new Exception( 'Create page failed: ' . $id->get_error_message() ); }
					return array(
						'post_id'  => (int) $id,
						'url'      => (string) get_permalink( $id ),
						'edit_url' => (string) get_edit_post_link( $id, 'raw' ),
						'status'   => get_post_status( $id ),
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_pages' ); },
				'meta'                => $meta( false ),
			)
		);

		/* ===== MEDIA ===================================================== */

		wp_register_ability(
			'trueview/list-media',
			array(
				'label'               => 'List Media',
				'description'         => 'List media library items. Read-only.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'number' => array(
							'type'    => 'integer',
							'default' => 20,
							'minimum' => 1,
							'maximum' => 100,
						),
						'search' => array(
							'type'        => 'string',
							'description' => 'Filter by text in the media title/filename.',
						),
					),
				),
				'output_schema'       => array(
					'type'  => 'array',
					'items' => array( 'type' => 'object' ),
				),
				'execute_callback'    => function ( $input ) {
					$items = get_posts(
						array(
							'post_type'   => 'attachment',
							'post_status' => 'inherit',
							'numberposts' => min( max( (int) ( $input['number'] ?? 20 ), 1 ), 100 ),
							's'           => ! empty( $input['search'] ) ? sanitize_text_field( $input['search'] ) : '',
						)
					);
					return array_map(
						function ( $a ) {
							return array(
								'id'    => (int) $a->ID,
								'title' => get_the_title( $a ),
								'url'   => (string) wp_get_attachment_url( $a->ID ),
								'mime'  => $a->post_mime_type,
								'alt'   => (string) get_post_meta( $a->ID, '_wp_attachment_image_alt', true ),
							);
						},
						$items
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'upload_files' ); },
				'meta'                => $meta( true, false, true ),
			)
		);

		wp_register_ability(
			'trueview/upload-image',
			array(
				'label'               => 'Upload Image from URL',
				'description'         => 'Download an image from a URL into the media library; optional alt, attach-to-post and set-featured.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'url'          => array( 'type' => 'string' ),
						'alt'          => array( 'type' => 'string' ),
						'attach_to'    => array( 'type' => 'integer' ),
						'set_featured' => array(
							'type'    => 'boolean',
							'default' => false,
						),
					),
					'required'   => array( 'url' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					require_once ABSPATH . 'wp-admin/includes/media.php';
					require_once ABSPATH . 'wp-admin/includes/file.php';
					require_once ABSPATH . 'wp-admin/includes/image.php';

					$attach_to = isset( $input['attach_to'] ) ? (int) $input['attach_to'] : 0;
					$alt       = $input['alt'] ?? '';
					$id        = media_sideload_image( esc_url_raw( $input['url'] ), $attach_to, $alt, 'id' );
					if ( is_wp_error( $id ) ) {
						throw new Exception( 'Upload failed: ' . $id->get_error_message() ); }

					if ( '' !== $alt ) {
						update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( $alt ) );
					}
					if ( $attach_to && ! empty( $input['set_featured'] ) && current_user_can( 'edit_post', $attach_to ) ) {
						set_post_thumbnail( $attach_to, $id );
					}
					return array(
						'attachment_id' => (int) $id,
						'url'           => (string) wp_get_attachment_url( $id ),
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'upload_files' ); },
				'meta'                => $meta( false ),
			)
		);

		wp_register_ability(
			'trueview/set-featured-image',
			array(
				'label'               => 'Set Featured Image',
				'description'         => 'Set an existing media item as a post/page featured image.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'post_id'       => array( 'type' => 'integer' ),
						'attachment_id' => array( 'type' => 'integer' ),
					),
					'required'   => array( 'post_id', 'attachment_id' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					$post_id = (int) $input['post_id'];
					if ( ! current_user_can( 'edit_post', $post_id ) ) {
						throw new Exception( "Not allowed to edit post {$post_id}." ); }
					return array( 'ok' => (bool) set_post_thumbnail( $post_id, (int) $input['attachment_id'] ) );
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' ); },
				'meta'                => $meta( false, false, true ),
			)
		);

		wp_register_ability(
			'trueview/set-image-alt',
			array(
				'label'               => 'Set Image Alt Text',
				'description'         => 'Set alt text on a media item.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'attachment_id' => array( 'type' => 'integer' ),
						'alt'           => array( 'type' => 'string' ),
					),
					'required'   => array( 'attachment_id', 'alt' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					$id = (int) $input['attachment_id'];
					if ( ! current_user_can( 'edit_post', $id ) ) {
						throw new Exception( "Not allowed to edit attachment {$id}." ); }
					update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( $input['alt'] ) );
					return array( 'ok' => true );
				},
				'permission_callback' => function () {
					return current_user_can( 'upload_files' ); },
				'meta'                => $meta( false, false, true ),
			)
		);

		wp_register_ability(
			'trueview/upload-file',
			array(
				'label'               => 'Upload Local File',
				'description'         => 'Upload a file sent as base64 into the media library (for local images Claude has optimized). Optional alt, attach-to-post and set-featured.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'filename'     => array(
							'type'        => 'string',
							'description' => 'File name with extension, e.g. hero.webp',
						),
						'data_base64'  => array(
							'type'        => 'string',
							'description' => 'Base64-encoded file contents.',
						),
						'alt'          => array( 'type' => 'string' ),
						'attach_to'    => array( 'type' => 'integer' ),
						'set_featured' => array(
							'type'    => 'boolean',
							'default' => false,
						),
					),
					'required'   => array( 'filename', 'data_base64' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					require_once ABSPATH . 'wp-admin/includes/media.php';
					require_once ABSPATH . 'wp-admin/includes/file.php';
					require_once ABSPATH . 'wp-admin/includes/image.php';

					$name = sanitize_file_name( $input['filename'] );
					if ( '' === $name ) {
						throw new Exception( 'Invalid filename.' ); }

					$check = wp_check_filetype( $name );
					if ( empty( $check['ext'] ) ) {
						throw new Exception( 'Disallowed file type: ' . $name ); }

					$bytes = base64_decode( $input['data_base64'], true );
					if ( false === $bytes ) {
						throw new Exception( 'Bad base64 data.' ); }

					$tmp = wp_tempnam( $name );
					if ( ! $tmp ) {
						throw new Exception( 'Could not create temp file.' ); }
					file_put_contents( $tmp, $bytes );

					$file_array = array(
						'name'     => $name,
						'tmp_name' => $tmp,
					);
					$attach_to  = isset( $input['attach_to'] ) ? (int) $input['attach_to'] : 0;

					$id = media_handle_sideload( $file_array, $attach_to );
					if ( is_wp_error( $id ) ) {
						@unlink( $tmp );
						throw new Exception( 'Upload failed: ' . $id->get_error_message() );
					}

					if ( ! empty( $input['alt'] ) ) {
						update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( $input['alt'] ) );
					}
					if ( $attach_to && ! empty( $input['set_featured'] ) && current_user_can( 'edit_post', $attach_to ) ) {
						set_post_thumbnail( $attach_to, $id );
					}

					return array(
						'attachment_id' => (int) $id,
						'url'           => (string) wp_get_attachment_url( $id ),
						'filename'      => $name,
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'upload_files' ); },
				'meta'                => $meta( false ),
			)
		);

		wp_register_ability(
			'trueview/update-media',
			array(
				'label'               => 'Update Media',
				'description'         => 'Edit a media item\'s title, caption, description and/or alt text. Only fields you pass change.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'attachment_id' => array( 'type' => 'integer' ),
						'title'         => array( 'type' => 'string' ),
						'caption'       => array( 'type' => 'string' ),
						'description'   => array( 'type' => 'string' ),
						'alt'           => array( 'type' => 'string' ),
					),
					'required'   => array( 'attachment_id' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					$id  = (int) $input['attachment_id'];
					$att = get_post( $id );
					if ( ! $att || 'attachment' !== $att->post_type ) {
						throw new Exception( "No attachment with id {$id}." ); }
					if ( ! current_user_can( 'edit_post', $id ) ) {
						throw new Exception( "Not allowed to edit attachment {$id}." ); }

					$data = array( 'ID' => $id );
					if ( isset( $input['title'] ) ) {
						$data['post_title']   = sanitize_text_field( $input['title'] ); }
					if ( isset( $input['caption'] ) ) {
						$data['post_excerpt'] = sanitize_text_field( $input['caption'] ); }
					if ( isset( $input['description'] ) ) {
						$data['post_content'] = wp_kses_post( $input['description'] ); }
					if ( count( $data ) > 1 ) {
						$res = wp_update_post( $data, true );
						if ( is_wp_error( $res ) ) {
							throw new Exception( 'Update failed: ' . $res->get_error_message() ); }
					}
					if ( isset( $input['alt'] ) ) {
						update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( $input['alt'] ) );
					}
					return array(
						'attachment_id' => $id,
						'title'         => get_the_title( $id ),
						'alt'           => (string) get_post_meta( $id, '_wp_attachment_image_alt', true ),
						'url'           => (string) wp_get_attachment_url( $id ),
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'upload_files' ); },
				'meta'                => $meta( false, false, true ),
			)
		);

		wp_register_ability(
			'trueview/delete-media',
			array(
				'label'               => 'Delete Media',
				'description'         => 'Delete a media library item. Permanently deletes the file and record on sites without MEDIA_TRASH (the WordPress default); where MEDIA_TRASH is enabled and force=false it moves to Trash instead.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'attachment_id' => array( 'type' => 'integer' ),
						'force'         => array(
							'type'        => 'boolean',
							'default'     => false,
							'description' => 'Bypass Trash and permanently delete even where MEDIA_TRASH is enabled.',
						),
					),
					'required'   => array( 'attachment_id' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					$id  = (int) $input['attachment_id'];
					$att = get_post( $id );
					if ( ! $att || 'attachment' !== $att->post_type ) {
						throw new Exception( "No attachment with id {$id}." ); }
					if ( ! current_user_can( 'delete_post', $id ) ) {
						throw new Exception( "Not allowed to delete attachment {$id}." ); }
					$res = wp_delete_attachment( $id, ! empty( $input['force'] ) );
					if ( ! $res ) {
						throw new Exception( "Delete failed for attachment {$id}." ); }
					return array(
						'deleted'       => true,
						'attachment_id' => $id,
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'upload_files' ); },
				'meta'                => $meta( false, true, false ),
			)
		);

		/* ===== SEO META (Yoast) ========================================= */

		wp_register_ability(
			'trueview/get-seo-meta',
			array(
				'label'               => 'Get SEO Meta',
				'description'         => 'Get Yoast SEO title, meta description and focus keyword for a post/page.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array( 'post_id' => array( 'type' => 'integer' ) ),
					'required'   => array( 'post_id' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					$id = (int) $input['post_id'];
					if ( ! current_user_can( 'edit_post', $id ) ) {
						throw new Exception( "Not allowed to read post {$id}." ); }
					return array(
						'title'    => (string) get_post_meta( $id, '_yoast_wpseo_title', true ),
						'metadesc' => (string) get_post_meta( $id, '_yoast_wpseo_metadesc', true ),
						'focuskw'  => (string) get_post_meta( $id, '_yoast_wpseo_focuskw', true ),
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' ); },
				'meta'                => $meta( true, false, true ),
			)
		);

		wp_register_ability(
			'trueview/set-seo-meta',
			array(
				'label'               => 'Set SEO Meta',
				'description'         => 'Set Yoast SEO title / meta description / focus keyword. Only fields you pass change.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'post_id'  => array( 'type' => 'integer' ),
						'title'    => array( 'type' => 'string' ),
						'metadesc' => array( 'type' => 'string' ),
						'focuskw'  => array( 'type' => 'string' ),
					),
					'required'   => array( 'post_id' ),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					$id = (int) $input['post_id'];
					if ( ! current_user_can( 'edit_post', $id ) ) {
						throw new Exception( "Not allowed to edit post {$id}." ); }
					if ( isset( $input['title'] ) ) {
						update_post_meta( $id, '_yoast_wpseo_title', sanitize_text_field( $input['title'] ) ); }
					if ( isset( $input['metadesc'] ) ) {
						update_post_meta( $id, '_yoast_wpseo_metadesc', sanitize_text_field( $input['metadesc'] ) ); }
					if ( isset( $input['focuskw'] ) ) {
						update_post_meta( $id, '_yoast_wpseo_focuskw', sanitize_text_field( $input['focuskw'] ) ); }
					return array( 'ok' => true );
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' ); },
				'meta'                => $meta( false, false, true ),
			)
		);

		/* ===== MENUS (read-only) + SITE IDENTITY ======================== */

		wp_register_ability(
			'trueview/list-menus',
			array(
				'label'               => 'List Menus',
				'description'         => 'List nav menus. Read-only.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(),
				),
				'output_schema'       => array(
					'type'  => 'array',
					'items' => array( 'type' => 'object' ),
				),
				'execute_callback'    => function () {
					return array_map(
						function ( $m ) {
							return array(
								'id'    => (int) $m->term_id,
								'name'  => $m->name,
								'slug'  => $m->slug,
								'count' => (int) $m->count,
							);
						},
						wp_get_nav_menus()
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_theme_options' ); },
				'meta'                => $meta( true, false, true ),
			)
		);

		wp_register_ability(
			'trueview/get-menu-items',
			array(
				'label'               => 'Get Menu Items',
				'description'         => 'List items in a nav menu by id, slug or name. Read-only.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array( 'menu' => array( 'type' => 'string' ) ),
					'required'   => array( 'menu' ),
				),
				'output_schema'       => array(
					'type'  => 'array',
					'items' => array( 'type' => 'object' ),
				),
				'execute_callback'    => function ( $input ) {
					$items = wp_get_nav_menu_items( $input['menu'] );
					if ( false === $items ) {
						throw new Exception( 'Menu not found: ' . $input['menu'] ); }
					return array_map(
						function ( $i ) {
							return array(
								'id'     => (int) $i->ID,
								'label'  => $i->title,
								'url'    => $i->url,
								'order'  => (int) $i->menu_order,
								'parent' => (int) $i->menu_item_parent,
							);
						},
						$items
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_theme_options' ); },
				'meta'                => $meta( true, false, true ),
			)
		);

		wp_register_ability(
			'trueview/get-site-identity',
			array(
				'label'               => 'Get Site Identity',
				'description'         => 'Get site title, tagline and URL. Read-only.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function () {
					return array(
						'title'   => get_bloginfo( 'name' ),
						'tagline' => get_bloginfo( 'description' ),
						'url'     => home_url(),
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'read' ); },
				'meta'                => $meta( true, false, true ),
			)
		);

		wp_register_ability(
			'trueview/set-site-identity',
			array(
				'label'               => 'Set Site Identity',
				'description'         => 'Set site title and/or tagline. Only fields you pass change.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'title'   => array( 'type' => 'string' ),
						'tagline' => array( 'type' => 'string' ),
					),
				),
				'output_schema'       => array( 'type' => 'object' ),
				'execute_callback'    => function ( $input ) {
					if ( ! current_user_can( 'manage_options' ) ) {
						throw new Exception( 'Not allowed to change site identity.' ); }
					if ( isset( $input['title'] ) ) {
						update_option( 'blogname', sanitize_text_field( $input['title'] ) ); }
					if ( isset( $input['tagline'] ) ) {
						update_option( 'blogdescription', sanitize_text_field( $input['tagline'] ) ); }
					return array( 'ok' => true );
				},
				'permission_callback' => function () {
					return current_user_can( 'manage_options' ); },
				'meta'                => $meta( false, false, true ),
			)
		);

		/* ===== SEARCH (freebie) ========================================= */

		wp_register_ability(
			'trueview/search-content',
			array(
				'label'               => 'Search Content',
				'description'         => 'Find text across posts and pages. Read-only.',
				'category'            => 'site',
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'query'     => array(
							'type'      => 'string',
							'minLength' => 1,
						),
						'post_type' => array(
							'type'    => 'string',
							'enum'    => array( 'any', 'post', 'page' ),
							'default' => 'any',
						),
						'number'    => array(
							'type'    => 'integer',
							'default' => 20,
							'minimum' => 1,
							'maximum' => 50,
						),
					),
					'required'   => array( 'query' ),
				),
				'output_schema'       => array(
					'type'  => 'array',
					'items' => array( 'type' => 'object' ),
				),
				'execute_callback'    => function ( $input ) use ( $post_row ) {
					$type  = $input['post_type'] ?? 'any';
					$query = new WP_Query(
						array(
							's'              => sanitize_text_field( $input['query'] ),
							'post_type'      => 'any' === $type ? array( 'post', 'page' ) : $type,
							'post_status'    => 'any',
							'posts_per_page' => min( max( (int) ( $input['number'] ?? 20 ), 1 ), 50 ),
							'no_found_rows'  => true,
						)
					);
					return array_map( $post_row, $query->posts );
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' ); },
				'meta'                => $meta( true, false, true ),
			)
		);
	}
);
