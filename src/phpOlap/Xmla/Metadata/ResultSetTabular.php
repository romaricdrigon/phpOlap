<?php 

/*
* This file is part of phpOlap.
*
* (c) Julien Jacottet <jjacottet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace phpOlap\Xmla\Metadata;

use phpOlap\Xmla\Connection\ConnectionInterface;
use phpOlap\Xmla\Metadata\MetadataBase;
use phpOlap\Xmla\Metadata\CellAxis;
use phpOlap\Xmla\Metadata\CellData;
use phpOlap\Metadata\ResultSetInterface;
use phpOlap\Xmla\Metadata\MetadataException;

/**
*	ResultSetTabular
*
*	@package Xmla
*	@subpackage Metadata
*  	@author Riad Benguella <benguella@gmail.com>
*/

class ResultSetTabular
{
	
	protected $rows;
	protected $columns;
	protected $ignore_first_row = false;

	/**
     * Constructor.
     *
     * @param Boolean $ignore_first_row
     *
     */	
	public function __construct($ignore_first_row = false)
	{
		$this->ignore_first_row = $ignore_first_row;
	}

    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     *
     */	
	public function hydrate(\DOMNode $node)
	{
		$this->cubeName = MetadataBase::getPropertyFromNode($node, 'CubeName');

		$this->rows    = $this->hydrateRows($node);
		$this->columns = $this->hydrateColumns($node);
	}

	/**
     * Get Rows
     *
     * @return Array collection
     *
     */
    protected  function hydrateRows(\DOMNode $node)
	{
		$result = array();
		$rows = $node->getElementsByTagName('row');
		$first = true;
		foreach ($rows as $row) 
		{
			if ($first)
			{
				$first = false;
				if ($this->ignore_first_row)
				{
					continue;
				}
			}

			$element = array();
			foreach ($row->childNodes as $child)
			{
				if ($child->nodeType === XML_ELEMENT_NODE)
				{
					$element[$child->nodeName] = $child->nodeValue;
				}
			}
			$result[] = $element;
		}

		return $result;
	}

	/**
     * Get Columns
     *
     * @return Array collection
     *
     */
	protected function hydrateColumns(\DOMNode $node)
	{
		$columns = array();

		$complexType = $node->getElementsByTagName('complexType');
		foreach ($complexType as $elm)
		{
			if ($elm->getAttribute('name') === 'row')
			{
				$sequences = $elm->getElementsByTagName('sequence');
				foreach ($sequences as $sequence)
				{
					foreach ($sequence->childNodes as $child)
					{
						if ($child->nodeType === XML_ELEMENT_NODE)
						{
							$columns[$child->getAttribute('name')] = $child->getAttribute('sql:field');
						}
					}
					break;
				}
				break;
			}
		}

		return $columns;
	}

	public function getRows()
	{
		return $this->rows;
	}

	public function getColumns()
	{
		return $this->columns;
	}
}