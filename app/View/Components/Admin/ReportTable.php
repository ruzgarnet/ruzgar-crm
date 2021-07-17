<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class ReportTable extends Component
{
    /**
     * Reports
     *
     * @param array $reports
     */
    public $reports = [];

    /**
     * Category Key
     *
     * @param string|null $categoryKey
     */
    public $categoryKey;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(array $reports, $categoryKey = null)
    {
        $this->reports = $reports;
        $this->categoryKey = $categoryKey;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.report-table');
    }
}
