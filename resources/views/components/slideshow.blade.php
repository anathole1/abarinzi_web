@props([
    'slides' => [] // Expects a collection of HeroSlide models or array of slide data
])

@if(count($slides) > 0)
<section class="relative h-screen overflow-hidden slideshow-container">
    @foreach($slides as $index => $slide)
    <div
      class="slide-item absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0 pointer-events-none"
      data-slide-index="{{ $index }}"
      style="{{ $index === 0 ? 'opacity: 1; pointer-events: auto;' : '' }}"
    >
      <div class="absolute inset-0 bg-black opacity-50 z-0"></div>
      <img
  src="{{ $slide->image_url }}"
  alt="{{ $slide->title ?? 'Slide Image' }}"
  class="absolute inset-0 w-full h-full object-cover z-[-1]"
/>
   
      <div class="absolute inset-0 flex items-center justify-center z-10">
        <div class="text-center px-4 sm:px-6 lg:px-8 max-w-4xl">
          <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl md:text-6xl mb-6 slide-title">
            {{ $slide->title ?? ($slide['title'] ?? 'Slide Title') }}
          </h1>
          <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto slide-description">
            {{ $slide->description ?? ($slide['description'] ?? 'Slide description.') }}
          </p>
          <div class="flex flex-col sm:flex-row justify-center gap-4">
            @if($slide->cta1_text && $slide->cta1_link)
            <a href="{{ $slide->cta1_link }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-md transition-colors duration-200 transform hover:scale-105">
              {{ $slide->cta1_text }}
            </a>
            @endif
            @if($slide->cta2_text && $slide->cta2_link)
            <a href="{{ $slide->cta2_link }}" class="bg-transparent hover:bg-white/10 text-white border border-white font-medium py-3 px-6 rounded-md transition-colors duration-200 transform hover:scale-105">
              {{ $slide->cta2_text }}
            </a>
            @endif
          </div>
        </div>
      </div>
    </div>
    @endforeach

    {{-- Navigation Buttons --}}
    @if(count($slides) > 1)
    <!--<button-->
    <!--  type="button"-->
    <!--  class="slideshow-prev absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full transition-colors duration-200 z-20"-->
    <!--  aria-label="Previous slide"-->
    <!-->-->
    <!--  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">-->
    <!--    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />-->
    <!--  </svg>-->
    <!--</button>-->
    <!--<button-->
    <!--  type="button"-->
    <!--  class="slideshow-next absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full transition-colors duration-200 z-20"-->
    <!--  aria-label="Next slide"-->
    <!-->-->
    <!--  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">-->
    <!--    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />-->
    <!--  </svg>-->
    <!--</button>-->

    {{-- Dots Navigation --}}
    <div class="slideshow-dots absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20">
      @foreach($slides as $index => $_)
      <button
        type="button"
        class="slideshow-dot h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-8' : 'bg-white/50 hover:bg-white/80 w-2' }}"
        data-dot-index="{{ $index }}"
        aria-label="Go to slide {{ $index + 1 }}"
      ></button>
      @endforeach
    </div>
    @endif
</section>
{{-- Keep the existing script tag here from the previous component definition --}}
<script> 
document.addEventListener('DOMContentLoaded', function() {
    const initializeSlideshow = (container) => {
        const slides = Array.from(container.querySelectorAll('.slide-item'));
        const dots = Array.from(container.querySelectorAll('.slideshow-dot'));
        const nextButton = container.querySelector('.slideshow-next');
        const prevButton = container.querySelector('.slideshow-prev');
        let currentSlideIndex = 0;
        let slideInterval;
        const autoSlideDelay = 7000; // Milliseconds for auto-slide

        if (slides.length === 0) {
            // console.log('No slides found in this container.');
            return; // Exit if no slides in this specific container
        }

        function showSlide(index) {
            // Ensure index is within bounds
            if (index >= slides.length) {
                index = 0;
            } else if (index < 0) {
                index = slides.length - 1;
            }

            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.style.opacity = '1';
                    slide.style.pointerEvents = 'auto'; // Make active slide interactive
                    // Optional: Add classes for text animations if you have them
                    // slide.querySelector('.slide-title')?.classList.add('animate-your-title-in');
                    // slide.querySelector('.slide-description')?.classList.add('animate-your-desc-in');
                } else {
                    slide.style.opacity = '0';
                    slide.style.pointerEvents = 'none'; // Make inactive slides non-interactive
                    // Optional: Remove text animation classes
                    // slide.querySelector('.slide-title')?.classList.remove('animate-your-title-in');
                    // slide.querySelector('.slide-description')?.classList.remove('animate-your-desc-in');
                }
            });

            if (dots.length > 0) {
                dots.forEach((dot, i) => {
                    if (i === index) {
                        dot.classList.add('bg-white', 'w-8');
                        dot.classList.remove('bg-white/50', 'w-2', 'hover:bg-white/80');
                    } else {
                        dot.classList.remove('bg-white', 'w-8');
                        dot.classList.add('bg-white/50', 'w-2', 'hover:bg-white/80');
                    }
                });
            }
            currentSlideIndex = index;
        }

        function next() {
            showSlide(currentSlideIndex + 1);
        }

        function prev() {
            showSlide(currentSlideIndex - 1);
        }

        function startAutoSlide() {
            // Clear any existing interval before starting a new one
            if (slideInterval) {
                clearInterval(slideInterval);
            }
            // Only start auto-slide if there's more than one slide
            if (slides.length > 1) {
                slideInterval = setInterval(next, autoSlideDelay);
            }
        }

        // Event Listeners
        if (nextButton) {
            nextButton.addEventListener('click', () => {
                next();
                startAutoSlide(); // Restart auto-slide timer on manual navigation
            });
        }

        if (prevButton) {
            prevButton.addEventListener('click', () => {
                prev();
                startAutoSlide(); // Restart auto-slide timer
            });
        }

        if (dots.length > 0) {
            dots.forEach(dot => {
                dot.addEventListener('click', () => {
                    const index = parseInt(dot.dataset.dotIndex);
                    if (!isNaN(index)) {
                        showSlide(index);
                        startAutoSlide(); // Restart auto-slide timer
                    }
                });
            });
        }

        // Initial Setup
        showSlide(0); // Show the first slide
        startAutoSlide(); // Start the automatic slideshow
    };

    // Find all slideshow containers on the page and initialize them
    const slideshowContainers = document.querySelectorAll('.slideshow-container');
    slideshowContainers.forEach(container => {
        initializeSlideshow(container);
    });
});
 </script>
@else
<section class="relative h-screen flex items-center justify-center bg-gray-200">
    <p class="text-gray-500">No slides to display. Add some in the admin panel!</p>
</section>
@endif