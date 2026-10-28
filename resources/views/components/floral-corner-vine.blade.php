@props(['position' => 'tl'])

@if ($position === 'tl')
    <div class="fe-realistic-floral-corner fe-floral-tl absolute -top-1 -left-1 z-20 select-none cursor-pointer group"
         title="Sentuh untuk efek kelopak bunga"
         data-floral-corner="tl">
        <img 
            src="{{ asset('images/templates/floral-elegance/floral-corner-tl.png') }}" 
            alt="Botanical Flower Ornament"
            loading="lazy"
            class="fe-flower-img w-[88px] sm:w-[110px] md:w-[124px] h-auto drop-shadow-md transition-transform duration-300 pointer-events-auto"
        >
    </div>
@else
    <div class="fe-realistic-floral-corner fe-floral-br absolute -bottom-1 -right-1 z-20 select-none cursor-pointer group"
         title="Sentuh untuk efek kelopak bunga"
         data-floral-corner="br">
        <img 
            src="{{ asset('images/templates/floral-elegance/floral-corner-br.png') }}" 
            alt="Botanical Flower Ornament"
            loading="lazy"
            class="fe-flower-img w-[88px] sm:w-[110px] md:w-[124px] h-auto drop-shadow-md transition-transform duration-300 pointer-events-auto"
        >
    </div>
@endif
