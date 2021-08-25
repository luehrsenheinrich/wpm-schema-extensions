<?php
/**
 * WpmSchema\Schema_Spouse\WPM_Spouse class
 * Extends the 'Person' graph piece as the 'Spouse' schema graph piece.
 *
 * @see https://developer.yoast.com/features/schema/integration-guidelines
 *
 * @package wpmschema
 */

namespace WpMunich\wpmschema\Schema_Spouse;

use Yoast\WP\SEO\Generators\Schema\Person;

/**
 * WpmSchema\Schema_Spouse\WPM_Spouse class
 */
class Spouse extends Person {

	/**
	 * The Schema type we use for this class.
	 *
	 * @var string[]
	 */
	protected $type = array( 'Person' );

	/**
	 * We store the spouse schema id here for use in filters and actions
	 *
	 * @var string
	 */
	protected $spouse_schema_id = '';

	/**
	 * We store the user_id of the person we are the spouse of.
	 *
	 * @var int
	 */
	protected $spouse_of_user_id = 0;

	/**
	 * Determine whether we should return Spouse schema.
	 *
	 * @return bool
	 */
	public function is_needed() {
		// Is this site about a person?
		$is_about_person = $this->context->site_represents === 'person' || $this->context->indexable->object_type === 'user';

		if ( $is_about_person ) {
			// Find the ID of the person this page is about.
			$user_id = parent::determine_user_id();

			// Check if that person has defined a spouse.
			$user_spouse = intval( get_user_meta( $user_id, 'wpm-spouse', true ) );

			// It should to be an integer higher than 0.
			if ( \is_int( $user_spouse ) && $user_spouse > 0 ) {
				// We know we need a spouse, so we generate a schema for later use.
				$this->spouse_schema_id  = $this->helpers->schema->id->get_user_schema_id( $user_spouse, $this->context );
				$this->spouse_of_user_id = $user_id;

				add_filter( 'wpseo_schema_person_data', array( $this, 'modify_spouse_of' ), 10, 2 );

				return true;
			}
		}

		return false;
	}

	/**
	 * Determines a User ID for the Person data.
	 *
	 * @return bool|int User ID or false upon return.
	 */
	protected function determine_user_id() {
		$person_id = parent::determine_user_id();

		if ( $person_id ) {
			// Check if that person has defined a spouse.
			$user_spouse = intval( get_user_meta( $person_id, 'wpm-spouse', true ) );

			// It should to be an integer higher than 0.
			if ( \is_int( $user_spouse ) && $user_spouse > 0 ) {
				return $user_spouse;
			}
		}

		return false;
	}

	/**
	 * Overwrite this function and make it straight return data, as spouses do not have images from options.
	 *
	 * @param array  $data      The Person schema.
	 * @param string $schema_id The string used in the `@id` for the schema.
	 *
	 * @return array The Person schema.
	 */
	protected function set_image_from_options( $data, $schema_id ) {
		return $data;
	}

	/**
	 * We add the spouse data object to the user graph piece that has triggered
	 * the generation of this spouse.
	 *
	 * @param array $data    The schema data we have for this person.
	 * @param int   $user_id The current user we're collecting schema data for.
	 */
	public function modify_spouse_of( $data, $user_id ) {
		if ( $user_id === $this->spouse_of_user_id ) {
			$data['spouse'] = array(
				'@id' => $this->spouse_schema_id,
			);
		}

		return $data;
	}
}
