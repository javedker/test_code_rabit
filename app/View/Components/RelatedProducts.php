<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RelatedProducts extends Component
{
    /**
     * Create a new component instance.
     */

    public $related;
    public function __construct($related)
    {
        $this->related =json_decode( html_entity_decode($related),1);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.related-products', ['articles' => $this->related]);
    }
}
