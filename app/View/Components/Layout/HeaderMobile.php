<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HeaderMobile extends Component
{
    /**
     * Create a new component instance.
     */
    public $offerStatus=false;
    public $offerTitle="";
    public $offerBadge="";
   public function __construct()
   {
       $offer=getOfferSettings();
       $this->offerStatus=isset($offer['status'])?$offer['status']:false;
       $this->offerTitle=isset($offer['title'])?$offer['title']:"";
       $this->offerBadge=isset($offer['badge'])?$offer['badge']:"";
   }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.header-mobile');
    }
}
