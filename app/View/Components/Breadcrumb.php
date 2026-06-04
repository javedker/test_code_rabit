<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component
{

    public $title;
    public $isTitle;
    public $current;
    public $background;
    public $titleUrl;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $current = '', $titleUrl = '', $background = 'img/banner/breadcrumb-01.jpg')
    {
        $this->title = $title;
        $this->current = $current;
        $this->background = $background;
        $this->titleUrl = $titleUrl;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.breadcrumb');
    }
}
