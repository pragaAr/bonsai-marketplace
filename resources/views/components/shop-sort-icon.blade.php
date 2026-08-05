@switch($sort)
  @case('price_asc')
    <x-icons.arrow-down-up class="h-4 w-4" />
    @break
  @case('price_desc')
    <x-icons.arrow-up-down class="h-4 w-4" />
    @break
  @case('name_asc')
    <x-icons.a-z class="h-5 w-5" />
    @break
  @case('name_desc')
    <x-icons.z-a class="h-5 w-5" />
    @break
  @default
    <x-icons.filter class="h-4 w-4" />
@endswitch
