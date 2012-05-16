<?php 

namespace phpOlap\Layout\Table;

use phpOlap\Xmla\Metadata\ResultSetTabular;

class HtmlTabularTableLayout 
{
	
	protected $resultSet;

    /**
     * Constructor.
     *
     * @param ResultSetTabular $resultSet The resultSet object
     *
     */	
	public function __construct (ResultSetTabular $resultSet)
	{
		$this->resultSet = $resultSet;
	}
	
    /**
     * generate the layout
     *
     * @return String Layout
     *
     */
	public function generate()
	{
		$thead = '<thead><tr><th>'.implode('</th><th>', $this->resultSet->getColumns()).'</th></tr></thead>';
		$rows = array();
		foreach ($this->resultSet->getRows() as $row)
		{
			$rows[] = '<tr><td>'.implode('</td><td>', $row).'</td></tr>';
		}

		return sprintf('<table>%s<tbody>%s</tbody></table>', $thead, implode('', $rows));
	}

}