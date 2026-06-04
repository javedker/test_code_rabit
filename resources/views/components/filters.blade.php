{{-- resources/views/partials/filters.blade.php --}}
<div class="product-sidebar">

    {{-- Brands --}}
    <div class="product-sidebar__widget mb-30">
        <h4 class="product-sidebar__title ">Brands</h4>
        <div id="filter-brands">
            @if (isset($filters['brands']))
                @foreach ($filters['brands'] as ['name' => $name, 'id' => $id])
                    <div class="form-check">
                        <input class="form-check-input filter-brands" type="checkbox" value="{{ e($name) }}"
                            id="brand-{{ e($id) }}">
                        <label class="form-check-label" for="brand-{{ e($id) }}">
                            {{ e($name) }}
                        </label>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Types --}}
    <div class="product-sidebar__widget mb-30">
        <h4 class="product-sidebar__title ">Types</h4>
        <div id="filter-types" class="custom-scrollbox">
            @if (isset($filters['types']))
                @foreach ($filters['types'] as ['type' => $type])
                    <div class="form-check">
                        <input class="form-check-input filter-types" type="checkbox" value="{{ e($type) }}"
                            id="type-{{ e($type) }}">
                        <label class="form-check-label" for="type-{{ e($type) }}">
                            {{ e($type) }}
                        </label>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Categories --}}
    <div class="product-sidebar__widget mb-30">
        <h4 class="product-sidebar__title ">Categories</h4>
        <div id="filter-category" class="custom-scrollbox">
            @if (isset($filters['categories']))
                @foreach ($filters['categories'] as ['category' => $category])
                    <div class="form-check">
                        <input class="form-check-input filter-category" type="checkbox" value="{{ e($category) }}"
                            id="category-{{ e($category) }}">
                        <label class="form-check-label" for="category-{{ e($category) }}">
                            {{ e($category) }}
                        </label>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Others --}}
    <div class="product-sidebar__widget mb-30">
        <h4 class="product-sidebar__title ">Others</h4>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="in-stock">
            <label class="form-check-label" for="in-stock">In Stock</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="special-price">
            <label class="form-check-label text-danger" for="special-price">Special Price</label>
        </div>
    </div>

</div>