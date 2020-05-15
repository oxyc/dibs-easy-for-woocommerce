<?php // phpcs:ignore
/**
 *
 * Undocumented class
 *
 * @package category
 */

/**
 * Undocumented class
 */
class Simple_Test extends AKrokedil_Unit_Test_Case {

	/**
	 * Dummy option
	 *
	 * @var string[]
	 */
	private $options = array(
		'kro_option_1' => 'kro_option_value_1',
		'kro_option_2' => 'kro_option_value_2',
		'kro_option_3' => 'kro_option_value_3',
	);

	/**
	 * Test options
	 */
	public function test_options() {
		foreach ( $this->options as $option_name => $option_value ) {
			$this->assertSame( $option_value, get_option( $option_name ) );
		}
	}

	/**
	 *
	 */
	public function test_options_size() {

	}

	/**
	 * Prepare stuff before each test.
	 */
	public function create() {
		foreach ( $this->options as $option_name => $option_value ) {
			add_option( $option_name, $option_value );
		}
	}

	/**
	 * Update logic
	 */
	public function update() {
		// TODO: Implement update() method.
	}

	/**
	 * View logic
	 */
	public function view() {
		// TODO: Implement view() method.
	}

	/**
	 * Removes option from db after each test.
	 */
	public function delete() {
		$options = array_keys( $this->options );
		foreach ( $options as $option ) {
			delete_option( $option );
		}
	}

	public function test_niklas() {
		$test1 = 'a';
		$test2 = 'a';
		$this->assertTrue( $test1 === $test2 );
	}
}
