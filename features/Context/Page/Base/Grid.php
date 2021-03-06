<?php

namespace Context\Page\Base;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Element\NodeElement;
use Context\Spin\TimeoutException;

/**
 * Page object for datagrid generated by the OroGridBundle
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Grid extends Index
{
    const FILTER_CONTAINS         = 1;
    const FILTER_DOES_NOT_CONTAIN = 2;
    const FILTER_IS_EQUAL_TO      = 3;
    const FILTER_STARTS_WITH      = 4;
    const FILTER_ENDS_WITH        = 5;
    const FILTER_IS_EMPTY         = 'empty';
    const FILTER_IN_LIST          = 'in';

    protected $filterDecorators = [
        'tree' => [
            'Pim\Behat\Decorator\Tree\JsTreeDecorator',
            'Pim\Behat\Decorator\Grid\Filter\CategoryDecorator',
        ],
        'boolean' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\BooleanDecorator',
        ],
        'choice' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\ChoiceDecorator',
        ],
        'date' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\DateDecorator'
        ],
        'datetime' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\DateDecorator'
        ],
        'metric' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\MetricDecorator',
        ],
        'multichoice' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\ChoiceDecorator',
        ],
        'number' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\NumberDecorator',
        ],
        'price' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\PriceDecorator',
        ],
        'product_completeness' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\ChoiceDecorator',
        ],
        'product_scope' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\ChoiceDecorator',
        ],
        'select2-choice' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\Select2ChoiceDecorator',
        ],
        'select2-rest-choice' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\Select2ChoiceDecorator',
        ],
        'string' => [
            'Pim\Behat\Decorator\Grid\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Grid\Filter\StringDecorator',
        ],
        'akeneo-product-enabled-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\Select2Decorator',
        ],
        'akeneo-product-family-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\Select2Decorator',
        ],
        'akeneo-product-completeness-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\Select2Decorator',
        ],
        'akeneo-product-updated-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\UpdatedDecorator',
        ],
        'akeneo-attribute-identifier-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\IdentifierDecorator',
        ],
        'akeneo-attribute-boolean-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\BooleanDecorator',
        ],
        'akeneo-attribute-metric-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\MetricDecorator',
        ],
        'akeneo-attribute-number-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\NumberDecorator',
        ],
        'akeneo-attribute-string-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\StringDecorator',
        ],
        'akeneo-attribute-date-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\DateDecorator'
        ],
        'akeneo-attribute-price-collection-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\PriceDecorator',
        ],
        'akeneo-attribute-select-reference-data-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\Select2Decorator',
        ],
        'akeneo-attribute-select-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\Select2Decorator',
        ],
        'akeneo-attribute-media-filter' => [
            'Pim\Behat\Decorator\Export\Filter\BaseDecorator',
            'Pim\Behat\Decorator\Export\Filter\MediaDecorator',
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct($session, $pageFactory, $parameters = [])
    {
        parent::__construct($session, $pageFactory, $parameters);

        $this->elements = array_merge(
            [
                'Grid container'        => ['css' => '.grid-container'],
                'Grid'                  => ['css' => 'table.grid'],
                'Grid content'          => ['css' => 'table.grid tbody'],
                'Filters'               => ['css' => '.filter-box, .filter-wrapper'],
                'Grid toolbar'          => ['css' => 'div.grid-toolbar'],
                'Manage filters'        => ['css' => 'div.filter-list'],
                'Configure columns'     => ['css' => 'a:contains("Columns")'],
                'View selector'         => ['css' => '.grid-view-selector'],
                'Views list'            => ['css' => '.ui-multiselect-menu.highlight-hover'],
                'Select2 results'       => ['css' => '#select2-drop .select2-results'],
                'Main context selector' => [
                    'css'        => '#container',
                    'decorators' => [
                        'Pim\Behat\Decorator\ContextSwitcherDecorator'
                    ]
                ]
            ],
            $this->elements
        );
    }

    /**
     * Returns the currently visible grid, if there is one
     *
     * @return NodeElement
     */
    public function getGrid()
    {
        $body = $this->getElement('Body');
        $container = $this->getElement('Container');

        return $this->spin(
            function () use ($body, $container) {
                $modal = $body->find('css', $this->elements['Dialog']['css']);
                if (null !== $modal && $modal->isVisible()) {
                    return $modal->find('css', $this->elements['Grid']['css']);
                }

                return $container->find('css', $this->elements['Grid']['css']);
            },
            'No visible grid found'
        );
    }

    /**
     * Returns the grid body
     *
     * @return NodeElement|null
     */
    public function getGridContent()
    {
        return $this->getGrid()->find('css', 'tbody');
    }

    /**
     * Get a row from the grid containing the value asked
     *
     * @param string $value
     *
     * @return NodeElement
     */
    public function getRow($value)
    {
        $value = str_replace('"', '', $value);

        $gridRow = $this->spin(function () use ($value) {
            return $this->getGridContent()->find('css', sprintf('tr td:contains("%s")', $value));
        }, sprintf('Couldn\'t find a row for value "%s"', $value));

        return $gridRow->getParent();
    }

    /**
     * Check if the grid contains a row with the specified value
     *
     * @param string $value
     *
     * @return bool
     */
    public function hasRow($value)
    {
        $value = str_replace('"', '', $value);

        return null !== $this->getGridContent()->find('css', sprintf('tr td:contains("%s")', $value));
    }

    /**
     * @param string $element
     * @param string $actionName
     *
     * @throws \InvalidArgumentException
     */
    public function clickOnAction($element, $actionName)
    {
        $action = $this->findAction($element, $actionName);

        if (!$action) {
            throw new \InvalidArgumentException(
                sprintf('Could not find action "%s".', $actionName)
            );
        }

        $action->click();
    }

    /**
     * @param string $element
     * @param string $actionName
     *
     * @return NodeElement|null
     */
    public function findAction($element, $actionName)
    {
        $rowElement = $this->getRow($element);
        $action     = $rowElement->find('css', sprintf('a.action[title="%s"]', $actionName));

        return $action;
    }

    /**
     * @param array  $expectedOptions
     * @param string $filterName
     *
     * @throws \InvalidArgumentException
     */
    public function checkOptionInFilter(array $expectedOptions, $filterName)
    {
        $filter = $this->getFilter($filterName);

        $filter->open();
        $options = $filter->getOptions();

        if ($options != $expectedOptions) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Expecting filter "%s" to contain the options "%s", got "%s"',
                    $filterName,
                    implode(', ', $expectedOptions),
                    implode(', ', $options)
                )
            );
        }
    }

    /**
     * @param string      $filterName The name of the filter
     * @param bool|string $operator   The operator
     * @param string      $value      The value to filter by
     *
     * @throws \InvalidArgumentException
     */
    public function filterBy($filterName, $operator, $value)
    {
        $filter = $this->getFilter($filterName);

        $filter->open();
        $filter->filter($operator, $value);
    }

    /**
     * Get grid filter from label name
     *
     * @param string $filterName
     *
     * @return NodeElement
     */
    public function getFilter($filterName)
    {
        // We find the node element
        $filter = $this->spin(function () use ($filterName) {
            $filter = $this->getElement('Body')->find('css', sprintf('.filter-item[data-name="%s"]', $filterName));

            return $filter;
        }, sprintf('Couldn\'t find a filter with name "%s"', $filterName));

        // We decorate it
        $filterType = $filter->getAttribute('data-type');
        if (isset($this->filterDecorators[$filterType])) {
            $filter = $this->decorate($filter, $this->filterDecorators[$filterType]);
        }

        return $filter;
    }

    /**
     * Count all rows in the grid
     *
     * @return int
     */
    public function countRows()
    {
        try {
            return count($this->getRows());
        } catch (\InvalidArgumentException $e) {
            return 0;
        }
    }

    /**
     * Indicate if the grid is empty (i.e. has the "No records found" div)
     *
     * @return bool
     */
    public function isGridEmpty()
    {
        $container = $this->getElement('Grid container');
        $noDataDiv = $this->spin(function () use ($container) {
            return $container->find('css', '.no-data');
        }, '"No data" div not found');

        return $noDataDiv->isVisible();
    }

    /**
     * Get toolbar count
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public function getToolbarCount()
    {
        $pagination = $this
            ->getElement('Grid toolbar')
            ->find('css', 'div label.dib:contains("record")');

        /**
         * If pagination not found or is empty, it actually count rows
         *
         * TODO Remove this. We have to find another toolbar count.
         * If there is no toolbar count, this method
         * should even not be called or should raise a not found exception.
         */
        if (!$pagination || !$pagination->getText() || false !== strstr($pagination->getText(), 'null')) {
            return $this->countRows();
        }

        if (preg_match('/(?P<count>[0-9][0-9 ]*) records?$/', $pagination->getText(), $matches)) {
            return (int) preg_replace('/[^0-9]/', '', $matches['count']);
        } else {
            throw new \InvalidArgumentException('Impossible to get count of datagrid records');
        }
    }

    /**
     * Get the text in the specified column of the specified row
     *
     * @param string $column
     * @param string $row
     *
     * @return string
     */
    public function getColumnValue($column, $row)
    {
        return $this->getColumnNode($column, $row)->getText();
    }

    /**
     * Get the node in the specified column of the specified row
     *
     * @param string $column
     * @param string $row
     *
     * @return NodeElement
     */
    public function getColumnNode($column, $row)
    {
        return $this->getRowCell(
            $this->getRow($row),
            $this->getColumnPosition($column, true, true)
        );
    }

    /**
     * Get an array of values in the specified column
     *
     * @param string $columnName
     *
     * @return array
     */
    public function getValuesInColumn($columnName)
    {
        $position = $this->getColumnPosition($columnName, true, true);
        $rows     = $this->getRows();
        $values   = [];

        foreach ($rows as $row) {
            $cell = $this->getRowCell($row, $position);
            if ($span = $cell->find('css', 'span')) {
                $values[] = (string) (strpos($span->getAttribute('class'), 'success') !== false);
            } else {
                $values[] = $cell->getText();
            }
        }

        return $values;
    }

    /**
     * Get an image element inside a grid cell
     *
     * @param string $column
     * @param string $row
     *
     * @throws \InvalidArgumentException
     *
     * @return NodeElement
     */
    public function getCellImage($column, $row)
    {
        $cell  = $this->getColumnNode($column, $row);
        $image = $cell->find('css', 'img');
        if (null === $image) {
            throw new \InvalidArgumentException(
                sprintf('Column "%s" of row "%s" contains no image.', $column, $row)
            );
        }

        return $image;
    }

    /**
     * @param string $columnName
     * @param bool   $withHidden
     * @param bool   $withActions
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public function getColumnPosition($columnName, $withHidden, $withActions)
    {
        $headers = $this->getColumnHeaders($withHidden, $withActions);
        foreach ($headers as $position => $header) {
            if (strtolower($columnName) === strtolower($header->getText())) {
                return $position;
            }
        }

        throw new \InvalidArgumentException(
            sprintf('Could not find a column header "%s"', $columnName)
        );
    }

    /**
     * Sort rows by a column in the specified order
     *
     * @param string $columnName
     * @param string $order
     */
    public function sortBy($columnName, $order = 'ascending')
    {
        $sorter = $this->getColumnSorter($columnName);
        if ($sorter->getParent()->getAttribute('class') !== strtolower($order)) {
            $sorter->click();
        }
    }

    /**
     * Predicate to know if a column is sorted and ordered as we want
     *
     * @param string $columnName
     * @param string $order
     *
     * @return bool
     */
    public function isSortedAndOrdered($columnName, $order)
    {
        $order = strtolower($order);
        if ($this->getColumnHeader($columnName)->getAttribute('class') !== $order) {
            return false;
        }

        $values = $this->getValuesInColumn($columnName);
        $values = $this->formatColumnValues($values);

        $sortedValues = $values;

        if ($order === 'ascending') {
            sort($sortedValues, SORT_NATURAL | SORT_FLAG_CASE);
        } else {
            rsort($sortedValues, SORT_NATURAL | SORT_FLAG_CASE);
        }

        return $sortedValues === $values;
    }

    /**
     * Count columns in datagrid
     *
     * @return int
     */
    public function countColumns()
    {
        return count($this->getColumnHeaders(false, false));
    }

    /**
     * Get column sorter
     *
     * @param string $columnName
     *
     * @return NodeElement
     */
    public function getColumnSorter($columnName)
    {
        $header = $this->getColumnHeader($columnName);

        return $this->spin(
            function () use ($header) {
                return $header->find('css', 'a');
            },
            sprintf('Column %s is not sortable', $columnName)
        );
    }

    /**
     * @param string $filterName
     *
     * @return bool
     */
    public function isFilterAvailable($filterName)
    {
        $this->clickFiltersList();
        $filterElement = $this->getElement('Manage filters')->find('css', sprintf('input[value="%s"]', $filterName));

        return null !== $filterElement;
    }

    /**
     * Show a filter from the management list
     *
     * @param string $filterName
     */
    public function showFilter($filterName)
    {
        $this->spin(function () {
            return $this->getElement('Body')->find('css', '.filter-box, .filter-wrapper');
        }, 'The filter box is not loaded');

        $filter = $this->getElement('Body')->find('css', sprintf('.filter-item[data-name="%s"]', $filterName));
        if (null === $filter || !$filter->isVisible()) {
            $this->clickOnFilterToManage($filterName);
        }
    }

    /**
     * Hide a filter from the management list
     *
     * @param string $filterName
     */
    public function hideFilter($filterName)
    {
        $filter = $this->getFilter($filterName);
        if ($filter->isVisible()) {
            $filter->remove();
        }
    }

    /**
     * Expand filter
     *
     * @param string $filterName
     */
    public function expandFilter($filterName)
    {
        $filter = $this->getFilter($filterName);
        $filter->expand();
    }

    /**
     * Click on the reset button of the datagrid toolbar
     */
    public function clickOnResetButton()
    {
        $resetBtn = $this->spin(function () {
            return $this
                ->getElement('Grid toolbar')
                ->find('css', sprintf('a:contains("%s")', 'Reset'));
        }, 'Reset button not found');

        $resetBtn->click();
    }

    /**
     * Click on the refresh button of the datagrid toolbar
     */
    public function clickOnRefreshButton()
    {
        $refreshBtn = $this->spin(function () {
            return $this
                ->getElement('Grid toolbar')
                ->find('css', sprintf('a:contains("%s")', 'Refresh'));
        }, 'Refresh button not found');

        $refreshBtn->click();
    }

    /**
     * Click on a filter in filter management list
     *
     * @param string $filterName
     */
    protected function clickOnFilterToManage($filterName)
    {
        $manageFilters = $this->getElement('Manage filters');
        if (!$manageFilters->isVisible()) {
            $this->clickFiltersList();
        }

        $this->spin(function () use ($manageFilters, $filterName) {
            $filterElement = $manageFilters->find('css', sprintf('input[value="%s"]', $filterName));

            if (null !== $filterElement && $filterElement->isVisible()) {
                $filterElement->click();

                return true;
            }

            if (null !== $searchField = $manageFilters->find('css', 'input[type="search"]')) {
                $searchField->setValue($filterName);
            }

            if (null !== $filterElement && $filterElement->isVisible()) {
                $filterElement->click();

                return true;
            }

            return false;
        }, sprintf('Impossible to activate filter "%s"', $filterName));
    }

    /**
     * Open/close filters list
     */
    protected function clickFiltersList()
    {
        $filterList = $this->spin(function () {
            return $this
                ->getElement('Filters')
                ->find('css', '#add-filter-button');
        }, 'Impossible to find filter list');

        $filterList->click();
    }

    /**
     * Select a row
     *
     * @param string $value
     * @param bool   $check
     */
    public function selectRow($value, $check = true)
    {
        $this->spin(function () use ($value, $check) {
            $row      = $this->getRow($value);
            $checkbox = $row->find('css', 'input[type="checkbox"]');

            if (null === $checkbox || !$checkbox->isVisible()) {
                return false;
            }

            if (true === $check) {
                $checkbox->check();

                return $checkbox->isChecked();
            }

            $checkbox->uncheck();

            return !$checkbox->isChecked();
        }, sprintf('Couldn\'t find a checkbox for row "%s"', $value));
    }

    /**
     * @param NodeElement $row
     * @param int         $position
     *
     * @throws \InvalidArgumentException
     *
     * @return NodeElement
     */
    protected function getRowCell($row, $position)
    {
        // $row->findAll('css', 'td') will not work in the case of nested table (like proposals changes)
        // because we only need to find the direct children cells
        $cells = $row->findAll('xpath', './td');
        if (!isset($cells[$position])) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Trying to access cell %d of a row which has %d cell(s).',
                    $position + 1,
                    count($cells)
                )
            );
        }

        return $cells[$position];
    }

    /**
     * Get column headers
     *
     * @param bool $withHidden
     * @param bool $withActions
     *
     * @return NodeElement[]
     */
    protected function getColumnHeaders($withHidden = false, $withActions = true)
    {
        $head    = $this->getGrid()->find('css', 'thead');
        $headers = $head->findAll('css', 'th');

        if (!$withActions) {
            $headers = array_filter($headers, function ($header) {
                return false === strpos($header->getAttribute('class'), 'action-column') &&
                    false === strpos($header->getAttribute('class'), 'select-all-header-cell') &&
                    null === $header->find('css', 'input[type="checkbox"]');
            });
        }

        if ($withHidden) {
            return $headers;
        }

        $visibleHeaders = array_filter($headers, function ($header) {
            return $header->isVisible();
        });
        $visibleHeaders = array_values($visibleHeaders);

        return $visibleHeaders;
    }

    /**
     * Get column header
     *
     * @param string $columnName
     *
     * @throws \InvalidArgumentException
     *
     * @return NodeElement
     */
    public function getColumnHeader($columnName)
    {
        $headers = $this->getColumnHeaders(true);
        foreach ($headers as $header) {
            if (strtolower($columnName) === strtolower($header->getText())) {
                return $header;
            }
        }

        throw new \InvalidArgumentException(
            sprintf('Could not find column header "%s"', $columnName)
        );
    }

    /**
     * Get rows
     *
     * @return NodeElement[]
     */
    protected function getRows()
    {
        return $this->spin(function () {
            return $this->getGridContent()->findAll('xpath', '/tr');
        }, 'Cannot get the grid rows.');
    }

    /**
     * Open the column configuration popin
     */
    public function openColumnsPopin()
    {
        $this->getElement('Configure columns')->click();
    }

    /**
     * Hide a grid column
     *
     * @param string $column
     */
    public function hideColumn($column)
    {
        return $this->getElement('Configuration Popin')->hideColumn($column);
    }

    /**
     * Move a grid column
     *
     * @param string $source
     * @param string $target
     */
    public function moveColumn($source, $target)
    {
        return $this->getElement('Configuration Popin')->moveColumn($source, $target);
    }

    /**
     * Select all rows
     */
    public function selectAll()
    {
        $selector = $this->getDropdownSelector();

        $allBtn = $this->spin(function () use ($selector) {
            return $selector->find('css', 'button:contains("All")');
        }, '"All" button on dropdown row selector not found');

        $allBtn->click();
    }

    /**
     * Select all visible rows
     */
    public function selectAllVisible()
    {
        $this->clickOnDropdownSelector('All visible');
    }

    /**
     * Unselect all rows
     */
    public function selectNone()
    {
        $this->clickOnDropdownSelector('None');
    }

    /**
     * Get the dropdown row selector
     *
     * @return NodeElement
     */
    protected function getDropdownSelector()
    {
        return $this->spin(function () {
            return $this->getElement('Grid')->find('css', 'th .btn-group');
        }, 'Grid dropdown row selector not found');
    }

    /**
     * Click on an item of the dropdown selector
     *
     * @param string $item
     */
    protected function clickOnDropdownSelector($item)
    {
        $selector = $this->getDropdownSelector();

        $dropdown = $this->spin(function () use ($selector) {
            return $selector->find('css', 'button.dropdown-toggle');
        }, 'Dropdown row selector not found');

        $dropdown->click();

        $listItem = $this->spin(function () use ($dropdown, $item) {
            return $dropdown->getParent()->find('css', sprintf('li:contains("%s") a', $item));
        }, sprintf('Item "%s" of dropdown row selector not found', $item));

        $listItem->click();
    }

    /**
     * Format column values before sorting
     *
     * @param string[] $values
     *
     * @return string[]
     */
    protected function formatColumnValues(array $values)
    {
        $cleanValues = [];

        foreach ($values as $key => $value) {
            $clean = trim($value);

            if (false !== $timestamp = strtotime($clean)) {
                $clean = date('Ymd-H:i:s', $timestamp);
            }

            $cleanValues[$key] = $clean;
        }

        return $cleanValues;
    }
}
