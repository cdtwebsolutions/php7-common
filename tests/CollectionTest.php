<?php declare(strict_types=1);
namespace Noname\Common;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
	protected $collection;

	protected $basicCollection = [
		'key1' => 'val1',
		'key2' => 'val2',
		'key3' => 'val3',
		'key4' => 'val4',
	];

	protected function setUp()
	{
		$this->collection = new Collection;
	}

	///////////////////////////////////
	// Data providers

	public function collectionItemsProvider()
	{
		return [
			['key1', 'val1'],
			['key2', 'val2'],
			['key3', 'val3'],
			['key4', 'val4'],
		];
	}

	public function isCollectionItemsProvider()
	{
		// Operators: null, =, ==, ===, >, >=, <, <=, <>
		return [
			['key1', 0, 0, null],
			['key1', 0, 0, '='],
			['key2', 0, 0, '=='],
			['key3', 0, 0, '==='],
			['key4', 0, 1, '<'],
			['key5', 0, 1, '<='],
			['key6', 1, 0, '>'],
			['key7', 1, 0, '>='],
			['key8', 0, 1, '!='],
			['key9', 0, 1, '<>'],
		];
	}

	public function isNotCollectionItemsProvider()
	{
		// Operators: null, =, ==, ===, >, >=, <, <=, <>
		return [
			['key1', 0, 1, null],
			['key1', 0, 1, '='],
			['key2', 0, 1, '=='],
			['key3', 0, 1, '==='],
			['key4', 1, 0, '<'],
			['key5', 1, 0, '<='],
			['key6', 0, 1, '>'],
			['key7', 0, 1, '>='],
			['key8', 0, 0, '!='],
			['key9', 0, 0, '<>'],
		];
	}

	///////////////////////////////////
	// Tests

	/**
	 * @covers Collection::count
	 */
	public function testEmpty()
	{
		$this->assertTrue($this->collection->count() == 0);
	}

	/**
	 * @covers Collection::set, Collection:get
	 * @dataProvider collectionItemsProvider
	 */
	public function testSet($key, $value)
	{
		$this->collection->set($key, $value);
		$this->assertEquals($value, $this->collection->get($key));
	}

	/**
	 * @covers Collection::has
	 * @dataProvider collectionItemsProvider
	 */
	public function testHas($key, $value)
	{
		$this->collection->set($key, $value);
		$this->assertTrue($this->collection->has($key));
	}

	/**
	 * @covers Collection::pluck
	 * @dataProvider collectionItemsProvider
	 */
	public function testPluck($key, $value)
	{
		$this->collection->set($key, $value);
		$plucked = $this->collection->pluck($key);
		$this->assertEquals($plucked, $value);
		$this->assertFalse($this->collection->has($key));
	}

	/**
	 * @covers Collection::all, Collection::toArray, Collection::keys, Collection::values
	 * @dataProvider collectionItemsProvider
	 */
	public function testToArray($key, $value)
	{
		$this->collection->set($key, $value);
		$arr = $this->collection->toArray();
		$keys = $this->collection->keys();
		$values = $this->collection->values();

		$this->assertTrue(is_array($arr));
		$this->assertEquals($arr[$key], $value);
		$this->assertEquals($keys, array_keys($arr));
		$this->assertEquals($values, array_values($arr));
	}

	/**
	 * @covers Collection::is
	 * @dataProvider isCollectionItemsProvider
	 */
	public function testIs($key, $value, $compare, $operator)
	{
		$this->collection->set($key, $value);
		$this->assertTrue($this->collection->is($key, $compare, $operator));
	}

	/**
	 * @covers Collection::is
	 * @dataProvider isNotCollectionItemsProvider
	 */
	public function testIsNot($key, $value, $compare, $operator)
	{
		$this->collection->set($key, $value);
		$this->assertFalse($this->collection->is($key, $compare, $operator));
	}

	/**
	 * @covers Collection::delete, Collection::count
	 * @dataProvider collectionItemsProvider
	 */
	public function testDelete($key, $value)
	{
		$this->collection->set($key, $value);
		$this->assertTrue($this->collection->count() == 1);
		$this->collection->delete($key);
		$this->assertFalse($this->collection->get($key, false));
	}

	/**
	 * @covers Collection::destroy, Collection::count
	 * @dataProvider collectionItemsProvider
	 */
	public function testDestroy($key, $value)
	{
		$this->collection->set($key, $value);
		$this->assertTrue($this->collection->count() == 1);
		$this->collection->destroy();
		$this->assertTrue($this->collection->count() == 0);
	}

	/**
	 * @covers Collection::getIterator
	 * @dataProvider collectionItemsProvider
	 */
	public function testGetIterator($key, $value)
	{
		$this->collection->set($key, $value);
		$this->assertEquals(iterator_to_array($this->collection->getIterator()), $this->collection->toArray());
	}
}