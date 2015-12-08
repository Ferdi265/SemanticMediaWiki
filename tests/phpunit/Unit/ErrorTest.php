<?php

namespace SMW\Tests;

use SMW\Tests\Utils\UtilityFactory;
use SMW\Error;
use SMW\DIWikiPage;
use SMW\DIProperty;

/**
 * @covers \SMW\Error
 * @group semantic-mediawiki
 *
 * @license GNU GPL v2+
 * @since 2.4
 *
 * @author mwjames
 */
class ErrorTest extends \PHPUnit_Framework_TestCase {

	private $semanticDataValidator;

	protected function setUp() {
		parent::setUp();

		$this->semanticDataValidator = UtilityFactory::getInstance()->newValidatorFactory()->newSemanticDataValidator();
	}

	public function testCanConstruct() {

		$subject = $this->getMockBuilder( '\SMW\DIWikiPage' )
			->disableOriginalConstructor()
			->getMock();

		$this->assertInstanceOf(
			'\SMW\Error',
			new Error( $subject )
		);
	}

	public function testErrorContainer() {

		$instance = new Error( DIWikiPage::newFromText( 'Foo' ) );

		$this->assertEquals(
			new DIProperty( '_ERRC' ),
			$instance->getProperty()
		);

		$container = $instance->getContainerFor( new DIProperty( 'Foo' ), 'Some error' );

		$expected = array(
			'propertyCount'  => 2,
			'propertyKeys'   => array( '_ERRP', '_ERRT' ),
		);

		$this->semanticDataValidator->assertThatPropertiesAreSet(
			$expected,
			$container->getSemanticData()
		);
	}

	public function testErrorContainerForNullProperty() {

		$instance = new Error( DIWikiPage::newFromText( 'Foo' ) );

		$this->assertEquals(
			new DIProperty( '_ERRC' ),
			$instance->getProperty()
		);

		$container = $instance->getContainerFor( null, 'Some error' );

		$expected = array(
			'propertyCount'  => 1,
			'propertyKeys'   => array( '_ERRT' ),
		);

		$this->semanticDataValidator->assertThatPropertiesAreSet(
			$expected,
			$container->getSemanticData()
		);
	}

	public function testErrorContainerForInverseProperty() {

		$instance = new Error( DIWikiPage::newFromText( 'Foo' ) );

		$this->assertEquals(
			new DIProperty( '_ERRC' ),
			$instance->getProperty()
		);

		$container = $instance->getContainerFor( new DIProperty( 'Foo', true ), array( 'Some error' ) );

		$expected = array(
			'propertyCount'  => 2,
			'propertyKeys'   => array( '_ERRP', '_ERRT' ),
		);

		$this->semanticDataValidator->assertThatPropertiesAreSet(
			$expected,
			$container->getSemanticData()
		);
	}

}
