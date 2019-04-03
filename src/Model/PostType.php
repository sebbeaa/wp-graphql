<?php

namespace WPGraphQL\Model;


use GraphQLRelay\Relay;

/**
 * Class PostType - Models data for PostTypes
 *
 * @property string $id
 * @property string $name
 * @property object $labels
 * @property string $description
 * @property bool   $public
 * @property bool   $hierarchical
 * @property bool   $excludeFromSearch
 * @property bool   $publiclyQueryable
 * @property bool   $showUi
 * @property bool   $showInMenu
 * @property bool   $showInNavMenus
 * @property bool   $showInAdminBar
 * @property int    $menuPosition
 * @property string $menuIcon
 * @property bool   $hasArchive
 * @property bool   $canExport
 * @property bool   $deleteWithUser
 * @property bool   $showInRest
 * @property string $restBase
 * @property string $restControllerClass
 * @property bool   $showInGraphql
 * @property string $graphqlSingleName
 * @property string $graphql_single_name
 * @property string $graphqlPluralName
 * @property string $graphql_plural_name
 *
 * @package WPGraphQL\Model
 */
class PostType extends Model {

	/**
	 * Stores the incoming WP_Post_Type to be modeled
	 *
	 * @var \WP_Post_Type $data
	 * @access protected
	 */
	protected $data;

	/**
	 * PostType constructor.
	 *
	 * @param \WP_Post_Type $post_type The incoming post type to model
	 *
	 * @access public
	 * @throws \Exception
	 */
	public function __construct( \WP_Post_Type $post_type ) {
		$this->data = $post_type;

		$allowed_restricted_fields = [
			'id',
			'name',
			'description',
			'hierarchical',
			'slug',
			'taxonomies',
			'graphql_single_name',
			'graphqlSingleName',
			'graphql_plural_name',
			'graphqlPluralName',
			'showInGraphql',
		];

		parent::__construct( $post_type->cap->edit_posts, $allowed_restricted_fields );
		$this->init();
	}

	/**
	 * Method for determining if the data should be considered private or not
	 *
	 * @access public
	 * @return bool
	 */
	public function is_private() {

		if ( false === $this->data->public && ! current_user_can( $this->data->cap->edit_posts ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Initializes the object
	 *
	 * @access protected
	 * @return void
	 */
	protected function init() {

		if ( 'private' === $this->get_visibility() ) {
			return;
		}

		if ( empty( $this->fields ) ) {

			$this->fields = [
				'id' => function() {
					return ! empty( $this->data->name ) ? Relay::toGlobalId( 'postType', $this->data->name ) : null;
				},
				'name' => function() {
					return ! empty( $this->data->name ) ? $this->data->name : null;
				},
				'label' => function() {
					return ! empty( $this->data->label ) ? $this->data->label : null;
				},
				'labels' => function() {
					return get_post_type_labels( $this->data );
				},
				'description' => function() {
					return ! empty( $this->data->description ) ? $this->data->description : '';
				},
				'public' => function() {
					return ! empty( $this->data->public ) ? (bool) $this->data->public : null;
				},
				'hierarchical' => function() {
					return ( true === $this->data->hierarchical || ! empty( $this->data->hierarchical ) ) ? true : false;
				},
				'excludeFromSearch' => function() {
					return ( true === $this->data->exclude_from_search ) ? true : false;
				},
				'publiclyQueryable' => function() {
					return ( true === $this->data->publicly_queryable ) ? true : false;
				},
				'showUi' => function() {
					return ( true === $this->data->show_ui ) ? true : false;
				},
				'showInMenu' => function() {
					return ( true === $this->data->show_in_menu ) ? true : false;
				},
				'showInNavMenus' => function() {
					return ( true === $this->data->show_in_nav_menus ) ? true : false;
				},
				'showInAdminBar' => function() {
					return ( true === $this->data->show_in_admin_bar ) ? true : false;
				},
				'menuPosition' => function() {
					return ! empty( $this->data->menu_position ) ? $this->data->menu_position : null;
				},
				'menuIcon' => function() {
					return ! empty( $this->data->menu_icon ) ? $this->data->menu_icon : null;
				},
				'hasArchive' => function() {
					return ( true === $this->data->has_archive ) ? true : false;
				},
				'canExport' => function() {
					return ( true === $this->data->can_export ) ? true : false;
				},
				'deleteWithUser' => function() {
					return ( true === $this->data->delete_with_user ) ? true : false;
				},
				'taxonomies' => function() {
					$object_taxonomies = get_object_taxonomies( $this->data->name );
					return ( ! empty( $object_taxonomies ) ) ? $object_taxonomies : null;
				},
				'showInRest' => function() {
					return ( true === $this->data->show_in_rest ) ? true : false;
				},
				'restBase' => function() {
					return ! empty( $this->data->rest_base ) ? $this->data->rest_base : null;
				},
				'restControllerClass' => function() {
					return ! empty( $this->data->rest_controller_class ) ? $this->data->rest_controller_class : null;
				},
				'showInGraphql' => function() {
					return ( true === $this->data->show_in_graphql ) ? true : false;
				},
				'graphqlSingleName' => function() {
					return ! empty( $this->data->graphql_single_name ) ? $this->data->graphql_single_name : null;
				},
				'graphql_single_name' => function() {
					return ! empty( $this->data->graphql_single_name ) ? $this->data->graphql_single_name : null;
				},
				'graphqlPluralName' => function() {
					return ! empty( $this->data->graphql_plural_name ) ? $this->data->graphql_plural_name : null;
				},
				'graphql_plural_name' => function() {
					return ! empty( $this->data->graphql_plural_name ) ? $this->data->graphql_plural_name : null;
				},
			];

			parent::prepare_fields();

		}
	}
}