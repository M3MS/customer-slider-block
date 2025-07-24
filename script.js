import Glide from '@glidejs/glide'

const customerSlider = {
    init() {

        var glide_element = document.querySelector('.customer-slider .glide');

        if (glide_element === null) {
            return;
        }

        var glide = new Glide('.customer-slider .glide', {
            type: 'carousel',
            autoplay: 7000,
            animationDuration: 1500,
            hoverpause: true,
            gap: 0
        });

        glide.mount();
    }
};

export default customerSlider;
