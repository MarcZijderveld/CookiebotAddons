<?php

namespace cookiebot_addons_framework\lib\buffer;

class Buffer_Output implements Buffer_Output_Interface {

	/**
	 * Hook tag names
	 *
	 * @var array
	 *
	 * @since 1.2.0
	 */
	private $tags = array();

	/**
	 * @param $tag_name         string      Hook name
	 * @param $priority         integer     Hook priority
	 * @param array $keywords array       List of words to search for in the script
	 *
	 * @since 1.2.0
	 */
	public function add_tag( $tag_name, $priority, $keywords = array(), $use_cache = true) {
		$tag       = new Buffer_Output_Tag( $tag_name, $priority, $keywords, $use_cache );
		$unique_id = $tag->tag . '_' . $tag->priority;

		/**
		 * If tag_name and priority exists
		 * Then merge the keywords
		 */
		if ( in_array( $unique_id, $this->tags ) ) {
			$tag->merge_keywords( $keywords );
		}

		$this->tags[ $unique_id ] = $tag;
	}

	/**
	 * Process every tag
	 *
	 * @since 1.2.0
	 */
	public function run_actions() {
		foreach ( $this->tags as $tag ) {
			add_action( $tag->tag, array( $tag, 'cookiebot_start_buffer' ), $tag->priority - 1 );
			add_action( $tag->tag, array( $tag, 'cookiebot_stop_buffer' ), $tag->priority + 1 );
		}
	}

	/**
	 * Returns true if tags has more than 0 item
	 *
	 * @return bool
	 *
	 * @since 1.2.0
	 */
	public function has_action() {
		return ( count( $this->tags ) > 0 ) ? true : false;
	}
}